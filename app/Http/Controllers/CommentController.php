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

    public function __construct()
    {
        $container = Container::getInstance();
        $this->commentService = $container->make(CommentInterface::class);
    }

    /**
     * @param Request $request
     * @throws \Exception
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