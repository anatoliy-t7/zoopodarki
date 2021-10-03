<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait DataFrom1с
{

    public function getUserData($phone)
    {

        $username = env('1C_USER', 'zoopodarki');
        $password = env('1C_PASSWORD', 'zoopodarki');
        $url      = env('1C_URL', 'http://185.77.242.198');

        $response = Http::withBasicAuth($username, $password)
            ->accept('application/json')
            ->retry(3, 100)
            ->get($url . '/rt_test/hs/rest/discount?tel=' . $phone);

        // Determine if the status code was >= 200 and < 300...
        if (!$response->successful()) {

            \Log::error($response->throw());

            return $response->throw();
        }

        if ($response->status() === 204) {

            return false;

        }

        return $response->json();

    }

}
