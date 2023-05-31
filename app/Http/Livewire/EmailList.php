<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\EmailJourney;
use Illuminate\Http\Request;
use App\Models\EmailCommunication;

class EmailList extends Component
{
    public $email_journeys_list;

    public function render()
    {
        $this->email_journeys_list = EmailJourney::select('email_journeys.*', 'treatment_types.description as description')
        ->leftJoin('treatment_types', 'treatment_type_id', '=', 'treatment_types.id')->get();

        return view('livewire.email-list')->extends('layouts.auth')->section('content');
    }

    public function updateStatus(Request $request)
    {
        $emailJourney = new EmailJourney;
        $record = EmailJourney::where('id', $request['id'])->first();

        $emailComms = EmailCommunication::where('name', $record['name'])->get();
        if(count($emailComms) > 0){
            return response()->json(-1);
        }

        $data['name'] = $record['name'];
        $data['subject'] = $record['subject'];
        $data['body'] = $record['body'];
        $data['trigger_date_type'] = $record['trigger_date_type'];
        $data['days'] = $record['days'];
        $data['treatment_type_id'] = $record['treatment_type_id'];
        $data['status'] = ($record['status'] == 0) ? 1 : 0;

        $updated = $emailJourney->updateEmailJourney($request['id'], $data);
        if($updated){
            return response()->json(1);
        }

        return response()->json(0);
    }
}
