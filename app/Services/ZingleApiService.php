<?php

namespace App\Services;

use App\Models\Schedule;
use App\Models\Ticket;
use App\Traits\HelperTrait;
use Illuminate\Support\Str;
use App\Services\ApiService;
use App\Models\ZingleIntegration;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\{Http};
use Illuminate\Contracts\Encryption\DecryptException;


class ZingleApiService
{
    use HelperTrait;

    protected $apiService;

    protected $model;
    protected $zingleCredentials;

    protected $headers;

    protected $baseUrl = 'https://api.zingle.me/v1';

    public function __construct()
    {
        $this->model = new ZingleIntegration();
        $this->apiService = new ApiService();
    }

    public function zingleCredentials($locationId)
    {
        return $this->model->getCredentials((int) $locationId);
    }

    public function getHeaders($locationId)
    {
        $password = '';

        try {
            $password = Crypt::decryptString($this->zingleCredentials($locationId)->zingle_password);
        } catch (DecryptException $e) {
            logger()->error($e->getMessage());

            $password = Crypt::encryptString($this->zingleCredentials($locationId)->zingle_password);

            $integration = $this->model->findByPassword(
                (int) $locationId,
                $this->zingleCredentials($locationId)->zingle_password
            );
            if ($integration) {
                $integration->zingle_password = $password;
                $integration->save();
            }
        }

        $password = Crypt::decryptString($this->zingleCredentials($locationId)->zingle_password);

        $userName = $this->zingleCredentials($locationId)->zingle_username;

        $token = base64_encode("{$userName}:{$password}");

        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => "Basic {$token}"
        ];
    }

    //https://github.com/Zingle/rest-api-documentation/blob/master/services/GET_list.md
    public function getServiceDetails($locationId)
    {
        $apiUrl = "{$this->baseUrl}/services";

        $serviceDetails = $this->apiService->get($apiUrl, [], $this->getHeaders($locationId));

        return $serviceDetails;
    }

    public function getAllContacts($locationId, int $page, int $pageSize)
    {
        $apiUrl = "{$this->baseUrl}/services/{$this->zingleCredentials($locationId)->service_id}/contacts/?page={$page}&page_size={$pageSize}";

        $contacts = $this->apiService->get($apiUrl, [], $this->getHeaders($locationId));

        return $contacts;
    }

    public function sendMessage($locationId, $message, $recipientData, $otherData): void
    {
        $zingleCredentials = $this->zingleCredentials($locationId);

        $apiUrl = "{$this->baseUrl}/services/{$zingleCredentials->service_id}/messages";

        $recipientPhoneNumber = $this->removeSpecialChars($recipientData->cell_phone);

        $body = [
            "sender_type" => "service",
            "sender" => [
                "id" => $zingleCredentials->service_id,
                "channel_value" => $zingleCredentials->zingle_phone_number
            ],
            "recipient_type" => "contact",
            "recipients" => [
                ["channel_value" => "+1{$recipientPhoneNumber}"]
            ],
            "channel_type_ids" => [$zingleCredentials->channel_type_id],
            "body" => $message,
            "uuid" => Str::uuid()
        ];

        $this->apiService->post($apiUrl, $body, $this->getHeaders($locationId));
    }

    public function updateContact($locationId, $zingleContactId, $data)
    {
        $zingleCredentials = $this->zingleCredentials($locationId);

        $apiUrl = "{$this->baseUrl}/services/{$zingleCredentials->service_id}/contacts/{$zingleContactId}";

        //if the zingle appointment is cancelled, we need to clear out the appointment info so the patient doesn't get confusing follow-up texts.
        if (isset($data['schedule_type_id']) && $data['schedule_type_id'] !== 4) {
            $appDate = strtotime($data['date']);
            $appTime = date("H:i", strtotime($data['time']));
        } else {
            $appDate = '';
            $appTime = '';
        }

        //test this on Montgomery before we roll it out to everyone.
        if ($locationId == 32 || $locationId == 33 || $locationId == 1 || $locationId == 31 || $locationId == 34) {
            $body = $this->createRequestBody($zingleCredentials, $data);
        } else {
            $body = [
                "custom_field_values" => [
                    [
                        "custom_field_id" => "{$zingleCredentials->appt_date_custom_field_id}",
                        "value" => "{$appDate}"
                    ],
                    [
                        "custom_field_id" => "{$zingleCredentials->appt_time_custom_field_id}",
                        "value" => "{$appTime}"
                    ]
                ]
            ];
        }

        $response = $this->apiService->put($apiUrl, $body, $this->getHeaders($locationId));
        return $this->parseZingleResponse($response);
    }

    public function createRequestBody($zingleCredentials, $data)
    {
        $body = [
            'custom_field_values' => []
        ];

        $customFields = $zingleCredentials->contact_custom_fields; //will eventually switch from specific fields to using this one field for all the various data points.

        foreach ($data->toArray() as $key => $value) {
            if ($data instanceof Schedule) {
                if ($key == 'date') {
                    $value = strtotime($value);
                    array_push($body['custom_field_values'], [
                        "custom_field_id" => "{$zingleCredentials->appt_date_custom_field_id}",
                        "value" => "{$value}"
                    ]);
                }

                if ($key == 'time') {
                    $value = date("H:i", strtotime($value));
                    array_push($body['custom_field_values'], [
                        "custom_field_id" => "{$zingleCredentials->appt_time_custom_field_id}",
                        "value" => "{$value}"
                    ]);
                }
            }

            if (!($data instanceof Schedule)) {

                $match = array_search($key, array_column($customFields, 'code'));

                if ($match) {

                    if ($customFields[$match]['data_type'] == 'date') {
                        $value = strtotime($value);
                    }

                    array_push($body['custom_field_values'], [
                        "custom_field_id" => "{$customFields[$match]['id']}",
                        "value" => "{$value}"
                    ]);
                }
            }
        }

        return $body;
    }

    public function createContact($locationId, $patient, $data)
    {
        $zingleCredentials = $this->zingleCredentials($locationId);

        $apiUrl = "{$this->baseUrl}/services/{$zingleCredentials->service_id}/contacts";

        $appDate = strtotime($data['date']);
        $appTime = date("H:i", strtotime($data['time']));

        $phoneNumber = $this->removeSpecialChars($patient->cell_phone);

        $body = [
            "channels" => [
                [
                    "channel_type_id" => $zingleCredentials->channel_type_id,
                    "value" => "+1{$phoneNumber}"
                ]
            ],
            "custom_field_values" => [
                [
                    "custom_field_id" => "{$zingleCredentials->first_name_custom_field_id}",
                    "value" => "{$patient->first_name}"
                ],
                [
                    "custom_field_id" => "{$zingleCredentials->last_name_custom_field_id}",
                    "value" => "{$patient->last_name}"
                ],
                [
                    "custom_field_id" => "{$zingleCredentials->appt_date_custom_field_id}",
                    "value" => "{$appDate}"
                ],
                [
                    "custom_field_id" => "{$zingleCredentials->appt_time_custom_field_id}",
                    "value" => "{$appTime}"
                ]
            ]
        ];

        $response = $this->apiService->post($apiUrl, $body, $this->getHeaders($locationId));
        return $this->parseZingleResponse($response);
    }

    public function parseZingleResponse(array $response): array
    {
        if (isset($response['status']['status_code']) && $response['status']['status_code'] === 200) {
            return $response;
        }

        if (isset($response['apiError']) && Str::contains($response['apiError'], 'error_code')) {
            $pos = strpos($response['apiError'], ':');
            $errorObject = trim(substr($response['apiError'], ($pos + 1)));
            $arr = json_decode($errorObject, true);
            $error = array();
            if (isset($arr['status']['error_code'])) {
                $error['zingle_error_code'] = $arr['status']['error_code'];
            }

            if (isset($arr['status']['description'])) {
                $error['zingle_error_message'] = $arr['status']['description'];
            }
            return $error;
        }

        return $response;
    }


    public function getTagList($locationId)
    {
        $zingleCredential = $this->zingleCredentials($locationId);
        $apiUrl = "{$this->baseUrl}/services/{$zingleCredential->service_id}/contact-labels";

        $tagList = $this->apiService->get($apiUrl, [], $this->getHeaders($locationId));
        return $tagList;
    }

    public function getContactCustomFields($locationId)
    {
        $zingleCredential = $this->zingleCredentials($locationId);
        $apiUrl = "{$this->baseUrl}/services/{$zingleCredential->service_id}/contact-custom-fields";

        $fieldsList = $this->apiService->get($apiUrl, [], $this->getHeaders($locationId));
        return json_encode($fieldsList['result']);
    }


    public function applyTag($locationId, $contactId, $tagId)
    {
        $zingleCredential = $this->zingleCredentials($locationId);

        $apiUrl = "{$this->baseUrl}/services/{$zingleCredential->service_id}/contacts/{$contactId}/labels/{$tagId}";

        return $this->apiService->postAsForm($apiUrl, [], $this->getHeaders($locationId));
    }

    public function removeTag($locationId, $contactId, $tagId)
    {
        $zingleCredential = $this->zingleCredentials($locationId);

        $apiUrl = "{$this->baseUrl}/services/{$zingleCredential->service_id}/contacts/{$contactId}/labels/{$tagId}";

        return $this->apiService->delete($apiUrl, $this->getHeaders($locationId));
    }
}
