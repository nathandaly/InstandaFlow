<?php

namespace App\Http\Controllers;

use App\Contracts\JiraUser;
use App\Exceptions\SlackRequestException;
use App\Services\JiraUserService;
use App\Services\SlackMessageService;
use App\Services\SlackUsersService;
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

    /**
     * @var SlackUsersService
     */
    private $slackUserService;

    /**
     * @var SlackMessageService
     */
    private $slackMessageService;

    public function __construct()
    {
        $container = Container::getInstance();
        $this->jiraUserService = $container->make(JiraUserService::class);
        $this->slackUserService = $container->make(SlackUsersService::class);
        $this->slackMessageService = $container->make(SlackMessageService::class);
    }

    /**
     * @param Request $request
     * @throws \Exception
     */
    public function created(Request $request)
    {
        try {
            echo 'Comment::Created';
            $commentAuthorName = $request->input('comment.author.name');
            $commentAuthorEmail = $this->jiraUserService->getAuthorEmailFromUsername($commentAuthorName);
            $slackUserId = $this->slackUserService->lookupUserByEmail($commentAuthorEmail);
            $this->slackMessageService->postMessageToUser(
                $slackUserId,
                'Someone posted a commend on Jira dawg...'
            );
            exit;
        } catch (SlackRequestException $e) {
            // Email/ELK logging?
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