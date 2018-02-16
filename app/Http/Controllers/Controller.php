<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\MessageBag;

/**
 * Class Controller
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param string|array $error
     * [
     *     'someKey1' => 'Some error message one.',
     *     'someKey2' => 'Some error message two.',
     * ]
     * @param \Exception|null $exception
     * @throws \Exception
     * @return MessageBag
     */
    protected function buildViewError($error, \Exception $exception = null): MessageBag
    {
        $messageBag = new MessageBag();

        foreach ($error as $key => $message) {
            if (null != $exception) {
                is_array($message) ?: $message = [
                    'message' => $message
                ];

                isset($message['meta']) ?: $message['meta'] = [
                    'exceptionType' => get_class($exception),
                    'exceptionMessage' => $exception->getMessage(),
                    'exceptionCode' => $exception->getCode(),
                    'exceptionFile' => $exception->getFile(),
                    'exceptionLine' => $exception->getLine()
                ];
            }

            $messageBag->add($key, $message);
        }

        return $messageBag;
    }
}
