<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use App\Services\ZingleApiService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\{Patient, ZingleIntegration};

class ZingleSMSJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $appointmentData;

    protected $zingleIntegration;

    protected $message;

    protected $locationId;

    protected $sendSms;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        $locationId,
        ZingleIntegration $zingleIntegration,
        $appointmentData,
        $message,
        bool $sendSms
    ) {
        $this->appointmentData = $appointmentData;

        $this->message = $message;

        $this->locationId = $locationId;

        $this->sendSms = $sendSms;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Get patient details
        $patient = Patient::find($this->appointmentData->patient_id);

        $zingleApiService = new ZingleApiService();

        if ($patient) {
            if ($patient->zingle_contact_id) { //patient exists then update
                $contact = $zingleApiService->updateContact($this->locationId, $patient->zingle_contact_id, $this->appointmentData);

                if ($contact['status']['status_code'] == 404) {
                    $contact = $zingleApiService->createContact($this->locationId, $patient, $this->appointmentData);
                }
            } else {
                if ($patient->cell_phone) {
                    $contact = $zingleApiService->createContact($this->locationId, $patient, $this->appointmentData);
                }
            }

            if (isset($contact['zingle_error_code'])) {
                $patient->zingle_error_code = $contact['zingle_error_code'] ?? null;
                $patient->zingle_error_message = $contact['zingle_error_message'] ?? null;
                $patient->save();
            } else if (isset($contact['result']['id'])) {
                //Update patient data
                $patient->zingle_contact_id = $contact['result']['id'];
                $patient->save();

                if ($this->sendSms) {
                    $zingleApiService->sendMessage($this->locationId, $this->message, $patient, $this->appointmentData);
                }
            }
        }
    }
}
