<?php

namespace App\Http\Controllers;

/**
 * Class SubscriberController
 * @package App\Http\Controllers
 */
class SubscriberController extends Controller
{
    public function subscribe()
    {
        return response()->json(['ok' => true, 'message' => 'Successfully subscribed.']);
    }

    public function unsubscribe($hash)
    {

        echo $hash; exit;
        return response()->json(['ok' => true, 'message' => 'Successfully unsubscribed.']);
    }
}