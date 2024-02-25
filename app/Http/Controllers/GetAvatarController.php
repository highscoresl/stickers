<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class GetAvatarController
{
    public function __invoke(string $generation)
    {
        $response = Http::acceptJson()
            ->timeout(60)
            ->asJson()
            ->withToken(config('services.replicate.token'), 'Token')
            ->get("https://api.replicate.com/v1/predictions/$generation")->throw()->json();


        if ($response['status'] !== 'succeeded') {
            return ['status' => 'We are still processing the image'];
        }

        return ['image' => $response['output']];
    }
}
