<?php

namespace App\Services;

use Throwable;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class ApiService
{
    public function post(string $url, array $body, array $headers): array
    {

        $response = Http::withHeaders($headers)->retry(5, 3000);

        try {

            $response = $response->post($url, $body);

            return $response->json();
        } catch (Throwable $e) {
            if ($e->getCode() !== 404) {
                logger()->error($e->getMessage());
            }

            return ['apiError' => $e->getMessage()];
        }
    }

    public function postAsForm(string $url, array $body, array $headers): array
    {
        $response = Http::withHeaders($headers)->retry(5, 3000);

        try {
            $response = $response->asForm()->post($url, $body);

            return $response->json();
        } catch (Throwable $e) {
            if ($e->getCode() !== 404) {
                logger()->error($e->getMessage());
            }

            return ['apiError' => $e->getMessage()];
        }
    }

    public function get(string $url, array $queryParams, array $headers): array
    {
        $response = Http::withHeaders($headers)->retry(5, 3000);

        try {
            if ($queryParams) {
                $response = $response->get($url, $queryParams);
            } else {
                $response = $response->get($url);
            }

            return $response->json();
        } catch (Throwable $e) {
            if ($e->getCode() !== 404) {
                logger()->error($e->getMessage());
            }

            return ['apiError' => $e->getMessage()];
        }
    }

    public function put(string $url, array $body, array $headers): array
    {
        $response = Http::withHeaders($headers)->retry(5, 3000);

        try {
            $response = $response->put($url, $body);

            return $response->json();
        } catch (RequestException $e) {
            if ($e->getCode() !== 404) {
                logger()->error($e->getMessage());
            }

            return $e->response->json();
        }
    }

    public function delete(string $url, array $headers): array
    {
        $response = Http::withHeaders($headers)->retry(5, 3000);

        try {
            $response = $response->delete($url);

            return $response->json();
        } catch (Throwable $e) {
            if ($e->getCode() !== 404) {
                logger()->error($e->getMessage());
            }

            return ['apiError' => $e->getMessage()];
        }
    }
}
