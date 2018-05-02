<?php

namespace App\Http\Controllers;

use App\Contracts\CommentInterface;
use App\Services\CommentService;
use Illuminate\Http\Request;

/**
 * Class PullrequestController
 * @package App\Http\Controllers
 */
class PullrequestController extends Controller
{
    /**
     * @var CommentService
     */
    private $commentService;

    /**
     * PullrequestController constructor.
     * @param CommentInterface $comment
     */
    public function __construct(CommentInterface $comment)
    {
        $this->commentService = $comment;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function created(Request $request, $hook)
    {
        return response()->json(['ok' => true], 200);
    }

    public function updated()
    {

        return response()->json(['ok' => true], 200);
    }

    public function deleted()
    {

    }

    public function worklogChanged()
    {

    }
}