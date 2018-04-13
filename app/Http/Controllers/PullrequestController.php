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
        try {
            $this->commentService->procesJiraCommentAndSendSlackMessage(
                $request->input('issue.key'),
                $request->input('issue.fields.issuetype.name'),
                $request->input('issue.fields.summary'),
                $request->input('comment.id'),
                $request->input('comment.body'),
                $hook
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'ok' => false,
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ],
                500
            );
            // Email/ELK logging?
        }

        return response()->json(['ok' => true], 200);
    }

    public function updated()
    {
        echo 'YO'; exit;
    }

    public function deleted()
    {

    }

    public function worklogChanged()
    {

    }
}