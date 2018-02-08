<?php

namespace App\Contracts;

/**
 * Interface CommentInterface
 * @package App\Contracts
 */
interface CommentInterface
{
    /**
     * @param string $issueKey
     * @param string $issueType
     * @param string $issueSummary
     * @param string $commentId
     * @param string $commentBody
     */
    public function procesJiraCommentAndSendSlackMessage(
        string $issueKey,
        string $issueType,
        string $issueSummary,
        string $commentId,
        string $commentBody
    );
}