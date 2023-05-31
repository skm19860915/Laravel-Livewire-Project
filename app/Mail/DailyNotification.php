<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Report;

class DailyNotification extends Mailable
{
    use Queueable, SerializesModels;

    protected $email;
    public $theme = 'fullwidth';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $now = now()->format('m/d/Y');
        $nowSql = now()->format('Y-m-d');
        $start = now()->startOfMonth()->format('Y-m-d');
        $end = now()->endOfMonth()->format('Y-m-d');

        $res = Report::dailyStats($start, $end, $nowSql, $this->email);

        //return $this->subject("$now  - Pryapus Admin Daily Stats")->view('mail.dailyNotification',$res);

        return $this->markdown('mail.dailyNotification', $res)->subject($now . ' - Pryapus Daily Stats');
    }
}
