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

                $this->slackMessageService->postMessageToUser(
                    $slackUserId,
                    "You have been mentioned on `" . $issueType .
                    "` `" . $issueKey . " - " . $issueSummary . "`" .
                    "\r```" . $commentBody . "```",
                    $unsubscribeHash,
                    [
                        'attachments' => [
                            [
                                'fallback' => 'Click here to unsubscribe ' . getenv('APP_URL') . '/' . $unsubscribeToken . '/unsubscribe',
                                'actions' => [
                                    [
                                        'type' => 'button',
                                        'text' => 'View',
                                        'style' => 'default',
                                        'url' => 'https://instanda.atlassian.net/browse/' . $issueKey . '?focusedCommentId=' . $commentId . '#comment-' . $commentId
                                    ]
                                ]
                            ],[
                                'fallback' => 'Click here to unsubscribe  ' . getenv('APP_URL') . '/' . $unsubscribeToken . '/unsubscribe',
                                'actions' => [
                                    [
                                        'type' => 'button',
                                        'text' => 'Unsubscribe',
                                        'style' => 'danger',
                                        'url' => getenv('APP_URL') . '' . $unsubscribeToken . '/unsubscribe'
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