<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


//Auth Group
$router->group(['prefix' => 'api'], function () use ($router){
    // Auth Routes
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
    $router->put('reset_password/{id}', 'AuthController@reset');

    // Skills Routes
    $router->get('skills', 'SkillsController@index');
    $router->post('skills/create', 'SkillsController@create');
    $router->post('skills/{id}', 'SkillsController@update');
    $router->delete('skills/{id}', 'SkillsController@destroy');

    // Subskills Routes
    $router->get('subskills/', 'SubskillsController@index');
    $router->post('subskills/', 'SubskillsController@getBySkillId');
    $router->post('subskills/create', 'SubskillsController@create');
    $router->post('subskills/{id}', 'SubskillsController@update');
    $router->delete('subskills/{id}', 'SubskillsController@destroy');

    // Resource Routes
    $router->get('resource', 'ResourceController@index');
    $router->get('resource_all', 'ResourceController@all');
    $router->get('resource/getStats', 'ResourceController@getStats');
    $router->get('resource/{id}', 'ResourceController@show');
    $router->get('getResourceByToken/{token}', 'ResourceController@getResource');
    $router->post('resource/create', 'ResourceController@create');
    $router->post('resource/{id}', 'ResourceController@update');
    $router->post('resource/notes_skills/{id}', 'ResourceController@updateNotes_Skills');
    $router->delete('resource/{id}', 'ResourceController@destroy');
    $router->get('getCounts', 'ResourceController@getCounts');
    $router->get('getLastLogin', 'ResourceController@getLastLogin');

    // Resource Image Route
    $router->post('resource/profile_image/{id}','ResourceController@upload_image');

    // Invites
    $router->get('invites', 'ResourceController@getInvites');
    $router->post('invites/delete', 'ResourceController@deleteInvite');
    $router->post('invites/resend', 'ResourceController@resendInvite');

    // verify_resource Routes
    $router->get('resource/verify/{token}', 'ResourceController@invites');

    // availability_resource Routes
    $router->post('resource/availability/{id}', 'ResourceController@availability');
    $router->get('resource/availability/getLastUpdate/{id}', 'ResourceController@getLastUpdate__availability');

    // Certificate Routes
    $router->get('certificate/{id}', 'CertificateController@show');
    $router->post('certificate/create/{id}', 'CertificateController@create');
    $router->put('certificate/edit/{id}', 'CertificateController@update');
    $router->delete('certificate/delete/{id}', 'CertificateController@destroy');

    // Cohorts Routes
    $router->get('cohorts', 'CohortsController@index');
    $router->post('cohorts/create', 'CohortsController@create');
    $router->post('cohorts/{id}', 'CohortsController@update');
    $router->delete('cohorts/{id}', 'CohortsController@destroy');

    // Notification Routes
    $router->get('notification/all', 'NotificationController@all');
    $router->get('notification/skills', 'NotificationController@skills');
    $router->post('notification/create', 'NotificationController@create');
    $router->put('notification/{id}', 'NotificationController@update');
    $router->delete('notification/{id}', 'NotificationController@destroy');
    $router->post('notification_reply', 'NotificationController@notification_reply');
    $router->get('get_unseen', 'NotificationController@get_unseen');
    $router->get('get_unseen_notificaiton/{id}', 'NotificationController@get_unseen_notificaiton');
    $router->get('all_notification_reply', 'NotificationController@get_notification_reply');
    

    // Search Routes
    $router->post('/search', 'SearchController@filter');

    //Stats
    $router->get('/getCounts', 'ResourceController@getCounts');

});

$router->get('/', function () use ($router) {
    // return $router->app->version();
    return 'Welcome To Trainers Management :: Server';
});
