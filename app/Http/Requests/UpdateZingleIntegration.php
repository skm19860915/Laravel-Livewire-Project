<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateZingleIntegration extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'location_id' => 'required|numeric',
            'zingle_username' => 'required|string|unique:zingle_integrations,zingle_username,' . $this->zingle_integration->id,
            'zingle_password' => 'required|string',
            'enabled' => 'required|numeric',
        ];
    }
}
