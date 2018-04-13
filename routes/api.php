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

Route::post('/v1/jira', function() {
    $jsonResult = json_decode(file_get_contents('php://input'), true);

    list($hookSubject, $hookAction) = recordEventInPublic(
        '/public/events/jira',
        $jsonResult,
        'webhookEvent',
        '_',
        0,
        1
    );

    // Remove "jira:" from the event hook name.
    $hookSubject = str_replace('jira:', '', $hookSubject);

    if ($reponse = notFound($hookSubject)) {
      return $reponse;
    }

    return redirectToControllerAction($hookSubject, $hookAction, $jsonResult['webhookEvent']);
});

Route::post('/v1/vsts', function() {
    $jsonResult = json_decode(file_get_contents('php://input'), true);

    list($hookSubject, $hookAction) = recordEventInPublic(
        '/public/events/vsts',
        $jsonResult,
        'eventType',
        '.',
        1,
        2
    );

    if ($reponse = notFound($hookSubject)) {
      return $reponse;
    }

    return redirectToControllerAction($hookSubject, $hookAction, $jsonResult['eventType']);
});

function recordEventInPublic(
    string $dirString,
    array $jsonResult,
    string $webHookName,
    string $seperator,
    int $subjectPos,
    int $actionPos
  ) : array {
    $dir = base_path() . $dirString;

    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }

    $json = json_decode(file_get_contents('php://input'), true);    
    $hook = explode($seperator, $json[$webHookName], $actionPos + 1);
    $hookSubject = $hook[$subjectPos];
    $hookAction = $hook[$actionPos];

    if (!file_exists($dir . '/' . $hookSubject)) {
        mkdir($dir . '/' . $hookSubject, 0777);
    }

    if (!file_exists($dir . '/' . $hookSubject . '/index.php')) {
        copy($dir . '/../index.php', $dir . '/' . $hookSubject . '/index.php');
    }

    file_put_contents(
        $dir . '/' . $hookSubject . '/' . $json[$webHookName] . '-' . (new DateTime())->format('Y-m-d_H:i:s') . '.json',
        json_encode($json, JSON_PRETTY_PRINT)
    );

    return [$hookSubject, $hookAction];
}

function notFound(string $hookSubject) {
    if (!class_exists('App\\Http\\Controllers\\' . ucfirst($hookSubject) . 'Controller')) {
        return Response(
            json_encode(['ok' => false, 'message' => ucfirst($hookSubject) . 'Controller not found.']),
            404
        );
    }

    return false;
}

function redirectToControllerAction(string $hookSubject, string $hookAction, string $event)
{
    // i.e. CommentController@create
    return app()->call(
        'App\\Http\\Controllers\\' . ucfirst($hookSubject) . 'Controller@' . $hookAction,
        [
            'hook' => 'VSTS::' . $event
        ]
    );
}