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
    'middleware' => [ 'serializer:array', 'bindings']
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

        // auth
        $api->group(['middleware' => 'api.auth'], function ($api) {
            $api->get('user', 'UsersController@me')
                ->name('user.show');
            $api->patch('user', 'UsersController@update')->name('user.update');

            $api->post('images', 'ImagesController@store')->name('images.store');

            $api->post('topics', 'TopicsController@store')->name('topics.store');
            $api->patch('topics/{topic}', 'TopicsController@update')->name('topics.update');
            $api->delete('topics/{topic}', 'TopicsController@destroy')->name('topics.destroy');

            $api->post('topics/{topic}/replies', 'RepliesController@store')->name('topics.replies.index');
            $api->delete('topics/{topic}/replies/{reply}', 'RepliesController@destroy')->name('topics.replies.destroy');

            $api->get('user/notifications', 'NotificationsController@index')->name('user.notifications.index');
            $api->get('user/notifications/stat', 'NotificationsController@stat')->name('user.notifications.stat');
            $api->patch('user/notifications', 'NotificationsController@read')->name('user.notifications.read');

            $api->get('user/permissions', 'PermissionsController@index')->name('user.permissions.index');
        });

        // guest
        $api->group([], function ($api) {
            $api->get('categories', 'CategoriesController@index')->name('categories.index');

            $api->get('topics', 'TopicsController@index')->name('topics.index');
            $api->get('users/{user}/topics', 'TopicsController@userIndex')->name('users.topics.index');
            $api->get('topics/{topic}', 'TopicsController@show')->name('topics.show');
            $api->get('/topics/{topic}/replies', 'RepliesController@index')->name('topics.replies.index');
            $api->get('/users/{user}/replies', 'RepliesController@userIndex')->name('users.replies.index');

            $api->get('/links', 'LinksController@index')->name('links.index');

            $api->get('/active/users', 'UsersController@activeIndex')->name('active.users.index');
        });
    });
});
