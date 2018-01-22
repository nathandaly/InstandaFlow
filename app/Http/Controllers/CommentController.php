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
            $commentAuthorName = $request->input('comment.author.displayName');
            $commentBody = $request->input('comment.body');
            $issueKey = $request->input('issue.key');
            $issueSummary = $request->input('issue.fields.summary');
            $issueType = $request->input('issue.fields.issuetype.name');

            $assigneeDisplayName = $request->input('issue.fields.assignee.key');
            $assigneeAuthorEmail = $this->jiraUserService->getAuthorEmailFromUsername($assigneeDisplayName);
            $slackUserId = $this->slackUserService->lookupUserByEmail($assigneeAuthorEmail);
            $this->slackMessageService->postMessageToUser(
                $slackUserId,
                '`' . $commentAuthorName  . ' just commented on ' . $issueType . ' `' . $issueKey . ' - ' . $issueSummary . '`. Click the link to view. https://instanda.atlassian.net/browse/' . $issueKey
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