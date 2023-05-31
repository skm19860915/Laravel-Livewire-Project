<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZingleIntegration extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'zingle_username',
        'zingle_password',
        'enabled',
        'zingle_phone_number',
        'service_id',
        'channel_type_id',
        'contact_custom_fields',
        'appt_date_custom_field_id',
        'appt_time_custom_field_id',
        'first_name_custom_field_id',
        'last_name_custom_field_id',
        'ace_tag_id',
        'sign_up_date_custom_field_id',
        'ed_treatment_custom_field_id',
        'trt_treatment_custom_field_id',
        'eswt_treatment_custom_field_id',
        'treatment_end_date_custom_field_id'
    ];

    const STATUS_DISABLED = 0;
    const STATUS_ENABLED = 1;

    public function getCredentials(int $locationId)
    {
        return $this->where('location_id', $locationId)->first();
    }

    public function isEnabled(int $locationId)
    {
        return $this->where('location_id', $locationId)
            ->where('enabled', self::STATUS_ENABLED)
            ->exists();
    }

    public function findByPassword(int $locationId, string $password)
    {
        return self::where('location_id', $locationId)->where('zingle_password', $password)->first();
    }

    public function getContactCustomFieldsAttribute($value)
    {
        return json_decode($value, true);
    }
}
