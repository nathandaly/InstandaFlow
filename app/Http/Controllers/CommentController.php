<?php

namespace App\Http\Controllers;

use App\Contracts\CommentInterface;
use App\Services\CommentService;
use Illuminate\Container\Container;
use Illuminate\Http\Request;

/**
 * Class CommentController
 * @package App\Http\Controllers
 */
class CommentController extends Controller
{
    /**
     * @var CommentService
     */
    private $commentService;

    /**
     * CommentController constructor.
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
    public function created(Request $request)
    {
        try {
            $this->commentService->procesJiraCommentAndSendSlackMessage(
                $request->input('issue.key'),
                $request->input('issue.fields.issuetype.name'),
                $request->input('issue.fields.summary'),
                $request->input('comment.id'),
                $request->input('comment.body')
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
            // Email/ELK logging?
        }
    }

    public function updated()
    {

    }

    public function deleted()
    {

    }

    public function worklogChanged()
    {

    }
}