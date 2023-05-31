<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\ZingleApiService;
use App\Traits\HelperTrait;
use App\Models\{Patient, ZingleIntegration};

class ZingleLoadContactsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use HelperTrait;

    protected $zingleApiService;

    protected $patientModel;

    protected $locationId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ZingleApiService $zingleApiService, $locationId)
    {
        $this->zingleApiService = $zingleApiService;

        $this->locationId = $locationId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $page = (int) 1;
        $pageSize = (int) 10;
        $totalPages = 1;

        do {
            $contacts = $this->zingleApiService->getAllContacts($this->locationId, $page, $pageSize);

            if ($contacts['status']['status_code'] == 200) {
                $totalPages = $contacts['status']['total_pages'];

                foreach ($contacts['result'] as $contact) {
                    foreach ($contact['channels'] as $channel) {
                        if ($channel['channel_type']['type_class'] == 'PhoneNumber') {
                            $zinglePhone = $channel['formatted_value'];
                            if ($zinglePhone) {
                                $patient = Patient::where('location_id', $this->locationId)
                                    ->where('cell_phone', $zinglePhone)
                                    ->first();

                                if ($patient) {
                                    $patient->zingle_contact_id = $contact['id'];
                                    $patient->save();
                                }
                            }
                        }
                    }
                }

                ++$page;
            }
        } while ($page <= $totalPages);
    }
}
