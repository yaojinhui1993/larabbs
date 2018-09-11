<?php

use Dingo\Api\Routing\Router;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = resolve(Router::class);

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api'
], function ($api) {
    $api->group([
        'name' => 'api.',
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ], function ($api) {
        $api->post('verificationCodes', 'VerificationCodesController@store')
            ->name('api.verificationCodes.store');

        $api->post('users', 'UsersController@store')
            ->name('users.store');

        $api->post('captchas', 'CaptchasController@store')
            ->name('captchas.store');

        $api->post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')
            ->name('socials.authorizations.store');
    });
});
