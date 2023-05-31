<?php

declare(strict_types=1);

namespace App\Traits;

trait HelperTrait
{
    public function serverErrorMessage(): string
    {
        return 'An error occured, please try again or contact support if the problem persists.';
    }

    public function prettyPrintJson(array $array)
    {
        header('Content-Type: application/json');
        echo json_encode($array, JSON_PRETTY_PRINT);
    }

    public function removeSpecialChars(string $string): string
    {
        return trim(preg_replace('/[^a-zA-Z0-9]/s', '', $string));
    }
}
