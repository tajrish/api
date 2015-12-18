<?php

/** @var \Dingo\Api\Routing\Router $api */
$api = app(Dingo\Api\Routing\Router::class);
$api->version('v1', ['namespace' => 'Tajrish\Http\Controllers\V1'],function ($api) {

    $api->post('auth/register', [
        'uses' => 'AuthController@postRegister',
        'as' => 'auth.register'
    ]);

    $api->get('users/{user_token}/provinces', [
        'uses' => 'UserController@provinces',
        'as' => 'users.provinces'
    ]);

    $api->get('users/{user_token}/provinces/{province_id}', [
        'uses' => 'UserController@getProvinceStatus',
    ]);

    $api->post('users/{user_token}/provinces/{province_id}', [
        'uses' => 'UserController@postStartProvince'
    ]);

    $api->post('users/{user_token}/provinces/{province_id}/finish', [
       'uses' => 'UserController@postFinishProvince'
    ]);

    $api->get('users/{user_token}/pin/{pin_id}', [
        'uses' => 'UserController@getPinStatus',
        'as' => 'users.get.pin_status'
    ]);

    $api->post('users/{user_token}/pin/{pin_id}', [
        'uses' => 'UserController@postCheckin'
    ]);

    $api->post('auth/login', [
        'uses' => 'AuthController@postLogin',
        'as' => 'auth.login'
    ]);

    $api->any('protected', [
        'uses' => function () {
            return 'protected';
        },
        'middleware' => ['auth']
    ]);

});

