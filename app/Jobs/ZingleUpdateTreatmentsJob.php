<?php

namespace App\Jobs;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use App\Services\ZingleApiService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\{Patient, ZingleIntegration};

class ZingleUpdateTreatmentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $zingleIntegration;
    protected $zingleApiService;
    protected $locationId;
    protected $ticketData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $locationId, $ticketData)
    {
        $this->locationId = $locationId;

        $this->ticketData = $ticketData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->zingleIntegration = new ZingleIntegration();

        $this->zingleApiService = new ZingleApiService();

        $zingleCredential = $this->zingleIntegration->getCredentials($this->locationId);
        if ($zingleCredential && $zingleCredential->isEnabled($this->locationId)) {
            $patient = Patient::find($this->ticketData['patient_id']);
            $contactId = $patient->zingle_contact_id;

            if ($contactId) {
                $this->zingleApiService->updateContact($this->locationId, $contactId,  $this->ticketData);
            }
        }
    }
}
