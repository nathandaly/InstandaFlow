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
            $issueKey = $request->input('issue.key');
            $issueSummary = $request->input('issue.fields.summary');
            $issueType = $request->input('issue.fields.issuetype.name');
            $commentId = $request->input('comment.id');
            $commentBody = $request->input('comment.body');

            if ($this->containsMention($commentBody) && ($mentionedUserKeys = $this->extractUserKey($commentBody))) {
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

    /**
     * @param $bodyText
     * @return bool
     */
    private function containsMention($bodyText)
    {
        if (strpos($bodyText, '[~') !== false) {
            return true;
        }

        return false;
    }

    /**
     * @param $bodyText
     * @return null
     */
    private function extractUserKey($bodyText)
    {
        if (preg_match_all(
            '/\[~(.*?)\]/',
            $bodyText, $matches,
            PREG_SET_ORDER,
            0)) {
            return $matches;
        }

        return null;
    }
}