<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/generate', function() {
    ini_set('max_execution_time', 300);
    $url = Http::acceptJson()
        ->asJson()
        ->withToken(config('services.replicate.token'), 'Token')
        ->post('https://api.replicate.com/v1/predictions', [
        'version' => '6af8583c541261472e92155d87bba80d5ad98461665802f2ba196ac099aaedc9',
        'input' => [
            'image' => 'https://replicate.delivery/pbxt/KIIutO7jIleskKaWebhvurgBUlHR6M6KN7KHaMMWSt4OnVrF/musk_resize.jpeg',
            'width' => 640,
            'height' => 640,
            'prompt' => 'analog film photo of a man. faded film, desaturated, 35mm photo, grainy, vignette, vintage, Kodachrome, Lomography, stained, highly detailed, found footage, masterpiece, best quality',
            'scheduler' => 'EulerDiscreteScheduler',
            'enable_lcm' => false,
            'pose_image' => 'https://replicate.delivery/pbxt/KJmFdQRQVDXGDVdVXftLvFrrvgOPXXRXbzIVEyExPYYOFPyF/80048a6e6586759dbcb529e74a9042ca.jpeg',
            'sdxl_weights' => 'protovision-xl-high-fidel',
            'pose_strength' => 0.4,
            'canny_strength' => 0.3,
            'depth_strength' => 0.5,
            'guidance_scale' => 5,
            'negative_prompt' => '(lowres, low quality, worst quality:1.2), (text:1.2), watermark, painting, drawing, illustration, glitch, deformed, mutated, cross-eyed, ugly, disfigured (lowres, low quality, worst quality:1.2), (text:1.2), watermark, painting, drawing, illustration, glitch,deformed, mutated, cross-eyed, ugly, disfigured',
            'ip_adapter_scale' => 0.8,
            'lcm_guidance_scale' => 1.5,
            'num_inference_steps' => 30,
            'enable_pose_controlnet' => true,
            'enhance_nonface_region' => true,
            'enable_canny_controlnet' => false,
            'enable_depth_controlnet' => false,
            'lcm_num_inference_steps' => 5,
            'controlnet_conditioning_scale' => 0.8,
        ],
    ])->throw()->json('urls.get');


    do {
        sleep(1);
        $response = Http::acceptJson()
            ->timeout(60)
            ->asJson()
            ->withToken(config('services.replicate.token'), 'Token')
            ->get($url)->throw()->json();
        dump($response);
        logger()->info('test', $response);
    } while ($response['status'] === 'starting' || $response['status'] === 'processing');
    dd($response);;

    dd($results);
});
