<?php

namespace App\Http\Controllers;

use App\Contracts\SubscriberInterface;
use App\SubscriberRepository;
use Illuminate\Http\Request;

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

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe()
    {
        return response()->json(['ok' => true, 'message' => 'Successfully subscribed.']);
    }

    /**
     * @param Request $request
     * @param string $hash
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function unsubscribe(Request $request, string $hash)
    {
        $view = view('subscriber.unsubscribe');
        $saved = false;

        try {
            $decoded = base64_decode($hash);
            $variables = json_decode($decoded, true);

            if ($request->isMethod(Request::METHOD_POST)) {
                $saved = $this->subscriberRepository->unsubscribe(
                    $variables['email'],
                    $variables['integration'],
                    $variables['hook']
                );
            }
        } catch (\Exception $e) {
            return $view->with(['success' => $saved])->withErrors(
                $this->buildViewError([
                        'unsubscribe' => $e->getMessage(),
                    ],
                $e
                )
            );
        }

        return $view->with($variables)->with(['success' => $saved]);
    }
}