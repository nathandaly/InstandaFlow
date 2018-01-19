<?php

namespace App\Http\Controllers;

use App\Contracts\JiraUser;
use App\Services\JiraUserService;
use Illuminate\Container\Container;
use Illuminate\Http\Request;

/**
 * Class CommentController
 * @package App\Http\Controllers
 */
class CommentController extends Controller
{
    /**
     * @var JiraUserService
     */
    private $jiraUserService;

    public function __construct()
    {
        $container = Container::getInstance();
        $this->jiraUserService = $container->make(JiraUserService::class);
    }

    public function created(Request $request)
    {
        echo 'Comment::Created';
        $authorName = $request->input('comment.author.name');
        $this->jiraUserService->getAuthorEmailFromUsername($authorName);
        echo $authorName; exit;
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