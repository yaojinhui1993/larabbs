<?php

namespace App\Http\Controllers\Api;

use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Api\CaptchaRequest;

class CaptchasController extends Controller
{
    public function store(CaptchaRequest $request, CaptchaBuilder $builder)
    {
        $key = 'captcha-'.str_random(15);
        $phone = $request->phone;

        $captcha = $builder->build();
        $expiredAt = now()->addMinutes(2);

        Cache::put($key, [
            'phone' => $phone,
            'code' => $captcha->getPhrase(),
        ], $expiredAt);

        return $this->response->array([
            'captcha_key' => $key,
            'expired_at' => $expiredAt,
            'captcha_image_content' => $captcha->inline(),
        ])->setStatusCode(201);
    }
}
