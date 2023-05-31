<?php

namespace App\Console\Commands;

use App\Mail\DailyNotification;
use App\Models\DailyStatsNotification;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class DailyNotificationCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:dailynotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily sales stats to all emails';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $emails = DailyStatsNotification::get();
        foreach($emails as $e)
        {
            try{
                Mail::to($e->email)->send(new DailyNotification($e->email));
                //logger()->info('Notifications sent to '. $e->email);
            }catch(Exception $e){
                logger()->error($e->getMessage());
            }
        }
    }
}
