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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/', function () use ($router) {
    $dir = base_path() . '/public/events';
    $json = json_decode(file_get_contents('php://input'), true);
    $hook = explode('_', $json['webhookEvent'], 2);
    $hookSubject = $hook[0];
    $hookAction = $hook[1];

    if (!file_exists($dir . '/' . $hookSubject)) {
        mkdir($dir . '/' . $hookSubject, 0777);
        copy($dir . '/index.php', $dir . '/' . $hookSubject . '/index.php');
    }

    file_put_contents(
        $dir . '/' . $hookSubject . '/' . $json['webhookEvent'] . '-' . (new DateTime())->format('Y-m-d_H:i:s') . '.json',
        json_encode($json, JSON_PRETTY_PRINT)
    );

    // i.e. CommentController@create
    return $router->app->call(
        'App\\Http\\Controllers\\' . ucfirst($hookSubject) . 'Controller@' . $hookAction,
        explode('/', '')
    );
});
