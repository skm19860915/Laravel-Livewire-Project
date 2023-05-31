<?php

namespace App\Http\Controllers;

use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use App\Services\ZingleApiService;
use App\Jobs\ZingleLoadContactsJob;
use Illuminate\Support\Facades\Crypt;
use App\Models\{ZingleIntegration, Location};
use Illuminate\Contracts\Encryption\DecryptException;
use App\Http\Requests\{CreateZingleIntegration, UpdateZingleIntegration};

class ZingleIntegrationController extends Controller
{
    use HelperTrait;

    protected $model;
    protected $zingleApiService;

    public function __construct(ZingleIntegration $model, ZingleApiService $zingleApiService)
    {
        $this->model = $model;

        $this->zingleApiService = $zingleApiService;
    }

    public function index($locationId)
    {
        $integrationData = $this->model->getCredentials((int) $locationId);

        if (!$integrationData) {
            return view('zingle_integration.create');
        }

        try {
            $integrationData['zingle_password'] = Crypt::decryptString($integrationData['zingle_password']);
        } catch (DecryptException $e) {
            logger()->error($e->getMessage());
        }

        $data = [
            'integrationData' => $integrationData
        ];

        return view('zingle_integration.edit', $data);
    }

    public function store(CreateZingleIntegration $request)
    {
        //Do not allow more than 1 credentials per location
        $integrationData = $this->model->getCredentials(
            (int) $request->location_id
        );

        if ($integrationData) {
            abort(403);
        }

        $data = $request->all();

        $data['zingle_password'] = Crypt::encryptString($data['zingle_password']);

        $zingleData = $this->model->create($data);

        $locationId = session('current_location')->id;

        //Get service details from zingle
        $serviceDetails = $this->zingleApiService->getServiceDetails($locationId);
        if ($serviceDetails['status']['status_code'] == 200) {
            $details = $serviceDetails['result'][0];

            $data['service_id'] = $details['id'];
            $data['zingle_phone_number'] = $details['channels'][0]['value'];
            $data['channel_type_id'] = $details['channels'][0]['channel_type']['id'];

            $customFields = $details['contact_custom_fields'];

            foreach ($customFields as $customField) {
                switch ($customField['code']) {
                    case 'appt_date':
                        $data['appt_date_custom_field_id'] = $customField['id'];
                        break;
                    case 'appt_time':
                        $data['appt_time_custom_field_id'] = $customField['id'];
                        break;
                    case 'first_name':
                        $data['first_name_custom_field_id'] = $customField['id'];
                        break;
                    case 'last_name':
                        $data['last_name_custom_field_id'] = $customField['id'];
                        break;
                    case 'sign_up_date':
                        $data['sign_up_date_custom_field_id'] = $customField['id'];
                        break;
                    case 'ed_treatment_plan':
                        $data['ed_treatment_custom_field_id'] = $customField['id'];
                        break;
                    case 'trt_treatment_plan':
                        $data['trt_treatment_custom_field_id'] = $customField['id'];
                        break;
                    case 'eswt_treatment_plan':
                        $data['eswt_treatment_custom_field_id'] = $customField['id'];
                        break;
                    case 'treatment_end_date':
                        $data['treatment_end_date_custom_field_id'] = $customField['id'];
                        break;
                }
            }

            $data['contact_custom_fields'] = $customFields; //$this->zingleApiService->getContactCustomFields($locationId);

            $zingleData->update($data);

            ZingleLoadContactsJob::dispatch($this->zingleApiService, $locationId);
        }

        return back()->with('success', 'Integration created successfully');
    }

    public function update(UpdateZingleIntegration $request, ZingleIntegration $zingleIntegration)
    {

        $data = $request->all();

        $data['zingle_password'] = Crypt::encryptString($data['zingle_password']);
        $zingleIntegration->update($data);

        $locationId = session('current_location')->id;

        //Get service details from zingle
        $serviceDetails = $this->zingleApiService->getServiceDetails($locationId);
        if ($serviceDetails['status']['status_code'] == 200) {
            $details = $serviceDetails['result'][0];

            $data['service_id'] = $details['id'];
            $data['zingle_phone_number'] = $details['channels'][0]['value'];
            $data['channel_type_id'] = $details['channels'][0]['channel_type']['id'];

            $customFields = $details['contact_custom_fields'];
            foreach ($customFields as $customField) {
                switch ($customField['code']) {
                    case 'appt_date':
                        $data['appt_date_custom_field_id'] = $customField['id'];
                        break;
                    case 'appt_time':
                        $data['appt_time_custom_field_id'] = $customField['id'];
                        break;
                    case 'first_name':
                        $data['first_name_custom_field_id'] = $customField['id'];
                        break;
                    case 'last_name':
                        $data['last_name_custom_field_id'] = $customField['id'];
                        break;
                    case 'sign_up_date':
                        $data['sign_up_date_custom_field_id'] = $customField['id'];
                        break;
                    case 'ed_treatment_plan':
                        $data['ed_treatment_custom_field_id'] = $customField['id'];
                        break;
                    case 'trt_treatment_plan':
                        $data['trt_treatment_custom_field_id'] = $customField['id'];
                        break;
                    case 'eswt_treatment_plan':
                        $data['eswt_treatment_custom_field_id'] = $customField['id'];
                        break;
                    case 'treatment_end_date':
                        $data['treatment_end_date_custom_field_id'] = $customField['id'];
                        break;
                }
            }

            $data['contact_custom_fields'] = $customFields; //$this->zingleApiService->getContactCustomFields($locationId);
            $zingleIntegration->update($data);

            ZingleLoadContactsJob::dispatch($this->zingleApiService, $locationId);
        }

        return back()->with('success', 'Integration updated successfully');
    }
}
