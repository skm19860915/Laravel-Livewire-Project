<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\ContactEmail;
use Illuminate\Support\Facades\Mail;
use App\Lib\EmailTemplate;
use App\Models\EmailCommunication;

class EmailQueueJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $template;

    public function __construct(EmailTemplate $emailTemplate)
    {
        $this->template = $emailTemplate;
    }

    public function handle()
    {
        $id = $this->job->getJobId();

        try
        {
            Mail::to($this->template->email)->send(new ContactEmail($this->template));
            $this->updateEmailCommunication($id, 1);
        }
        catch(Exception $e)
        {
            logger()->error($e->getMessage());
            $this->updateEmailCommunication($id, -1);
        }
    }

    public function updateEmailCommunication($id, $status)
    {
        $emailCommunication = new EmailCommunication;
        $record = EmailCommunication::where('job_id', $id)->first();

        $data['patient_id'] = $record['patient_id'];
        $data['job_id'] = $id;
        $data['name'] = $record['name'];
        $data['trigger_date_type'] = $record['trigger_date_type'];
        $data['status'] = $status;

        $updated = $emailCommunication->updateEmailCommunication($id, $data);
    }
}
