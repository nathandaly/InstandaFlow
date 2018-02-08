<?php

namespace App\Services;

use App\Contracts\CommentInterface;
use App\Contracts\JiraUserInterface;
use App\Contracts\SlackMessageInterface;
use App\Contracts\SlackUsersInterface;
use App\Services\Jira\JiraUserService;
use App\Services\Slack\SlackMessageService;

class CommentService implements CommentInterface
{
    /**
     * @var JiraUserService
     */
    private $jiraUserService;

    /**
     * @var SlackUsersInterface
     */
    private $slackUserService;

    /**
     * @var SlackMessageService
     */
    private $slackMessageService;

    public function __construct()
    {
        $container = Container::getInstance();
        $this->jiraUserService = $container->make(JiraUserInterface::class);
        $this->slackUserService = $container->make(SlackUsersInterface::class);
        $this->slackMessageService = $container->make(SlackMessageInterface::class);
    }

    /**#
     * @param $issueKey
     * @param $issueType
     * @param $issueSummary
     * @param $commentId
     * @param $commentBody
     */
    public function procesJiraCommentAndSendSlackMessage(
        $issueKey,
        $issueType,
        $issueSummary,
        $commentId,
        $commentBody
    ): void
    {
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
                $slackUserId = $this->slackUserService->lookupUserByEmail($mentionedUserEmail);
                $this->slackMessageService->postMessageToUser(
                    $slackUserId,
                    "You have been mentioned on `" . $issueType .
                    "` `" . $issueKey . " - " . $issueSummary . "`" .
                    "\r```" . $commentBody . "```" .
                    "\rClick the link to view. https://instanda.atlassian.net/browse/" . $issueKey . "?focusedCommentId=" . $commentId . "#comment-" . $commentId
                );
                $mentions[$mentionedUserKey[0]] = $mentionedUserKey[1];
            }
        }
    }
}