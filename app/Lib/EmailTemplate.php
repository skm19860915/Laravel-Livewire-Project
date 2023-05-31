<?php

namespace App\Lib;

class EmailTemplate
{
    public string $email;
    public string $name;
    public string $subject;
    public string $body;
    public string $date;
    public string $days;
    public string $treatment_type_id;
    public string $location_name;

    public function __construct()
    {
    }
}
