<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactEmail;
use App\Models\TreatmentType;
use App\Models\EmailJourney;

class CreateEmail extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $subject = '';
    public string $body = '';
    public $trigger_date_type;
    public $treatment_type_id;
    public $days = 0;

    public string $saved = '';

    protected $rules = [
        'name' => ['required', 'min:2'],
        'subject' => ['required', 'min:2'],
        'body' => 'required',
        'trigger_date_type' => 'required',
        'treatment_type_id' => 'required',
        'days' => ''
    ];

    public function mount()
    {
        $this->treatments = TreatmentType::orderBy('id', 'DESC')->get();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function saveTemplate()
    {
        $validData = $this->validate();
        $this->saved = $this->storeEmailJouneysTemplate($validData) ? 'success' : 'failed';
    }

    public function render()
    {
        return view('livewire.create-email')->extends('layouts.auth')->section('content');
    }

    public function storeEmailJouneysTemplate($obj)
    {
        $emailJourney = new EmailJourney;
        $data['name'] = $obj['name'];
        $data['subject'] = $obj['subject'];
        $data['body'] = $obj['body'];
        $data['trigger_date_type'] = $obj['trigger_date_type'];
        $data['days'] = $obj['days'];
        $data['treatment_type_id'] = ($obj['treatment_type_id'] == 0) ? null : $obj['treatment_type_id'];
        $data['status'] = false;

        $saved = $emailJourney->storeEmailJourney($data);
        return $saved;
    }
}
