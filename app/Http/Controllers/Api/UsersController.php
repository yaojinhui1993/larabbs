<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Transformers\UserTransformer;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Api\UserRequest;

class UsersController extends Controller
{
    public function store(UserRequest $request)
    {
        $verifyData = Cache::get($request->verification_key);

        if (! $verifyData) {
            return $this->response->error('验证码已失效', 422);
        }

        if (! hash_equals((string)$verifyData['code'], (string)$request->verification_code)) {
            return $this->response->errorUnauthorized('验证码错误');
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $verifyData['phone'],
            'password' => $request->password
        ]);

        Cache::forget($request->verification_key);

        return $this->response
            ->item($user, new UserTransformer())
            ->setStatusCode(201)
            ->setMeta([
                'access_token' =>  Auth::guard('api')->fromUser($user),
                'token_type' => 'Bearer',
                'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            ]);
    }

    public function me()
    {
        return $this->response->item($this->user(), new UserTransformer());
    }
}
