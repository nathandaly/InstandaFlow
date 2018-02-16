<?php

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/v1/jira', function() {
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

    // Remove "jira:" from the event hook name.
    $hookSubject = str_replace('jira:', '', $hookSubject);

    if (!class_exists('App\\Http\\Controllers\\' . ucfirst($hookSubject) . 'Controller')) {
        return Response(
            json_encode(['ok' => false, 'message' => ucfirst($hookSubject) . 'Controller not found.']),
            404
        );
    }

    // i.e. CommentController@create
    return app()->call(
        'App\\Http\\Controllers\\' . ucfirst($hookSubject) . 'Controller@' . $hookAction,
        [
            'hook' => 'JIRA::' . $json['webhookEvent']
        ]
    );
});