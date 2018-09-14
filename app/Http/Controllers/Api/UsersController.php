<?php

namespace App\Http\Controllers\Api;

use EasyWeChat;
use App\Models\User;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use App\Transformers\UserTransformer;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Api\UserRequest;

class UsersController extends Controller
{
    public function update(UserRequest $request)
    {
        $user = $this->user();

        $attributes = $request->only(['name', 'email', 'introduction']);

        if ($request->avatar_image_id) {
            $image = Image::find($request->avatar_image_id);

            $attributes['avatar'] = $image->path;
        }

        $user->update($attributes);

        return $this->response->item($user, new UserTransformer());
    }

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

    public function weappStore(UserRequest $request)
    {
        $verifyData = Cache::get($request->verification_key);

        if (! $verifyData) {
            return $this->response->error('验证码已失效', 422);
        }

        if (! hash_equals((string)$verifyData['code'], (string)$request->verification_code)) {
            return $this->response->errorUnauthorized('验证码错误');
        }

        $data = EasyWeChat::miniProgram()->auth->session($request->code);

        if (isset($data['errcode'])) {
            return $this->response->errorUnauthorized('code 不正确');
        }

        if (User::where('weapp_openid', $data['openid'])->exists()) {
            return $this->response->errorForbidden('微信已绑定其他用户，请直接登录');
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $verifyData['phone'],
            'password' => bcrypt($request->password),
            'weapp_openid' => $data['openid'],
            'weixin_session_key' => $data['session_key'],
        ]);

        Cache::forget($request->verification_key);

        return $this->response->item($user, new UserTransformer)
            ->setMeta([
                'access_token' => \Auth::guard('api')->fromUser($user),
                'token_type' => 'Bearer',
                'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
            ])
            ->setStatusCode(201);
    }

    public function me()
    {
        return $this->response->item($this->user(), new UserTransformer());
    }

    public function activeIndex(User $user)
    {
        $users = $user->getActiveUsers();

        return $this->response->collection($users, new UserTransformer);
    }
}
