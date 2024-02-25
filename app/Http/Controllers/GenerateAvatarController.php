<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GenerateAvatarController
{
    public function __invoke(Request $request)
    {
        $response = Http::acceptJson()
            ->withToken(config('services.replicate.token'), 'Token')
            ->post('https://api.replicate.com/v1/predictions', [
                'version' => '6af8583c541261472e92155d87bba80d5ad98461665802f2ba196ac099aaedc9',
                'input' => [
                    'image' => $request->get('img'),
                    'width' => 640,
                    'height' => 640,
                    'prompt' => 'sticker of a man with beard and futuristic cyberpunk glasses, outlined by a thick white line. Cyberpunk style, vivid colors, futuristic. Cartoon style',
                    'negative_prompt' => '(lowres, low quality, worst quality:1.2), (text:1.2), watermark, deformed, mutated, cross-eyed, ugly, disfigured (lowres, low quality, worst quality:1.2), (text:1.2), watermark',
                    'scheduler' => 'EulerDiscreteScheduler',
                    'enable_lcm' => false,
                    'sdxl_weights' => 'protovision-xl-high-fidel',
                    'pose_strength' => 0.4,
                    'canny_strength' => 0.3,
                    'depth_strength' => 0.5,
                    'guidance_scale' => 5,
                    'ip_adapter_scale' => 0.8,
                    'lcm_guidance_scale' => 1.5,
                    'num_inference_steps' => 30,
                    'enable_pose_controlnet' => true,
                    'enhance_nonface_region' => true,
                    'enable_canny_controlnet' => false,
                    'enable_depth_controlnet' => false,
                    'lcm_num_inference_steps' => 5,
                    'controlnet_conditioning_scale' => 0.8
                ]
            ])
            ->throw()
            ->json('urls.get');

        return new JsonResponse([
            'generation' => Str::of($response)->explode('/')->last()
        ]);
    }
}
