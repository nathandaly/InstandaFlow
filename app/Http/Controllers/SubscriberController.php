<?php

namespace App\Http\Controllers;

use App\Contracts\SubscriberInterface;
use App\SubscriberRepository;

/**
 * Class SubscriberController
 * @package App\Http\Controllers
 */
class SubscriberController extends Controller
{
    /**
     * @var SubscriberRepository
     */
    private $subscriberRepository;

    /**
     * SubscriberController constructor.
     * @param SubscriberInterface $subscriber
     */
    public function __construct(SubscriberInterface $subscriber)
    {
        $this->subscriberRepository = $subscriber;
    }

    public function subscribe()
    {
        return response()->json(['ok' => true, 'message' => 'Successfully subscribed.']);
    }

    public function unsubscribe(string $hash)
    {
        try {
            $responseMessage = [
                'ok' => true,
                'message' => 'Successfully unsubscribed.'
            ];
            $decoded = base64_decode($hash);
            $json = json_decode($decoded, true);

            $this->subscriberRepository->unsubscribe(
                $json['email'],
                $json['integration'],
                $json['hook']
            );
        } catch (\Exception $e) {
            $responseMessage =  ['ok' => false, 'message' => $e->getMessage()];
        }

        return response()->json($responseMessage);
    }
}