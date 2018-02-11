<?php

namespace App\Services;

use App\Contracts\CommentInterface;
use App\Contracts\JiraUserInterface;
use App\Contracts\SlackMessageInterface;
use App\Contracts\SlackUsersInterface;
use App\Contracts\SubscriberInterface;
use App\Helpers\JiraCommentHelper;
use App\Services\Jira\JiraUserService;
use App\Services\Slack\SlackMessageService;

/**
 * Class CommentService
 * @package App\Services
 */
class CommentService implements CommentInterface
{
    /**
     * @var JiraUserService
     */
    private $jiraUserService;

    private $subscriber;

    /**
     * @var SlackUsersInterface
     */
    private $slackUserService;

    /**
     * @var SlackMessageService
     */
    private $slackMessageService;

    public function __construct(
        JiraUserInterface $jiraUser,
        SlackUsersInterface $slackUser,
        SlackMessageInterface $slackMessage,
        SubscriberInterface $subscriber
    ) {
        $this->jiraUserService = $jiraUser;
        $this->slackUserService = $slackUser;
        $this->slackMessageService = $slackMessage;
        $this->subscriber = $subscriber;
    }

    /**
     * @param string $issueKey
     * @param string $issueType
     * @param string $issueSummary
     * @param string $commentId
     * @param string $commentBody
     * @param string|null $hook
     * @return void
     */
    public function procesJiraCommentAndSendSlackMessage(
        string $issueKey,
        string $issueType,
        string $issueSummary,
        string $commentId,
        string $commentBody,
        string $hook = null
    ) {
        if (!isset(
            $issueKey,
            $issueType,
            $issueSummary,
            $commentId,
            $commentBody
        )) {
            throw new \InvalidArgumentException();
        }

        if (
            JiraCommentHelper::containsMention($commentBody) &&
            ($mentionedUserKeys = JiraCommentHelper::extractUserKey($commentBody))
        ) {
            $mentions = [];

            foreach ($mentionedUserKeys as $mentionedUserKey) {
                if (isset($mentions[$mentionedUserKey[0]])) {
                    continue;
                }

                $mentionedUserEmail = $this->jiraUserService->getAuthorEmailFromUsername($mentionedUserKey[1]);
                $appSubsriberKeys = explode('::', $hook, 2);

                if (!$this->userAllowsNotifications($mentionedUserEmail, $appSubsriberKeys[0], $appSubsriberKeys[1])) {
                    continue;
                }

                $slackUserId = $this->slackUserService->lookupUserByEmail($mentionedUserEmail);
                $unsubscribeHash = base64_encode('{email: ' . $mentionedUserEmail . ', integration: ' . $appSubsriberKeys[0] . ', hook: ' . $appSubsriberKeys[0] . '}');
                $viewMessage = 'https://instanda.atlassian.net/browse/' . $issueKey . '?focusedCommentId=' . $commentId . '#comment-' . $commentId;

                $this->slackMessageService->postMessageToUser(
                    $slackUserId,
                    "You have been mentioned on `" . $issueType .
                    "` `" . $issueKey . " - " . $issueSummary . "`" .
                    "\r```" . $commentBody . "```",
                    [
                        'attachments' => [
                            [
                                'fallback' => 'Click here view ' . $viewMessage,
                                'actions' => [
                                    [
                                        'name' => 'view-comment',
                                        'type' => 'button',
                                        'text' => 'View',
                                        'fallback' => 'Click here view ' . $viewMessage,
                                        'url' => $viewMessage
                                    ],[
                                        'name' => 'unsubscribe',
                                        'type' => 'button',
                                        'text' => 'Unsubscribe',
                                        'style' => 'danger',
                                        'url' => 'http://localhost:8080/' . $unsubscribeHash . '/unsubscribe'
                                    ]
                                ]
                            ]
                        ]
                    ]
                );
                $mentions[$mentionedUserKey[0]] = $mentionedUserKey[1];
            }
        }
    }

    private function userAllowsNotifications(string $email, string $integration, string $hook): bool
    {
        return !$this->subscriber->hasUnsubscribed($email, $integration, $hook);
    }
}