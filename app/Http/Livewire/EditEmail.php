<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Patient;
use Livewire\WithFileUploads;
use App\Models\TreatmentType;
use App\Models\Schedule;
use App\Models\EmailJourney;

class EditEmail extends Component
{
    use WithFileUploads;

    public $name;
    public $subject;
    public $body;
    public $trigger_date_type;
    public $treatment_type_id;
    public $days = 0;
    public $edit_id;
    public $status;

    public string $updated = '';

    protected $rules = [
        'name' => ['required', 'min:2'],
        'subject' => ['required', 'min:2'],
        'body' => 'required',
        'trigger_date_type' => 'required',
        'treatment_type_id' => 'required',
        'days' => ''
    ];

    public function mount($id)
    {
        $email_journeys = EmailJourney::where('id', $id)->first();
        $this->name = $email_journeys->name;
        $this->subject = $email_journeys->subject;
        $this->body = $email_journeys->body;
        $this->trigger_date_type = $email_journeys->trigger_date_type;
        $this->treatment_type_id = ($email_journeys->treatment_type_id == null) ? 0 : $email_journeys->treatment_type_id;
        $this->days = $email_journeys->days;
        $this->status = $email_journeys->status;
        $this->edit_id = $id;

        $this->treatments = TreatmentType::orderBy('id', 'DESC')->get();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updateTemplate($id, $status)
    {
        $validData = $this->validate();
        $this->updated = $this->updateEmailJouneysTemplate($id, $status, $validData) ? 'success' : 'failed';
    }

    public function render()
    {
        return view('livewire.edit-email')->extends('layouts.auth')->section('content');
    }

    public function updateEmailJouneysTemplate($id, $status, $obj)
    {
        $emailJourney = new EmailJourney;
        $data['name'] = $obj['name'];
        $data['subject'] = $obj['subject'];
        $data['body'] = $obj['body'];
        $data['trigger_date_type'] = $obj['trigger_date_type'];
        $data['days'] = $obj['days'];
        $data['treatment_type_id'] = ($obj['treatment_type_id'] == 0) ? null : $obj['treatment_type_id'];
        $data['status'] = $status;

        $updated = $emailJourney->updateEmailJourney($id, $data);
        return $updated;
    }
}
