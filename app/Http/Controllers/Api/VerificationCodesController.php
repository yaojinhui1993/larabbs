<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Api\VerificationCodesRequest;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodesRequest $request)
    {
        if (! $captchaData = Cache::get($request->captcha_key)) {
            return $this->response->error('图片验证码已失效', 422);
        }
        if (! hash_equals((string)$captchaData['code'], (string)$request->captcha_code)) {
            Cache::forget($request->captcha_key);
            return $this->response->errorUnauthorized('验证码错误');
        }

        $phone = $captchaData['phone'];


        try {
            $code = $this->sendSms();
        } catch (NoGatewayAvailableException $e) {
            $message = $exception->getException('yunpian')->getMessage();

            return $this->response->errorInternal($message ?? '短信发送异常');
        }


        $key = 'verificationCode_' . str_random(15);
        $expireAt = now()->addMinutes(10);

        Cache::put($key, [
            'phone' => $phone,
            'code' => $code,
        ], $expireAt);

        return $this->response->array([
            'key' => $key,
            'expired_at' => $expireAt->toDateTimeString(),
        ])->setStatusCode(201);
    }

    protected function sendSms()
    {
        if (! app()->environment('production')) {
            return 1234;
        }

        $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);

        resolve('eaysms')->send($phone, [
            'content' => "【Lbbs社区】您的验证码是{$code}。如非本人操作，请忽略本短信"
        ]);

        return $code;
    }
}
