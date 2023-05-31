<?php

namespace App\Models;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;


    public  $createAppointmentRules = [
        'date_appointment' => "required",
        'first_name'       => ["string", 'nullable'],
        'last_name'        => ["string", 'nullable'],
        'city'             => ["string", 'nullable'],
    ];

    public  $editAppointmentRules = [
        'date_appointment' => "required",
    ];

    public $appointment_types = ['Office Visit', 'Procedure', 'Other'];


    // public function checktime($time)
    // {
    //    return  dd($time);
    // }
    // public function getDateAttribute($value)
    // {
    //     return Carbon::parse($value)->format(config('app.date_format'));
    // }

    public function getTicket()
    {
        return $this->hasMany(Ticket::class);
    }

    public function _createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function _updateBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }


    // public function getCreatedAtAttribute($value)
    // {
    //     return Carbon::parse($value)->format(config('app.date_format'));
    // }

    // public function getUpdatedAtAttribute($value)
    // {
    //     return Carbon::parse($value)->format(config('app.date_format'));
    // }

    public function getAppointmentsWithPatient($start, $end)
    {
        ///get current location
        $current_location = session('current_location');
        /// if not location selected return ERROR
        if (!$current_location) {
            Session::flash('error', 'Please select location.');
            return collect();
        };
        /// return appointments and patient
        $appts = Schedule::join('patients', 'patients.id', 'schedules.patient_id')
            ->join('schedule_types', 'schedules.schedule_type_id', 'schedule_types.id')
            ->where('schedules.location_id', $current_location->id)
            ->select('schedules.*', 'schedules.new_customer  as new', 'patients.first_name', 'patients.last_name', 'schedule_types.description as schedule_type')
            //->selectRaw("(SELECT IFNULL(SUM(tickets.total),0) FROM  tickets WHERE tickets.patient_id = patients.id ) as total")
            ->selectRaw("(SELECT IFNULL(SUM(tickets.amount_paid_during_office_visit),0) FROM  tickets WHERE schedules.id = tickets.schedule_id ) as total")
            ->selectRaw("(SELECT COUNT(*) FROM  tickets WHERE tickets.schedule_id = schedules.id ) as ticket_count")
            ->whereBetween('schedules.date', [$start, $end])
            ->get()
            ->map(function ($appt) use ($current_location) {
                $date = Carbon::parse($appt->date . $appt->time, $current_location->time_zone);
                if ($appt->ticket_count == 0 && $appt->schedule_type_id == 1) {
                    if ($date->lt(now($current_location->time_zone))) {
                        $appt->schedule_type_id = 8;
                        $appt->save();
                    }
                }
                return $appt;
            });
        return $appts;
    }

    public function getAppointmentsWithPatientOfToday()
    {
        ///get current location
        $current_location = session('current_location');
        /// if not location selected return ERROR
        if (!$current_location) return collect();
        /// return appointments and patient
        return self::join('patients', 'patients.id', 'schedules.patient_id')
            ->where('schedules.location_id', $current_location->id)
            ->whereDate('schedules.date', now()->format('Y-m-d'))
            ->select('schedules.*', 'patients.first_name', 'patients.last_name', 'patients.home_phone', 'patients.cell_phone')
            ->get();
    }

    public function storeAppointment($params)
    {

        try {
            /// current location
            $current_location = session('current_location');
            if (!$current_location) return ['status' => 0, 'msg' => 'Please Select location.', 'data' => null];
            /// check time
            $check = $this->checkTime($params['time_appointment']);
            if (!$check['status']) return ['status' => 0, 'msg' => $check['msg'], 'data' => null];
            if (strtolower($params['currentOrnew']) == 'new') {
                $patient = new Patient;
                $newPatient = $patient->storePatient($current_location->id, $params);
                if (!$newPatient['status']) return ['status' => 0, 'msg' => $newPatient['msg'], 'data' => null];
                $self =  new self;
                $self->patient_id = $newPatient['data']->id;
                $self->date = Carbon::parse($params['date_appointment']);
                $self->time = $params['time_appointment'];
                $self->note = $params['appointment_note'];
                $self->appointment_type = $params['appointment_type'];
                $self->schedule_type_id = 1;
                $count =  self::where('patient_id', $self->patient_id)->count();
                $self->new_customer = $count ? 0 : 1;
                $self->location_id = $current_location->id;
                $self->created_by = auth()->user()->id;
                $self->updated_by = auth()->user()->id;
                if (isset($params['reorder'])) {
                    $self->reorder = $params['reorder'];
                }
                $self->save();
                $self = $self->fresh();
                return ['status' => 1, 'msg' => "Appointment created successfully.", 'data' => $self];
            }
            if (strtolower($params['currentOrnew']) == 'current') {
                $patient = Patient::find($params['current_patient']);
                if (!$patient) return ['status' => 0, 'msg' => "Patient does not exist", 'data' => null];
                $self =  new self;
                $self->patient_id = $patient->id;
                $self->date = Carbon::parse($params['date_appointment']);
                $self->time = $params['time_appointment'];
                $self->note = $params['appointment_note'];
                $self->appointment_type = $params['appointment_type'];
                $self->schedule_type_id = 1;
                $count =  self::where('patient_id', $self->patient_id)->count();
                $self->new_customer = $count ? 0 : 1;
                // $self->new_customer = 0;
                $self->location_id = $current_location->id;
                $self->created_by = auth()->user()->id;
                $self->updated_by = auth()->user()->id;
                $self->prevent_zingle_confirmation = $params['prevent_zingle_confirmation'];
                if (isset($params['reorder'])) {
                    $self->reorder = $params['reorder'];
                }
                $self->save();
                return ['status' => 1, 'msg' => "Appointment created successfully.", 'data' => $self];
            }
        } catch (Exception $e) {
            return ['status' => 0, 'msg' => $e->getMessage(), 'data' => null];
        }
    }


    public function updateAppointment(self $self, $params)
    {

        try {
            ///current location
            $current_location = session('current_location');
            if (!$current_location) return ['status' => 0, 'msg' => 'Please select location.', 'data' => null];
            // check time
            $check = $this->checkTime($params['time_appointment']);
            if (!$check['status']) return ['status' => 0, 'msg' => $check['msg'], 'data' => null];
            $self->date = Carbon::parse($params['date_appointment']);
            $self->time = $params['time_appointment'];
            $self->note = $params['appointment_note'];
            $self->appointment_type = $params['appointment_type'];
            $self->prevent_zingle_confirmation = $params['prevent_zingle_confirmation'];
            if (isset($params['reorder'])) {
                $self->reorder = $params['reorder'];
            }
            $self->location_id = $current_location->id;
            $self->updated_by = auth()->user()->id;
            $self->save();
            return ['status' => 1, 'msg' => "Appointment updated successfully.", 'data' => $self];
        } catch (Exception $e) {
            return ['status' => 0, 'msg' => $e->getMessage(), 'data' => null];
        }
    }

    public function rescheduled(self $self, $params)
    {
        try {
            // check time
            $check = $this->checkTime($params['time_appointment']);
            if (!$check['status']) return ['status' => 0, 'msg' => $check['msg'], 'data' => null];
            // set new date
            $self->date = Carbon::parse($params['date_appointment']);
            // set new time
            $self->time = $params['time_appointment'];
            // update type
            $self->appointment_type = $params['appointment_type'];
            // update note
            $self->note = $params['appointment_note'];
            // get schedule type id where is Rescheduled
            $rescheduled = 5;
            // update Schedule type
            $self->schedule_type_id = $rescheduled;
            $self->prevent_zingle_confirmation = $params['prevent_zingle_confirmation'];
            // User ID  How Make Update
            $self->updated_by = auth()->user()->id;
            if (isset($params['reorder'])) {
                $self->reorder = $params['reorder'];
            }

            // save changes
            $self->save();
            // response
            return ['status' => 1, 'msg' => "Appointment updated successfully.", 'data' => $self];
        } catch (Exception $e) {
            return ['status' => 0, 'msg' => $e->getMessage(), 'data' => null];
        }
    }

    public function cancel(self $self, $params)
    {
        try {
            // get schedule type id where is Rescheduled
            $cancelled = 4;
            // update Schedule type
            $self->schedule_type_id = $cancelled;
            // User ID  How Make Update
            $self->updated_by = auth()->user()->id;
            if (isset($params['reorder'])) {
                $self->reorder = $params['reorder'];
            }

            $self->prevent_zingle_confirmation = $params['prevent_zingle_confirmation'];

            // save changes
            $self->save();

            // response
            return ['status' => 1, 'msg' => "Appointment updated successfully.", 'data' => $self];
        } catch (Exception $e) {
            return ['status' => 0, 'msg' => $e->getMessage(), 'data' => null];
        }
    }

    public function voiceMail(self $self, $params)
    {
        try {
            // get schedule type id where is Voicemail
            $voicemail  = 6;
            // update Schedule type
            $self->schedule_type_id = $voicemail;
            // User ID  How Make Update
            $self->updated_by = auth()->user()->id;
            if (isset($params['reorder'])) {
                $self->reorder = $params['reorder'];
            }

            $self->prevent_zingle_confirmation = $params['prevent_zingle_confirmation'];

            // save changes
            $updated = $self->save();
            // response
            return ['status' => 1, 'msg' => "Appointment updated successfully.", 'data' => $updated];
        } catch (Exception $e) {
            return ['status' => 0, 'msg' => $e->getMessage(), 'data' => null];
        }
    }

    public function deleteAppointment(self $self)
    {
        try {
            $self->delete();
            // response
            return ['status' => 1, 'msg' => "Appointment deleted successfully.", 'data' => null];
        } catch (Exception $e) {
            return ['status' => 0, 'msg' => $e->getMessage(), 'data' => null];
        }
    }

    public function confirm(self $self, $params)
    {
        try {
            // get schedule type id where is Rescheduled
            $confirmed =  3;
            // update Schedule type
            $self->schedule_type_id = $confirmed;
            // User ID  How Make Update
            $self->updated_by = auth()->user()->id;
            if (isset($params['reorder'])) {
                $self->reorder = $params['reorder'];
            }
            $self->prevent_zingle_confirmation = $params['prevent_zingle_confirmation'];
            // save changes
            $self->save();
            // response
            return ['status' => 1, 'msg' => "Appointment updated successfully.", 'data' => null];
        } catch (Exception $e) {
            return ['status' => 0, 'msg' => $e->getMessage(), 'data' => null];
        }
    }

    public function checkTime($params)
    {
        $time = explode(' ', trim($params));
        if (!in_array(strtolower($time[1]), ['am', 'pm'])) return ['status' => 0, 'msg' => 'Incorrect Time >> ' . $time[1], 'data' => null];
        // check hours and mints
        $time = explode(':', $time[0]);
        $time_hour = (int) $time[0];
        $time_mints = is_string($time[1]) ? false : (int) $time[1];
        if (!$time) {
            return ['status' => 0, 'msg' => "[$params] invalid time", 'data' => null];
        } else {
            if (!in_array($time[1], [00, 15, 30, 45])) {
                return ['status' => 0, 'msg' => "[$params] invalid time", 'data' => null];
            }
        }
        // dd($time,$time_hour,$time_mints);
        if ($time_hour > 12) {
            return ['status' => 0, 'msg' => 'Hour should be less than 12', 'data' => null];
        }
        if ($time_mints >= 60) {
            return ['status' => 0, 'msg' => 'Minutes should be less than 60', 'data' => null];
        }

        return ['status' => 1, 'msg' => 'ok', 'data' => null];
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }


    public function scheduleType()
    {
        return $this->belongsTo(ScheduleType::class);
    }


    public function getDate($appointment, $date)
    {

        if (!$appointment->{$date}) {
            $date = 'created_at';
        }
        //dd($appointment->{$date});
        // get date with timezone
        $dateString = $appointment->{$date}->toDateTimeString();
        $timeString = explode(' ', $dateString)[1];
        $mintString = explode(':', $timeString)[1];
        // dd($timeString,$mintString);
        $date = $appointment->{$date}->tz(session('current_location')->time_zone);
        // if DST  add hour
        date('I', time()) ? $date->addHours(1) : '';
        $date->addHours(1);
        // format date
        $date = $date->format("m/d/Y h:$mintString A");
        // dd($date);
        return $date;
    }

    static function ajaxSearchPatient($req)
    {

        $data = Patient::where('location_id', session('current_location')->id)
            ->where(function ($q) use ($req) {
                $q->where('first_name', 'like', '%' . $req->searchTerm . '%')
                    ->orWhere('last_name', 'like', '%' . $req->searchTerm . '%');
            })->get();

        return $data;
    }
}
