<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Patient;
use App\Models\Schedule;
use App\Jobs\ZingleSMSJob;
use App\Models\ScheduleType;
use Illuminate\Http\Request;
use App\Models\ScheduleBlock;
use App\Models\ZingleIntegration;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Lib\EmailTemplate;
use App\Models\EmailJourney;
use App\Models\EmailCommunication;
use App\Events\EventForEmailing;
use App\Jobs\EmailQueueJob;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    protected $zingleIntegration;

    public function __construct()
    {
        $this->zingleIntegration = new ZingleIntegration();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Schedule $schedule)
    {
        $res['appointmentsToday'] = $schedule->getAppointmentsWithPatientOfToday();
        return view('schedule.index', $res);
    }


    public function getAppts(Request $request, Schedule $schedule)
    {
        $current_location = session('current_location')->id;
        $req = $request->all();
        $start = Carbon::parse($req['start'])->format('Y-m-d');
        $end = Carbon::parse($req['end'])->format('Y-m-d');

        $appts['appts'] = $schedule->getAppointmentsWithPatient($start, $end);
        $appts['blocks'] = ScheduleBlock::where('location_id', $current_location)->where('date', '>=', $start)->where('date', '<=', $end)->get();

        return response()->json($appts);
    }


    public function getApptBlocks(Request $request)
    {
        $current_location = session('current_location')->id;
        $blocks = ScheduleBlock::where('location_id', $current_location)->where('date', '>=', $request['start'])->where('date', '<=', $request['end'])->get();
        return response()->json($blocks);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Patient $patient, Schedule $schedule)
    {
        $current_location = session('current_location')->id;

        if (!session('cl')) {
            session(['cl' => $current_location]);
        }
        else {
            if (url()->previous() == url('create/appointment'))
                if (session('cl') != $current_location) {
                    // dd(session('cl'),$current_location);
                    session()->forget('cl');
                    return  redirect()->route('schedule.index');
                };
        }
        $res['page_name'] = "Schedule";
        $res['page_info'] = "New Appointment";
        $res['card_title'] = "New Appointment";
        //Marketing Source for current location
        if (!session('current_location')) return back()->with('error', 'Please select location.');
        $res['marketing_source'] = session('current_location')->marketing_sources->where('disable', 0);
        //$res['patients'] = $patient->getAll();
        $res['appointment_types'] = $schedule->appointment_types;

        return view('schedule.create', $res);
    }

    public function newcreate(Schedule $appointment) //this is used. terrible name. it's called when a patient is checked-in.
    {
        $schedule = new Schedule;
        $patient = new Patient;
        $current_location = session('current_location')->id;

        if (!session('cl')) {
            session(['cl' => $current_location]);
        } else {
            if (url()->previous() == url('create/appointment'))  if (session('cl') != $current_location) {
                // dd(session('cl'),$current_location);
                session()->forget('cl');
                return  redirect()->route('schedule.index');
            };
        }
        $res['page_name'] = "Schedule";
        $res['page_info'] = "New Appointment";
        $res['card_title'] = "New Appointment";
        //Marketing Source for current location
        if (!session('current_location')) return back()->with('error', 'Please select location.');
        $res['marketing_source'] = session('current_location')->marketing_sources->where('disable', 0);
        $res['patients'] = $patient->getAll();
        $res['appointment_types'] = $schedule->appointment_types;
        $appointment->date = Carbon::parse($appointment->date)->format('m/d/Y');

        $res['appt'] = $appointment;

        return view('schedule.newcreate', $res);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request =  $request->all();
        //dd($request);
        $schedule = new Schedule();
        Validator::make($request, $schedule->createAppointmentRules)->validate();

        if (!isset($request['prevent_zingle_confirmation'])) {
            $request['prevent_zingle_confirmation'] = 0;
        }

        $create = $schedule->storeAppointment($request);

        if ($create['status']) {
            //Dispatch job to send SMS if Integration enabled and not a reorder
            $locationId = session('current_location')->id;
            $locationName = session('current_location')->location_name;

            if (
                $this->zingleIntegration->isEnabled((int) $locationId)
                && $request['reorder'] == 0
                && $request['prevent_zingle_confirmation'] == 0
            ) {
                $apptDate = $request['date_appointment'];
                $apptTime = $request['time_appointment'];
                $message = <<<LNGSTR
                Thank you for booking your appointment with $locationName.
                Appointment Date/Time: $apptDate $apptTime
                We look forward to seeing you!
                LNGSTR;

                ZingleSMSJob::dispatch(
                    $locationId,
                    $this->zingleIntegration,
                    $create['data'],
                    $message,
                    !$request['prevent_zingle_confirmation']
                );
            }

            $this->sendEmailWithAppointmentTemplate($locationName, $create['data']);

            return redirect()->route('schedule.index')->with('success', $create['msg']);
        }
        return back()->with('error', $create['msg']);
    }

    public function custom_dispatch($job): int
    {
        return app(\Illuminate\Contracts\Bus\Dispatcher::class)->dispatch($job);
    }

    public function sendEmailWithAppointmentTemplate($locationName, $data)
    {
        $patient_id = $data['patient_id'];
        $appointment_date = Carbon::parse($data['date'])->format('Y-m-d');
        $patient = Patient::find($patient_id);

        $appointment_template  = EmailJourney::getTemplate(2, 4);
        $job_id = 0;

        if(!empty($appointment_template))
        {
            try
            {
                $emailTemplate = new EmailTemplate;
                $emailTemplate->email = $patient->email;
                $emailTemplate->name = $patient->first_name." ".$patient->last_name;
                $emailTemplate->subject = $appointment_template->subject;
                $emailTemplate->body = $appointment_template->body;
                $emailTemplate->date = $appointment_date;
                $emailTemplate->days = $appointment_template->days;
                $emailTemplate->location_name = $locationName;

                $job = new EmailQueueJob($emailTemplate);
                $job->delay(now()->addDays($emailTemplate->days)); //addMinutes
                $job_id = $this->custom_dispatch($job);
            }
            catch(Exception $e)
            {
                return 0;
            }
        }

        if($job_id > 0)
        {
            $this->saveEmailCommunication($appointment_template->name, $patient_id, $job_id);
        }

        return 0;
    }

    public function saveEmailCommunication($temp_name, $patient_id, $job_id)
    {
        $emailCommunication = new EmailCommunication;
        $data['patient_id'] = $patient_id;
        $data['job_id'] = $job_id;
        $data['name'] = $temp_name;
        $data['trigger_date_type'] = 2;

        $id = $emailCommunication->storeEmailCommunication($data);
        return $id;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function edit(Schedule $appointment, Patient $patient)
    {
        $current_location = session('current_location');
        if ($appointment->location_id !== $current_location->id) return redirect()->route('schedule.index');

        //show check in patient button;
        $apptdate = Carbon::parse($appointment->date);
        $hidden = false;
        if (now()->format('Y-m-d') == $apptdate->format('Y-m-d') || now()->greaterThan($apptdate)) {
            $hidden = true;
        }
        $res['hidden'] = $hidden;
        // $res['hidden'] = true;
        //parse date
        $appointment->date = $apptdate->format('m/d/Y');
        $current_location = session('current_location')->id;
        if (!session('cl')) {
            session(['cl' => $current_location]);
        } else {

            if (url()->previous() == url("edit/appointment/$appointment->id"))  if (session('cl') != $current_location) {
                session()->forget('cl');
                return  redirect()->route('schedule.index');
            };
        }
        $ticket = $appointment->getTicket->first();


        if ($ticket) return redirect()->route('ticket.edit', [
            'ticket' => $ticket->id,
            'appointment' => $appointment->id
        ]);
        /**
         * user should select location before see content of page
         */
        if (!session('current_location')) return back()->with('error', 'Please select location.');

        $res['page_name'] = "Schedule";
        $res['page_info'] = "Edit Appointment";
        $res['card_title'] = "Edit Appointment";
        /**
         *  get Marketing Source that belong to current location
         */
        $res['marketing_source'] = session('current_location')->marketing_sources;
        /**
         * get targeted appointment for update
         */
        $res['appointment'] = $appointment;
        /**
         * get all patient for currenct patient form
         */
        $res['patients'] = $patient->getAll();
        /**
         * get patient he belong to appointment
         */
        $res['patient'] = $appointment->patient;
        /**
         * get appoinmtent type from model
         */
        $res['appointment_types'] = $appointment->appointment_types;

        /**
         * this section for display alert in the page
         */
        /**
         * Schedule types
         *[
         *   'Unconfirmed / No Show' => 1 ,
         *   'Confirmed/Ticket' => 2 ,
         *   'Confirmed No Show' => 3 ,
         *   'Cancelled'  => 4,
         *   'Rescheduled' => 5 ,
         *   'Voicemail' => 6,
         *   'No Sale / Rescheduled during office visit / Marked for Revisit' => 7
         *]
         */
        // schedule type description
        $desc = $appointment->scheduleType->description;
        // schedule type id
        $scheduleType = $appointment->scheduleType->id;
        // get date of update
        $date = $appointment->getDate($appointment, 'updated_at');
        // time of appointment 12:00 AM
        $time = $appointment->time;
        // name of user how update appointment
        if ($appointment->updatedBy) {
            //$name = $appointment->updatedBy->first_name .' '. $appointment->updatedBy->last_name;
            $name = $appointment->updatedBy->username;
        } elseif ($appointment->createdBy) {
            //$name = $appointment->createdBy->first_name .' '. $appointment->createdBy->last_name;
            $name = $appointment->createdBy->username;
        } else {
            $name = '';
        }

        // get alert type  (default value  =  dark )
        $alert_type = ScheduleType::getAlertType($scheduleType);
        // if voicemail add aleft "LEFT" word before description
        if ($scheduleType ==  6)         $desc = "LEFT " . $desc;
        //alert content
        $alert_content = "<b>$desc</b> $date by <b>$name</b> ";
        $res['alert_content'] = $alert_content;
        $res['alert_type'] = $alert_type;
        /**
         * End of Section
         */
        $res['cancelled'] =  $scheduleType == 4  ? 1 : 0;
        $res['voicemail'] =  $scheduleType == 6  ? 1 : 0;
        $res['confirm'] =    $scheduleType == 3  ? 1 : 0;
        return view('schedule.edit', $res);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Schedule $appointment)
    {
        //
        $request =  $request->all();
        $schedule = new Schedule();
        Validator::make($request, $schedule->editAppointmentRules)->validate();
        $update = FALSE;

        if (!isset($request['prevent_zingle_confirmation'])) {
            $request['prevent_zingle_confirmation'] = 0;
        }

        if (strtolower($request['action']) == strtolower('update')) $update = $schedule->updateAppointment($appointment, $request);
        if (strtolower($request['action']) == strtolower('confirm')) $update = $schedule->confirm($appointment, $request);
        if (strtolower($request['action']) == strtolower('reschedule')) $update = $schedule->rescheduled($appointment, $request);
        if (strtolower($request['action']) == strtolower('cancel')) $update = $schedule->cancel($appointment, $request);
        if (strtolower($request['action']) == strtolower('voicemail')) $update = $schedule->voiceMail($appointment, $request);
        if (strtolower($request['action']) == strtolower('delete')) $update = $schedule->deleteAppointment($appointment);

        if ($update['status']) {
            if (strtolower($request['action']) == 'delete')
                return redirect()->route('schedule.index')->with('success', $update['msg']);
        }

        $locationId = session('current_location')->id;
        $locationName = session('current_location')->location_name;

        if ($update['status'] && strtolower($request['action']) == 'reschedule' && $request['reorder'] == 0) {
            if ($this->zingleIntegration->isEnabled((int) $locationId)) {
                $apptDate = $request['date_appointment'];
                $apptTime = $request['time_appointment'];
                $message = <<<LNGSTR
                Your appointment with $locationName was rescheduled.
                Appointment Date/Time: $apptDate $apptTime
                We look forward to seeing you!
                LNGSTR;

                ZingleSMSJob::dispatch(
                    $locationId,
                    $this->zingleIntegration,
                    $update['data'],
                    $message,
                    !$request['prevent_zingle_confirmation']
                );
            }

            return  redirect()->route('appointment.edit', ['appointment' => $appointment->id])
                ->with('success', $update['msg']);
        }

        if ($update['status'] && strtolower($request['action']) == 'cancel') {
            if ($this->zingleIntegration->isEnabled((int) $locationId)) {

                $message = <<<LNGSTR
                Your appointment with $locationName was cancelled.
                Status: Cancelled
                LNGSTR;

                ZingleSMSJob::dispatch(
                    $locationId,
                    $this->zingleIntegration,
                    $update['data'],
                    $message,
                    !$request['prevent_zingle_confirmation']
                );
            }

            return  redirect()->route('appointment.edit', ['appointment' => $appointment->id])
                ->with('success', $update['msg']);
        }

        if ($update['status'])
        {
            if(!empty($update['data']))
            {
                $this->deleteUnsentEmailCommunications($update['data']['patient_id'], 2, 0); // trigger_date_type = 2 (appointment date); status = 0;
                $this->sendEmailWithAppointmentTemplate($locationName, $update['data']);
            }

            return  redirect()->route('appointment.edit', ['appointment' => $appointment->id])
                ->with('success', $update['msg']);
        }

        return back()->with('error', $update['msg']);
    }

    public function deleteUnsentEmailCommunications($patient_id, $trigger_date_type, $status)
    {
        $records = EmailCommunication::where('patient_id', $patient_id)->get();
        if(count($records) > 0)
        {
            foreach($records as $record)
            {
                DB::table('jobs')->where('id', '=', $record->job_id)->delete();
            }

            $patient_id = $records[0]['patient_id'];
            DB::delete('delete from email_communications where patient_id = ?',[$patient_id]);
        }
    }

    public function cancel(Schedule $appointment)
    {

        $current_location = session('current_location');
        if ($appointment->location_id !== $current_location->id) return redirect()->route('schedule.index');

        //No Sale / Rescheduled during office visit / Marked for Revisit
        $scheduleType = 7;
        $appointment->schedule_type_id = $scheduleType;
        $appointment->save();
        return redirect()->route('schedule.index')->with('success', 'Schedule type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */

    public function destroy(Schedule $schedule)
    {
        //
    }

    public function ajaxSearchPatient(Request $req)
    {

        $data = Schedule::ajaxSearchPatient($req);
        return response()->json($data);
    }
}
