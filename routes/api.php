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
    'namespace' => 'App\Http\Controllers\Api',
    'middleware' => 'serializer:array'
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
        $api->post('authorizations', 'AuthorizationsController@store')
            ->name('authorizations.store');
        $api->put('authorizations/current', 'AuthorizationsController@update')
            ->name('authorizations.update');
        $api->delete('authorizations/current', 'AuthorizationsController@destroy')
            ->name('authorizations.destroy');

        $api->group(['middleware' => 'api.auth'], function ($api) {
            $api->get('user', 'UsersController@me')
                ->name('user.show');
            $api->patch('user', 'UsersController@update')->name('user.update');

            $api->post('images', 'ImagesController@store')->name('images.store');
        });

        $api->group([], function ($api) {
            $api->get('categories', 'CategoriesController@index')->name('categories.index');
        });
    });
});
