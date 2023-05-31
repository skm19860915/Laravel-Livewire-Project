<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\{Patient, ZingleIntegration};
use App\Services\ZingleApiService;

class ZingleApplyTagsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $zingleIntegration;
    protected $zingleApiService;
    protected $locationId;
    protected $ticket;
    protected $action;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($locationId, $ticket, $action)
    {
        $this->locationId = $locationId;

        $this->ticket = $ticket;

        $this->action = $action;
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

        //Check Tag existence
        $zingleCredential = $this->zingleIntegration->getCredentials($this->locationId);
        if ($zingleCredential && $zingleCredential->isEnabled($this->locationId)) {
            $aceTagId = '';

            if (!$zingleCredential->ace_tag_id) {
                //Get ace_tag_id from API if it doesn't exist in DB
                $tagList = $this->zingleApiService->getTagList($this->locationId);
                //dd($tagList);
                if ($tagList['status']['status_code'] == 200) {
                    $tags = $tagList['result'];
                    foreach ($tags as $tag) {
                        if (trim(strtolower($tag['display_name'])) == 'ace') {
                            $zingleCredential->ace_tag_id = $tag['id'];
                            $zingleCredential->save();

                            $aceTagId = $zingleCredential->ace_tag_id;

                            break;
                        }
                    }
                }
            } else {
                $aceTagId = $zingleCredential->ace_tag_id;
            }

            if ($aceTagId) {
                $patient = Patient::find($this->ticket->patient_id);
                $contactId = $patient->zingle_contact_id;

                if ($contactId) {
                    if ($this->action == 'apply') {
                        //Apply ace tag
                        $this->zingleApiService->applyTag($this->locationId, $contactId, $aceTagId);
                    }

                    if ($this->action == 'remove') {
                        //Remove ace tag
                        $this->zingleApiService->removeTag($this->locationId, $contactId, $aceTagId);
                    }
                }
            }
        }
    }
}
