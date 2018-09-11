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
    $api->post('verificationCodes', 'VerificationCodesController@store')
        ->name('api.verificationCodes.store');
});
