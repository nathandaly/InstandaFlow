<?php

namespace App\Helpers;

/**
 * Class JiraCommentHelper
 * @package App\Helpers
 */
class JiraCommentHelper
{
    /**
     * @param string $bodyText
     * @return bool
     */
    public static function containsMention(string $bodyText)
    {
        if (strpos($bodyText, '[~') !== false) {
            return true;
        }

        return false;
    }

    /**
     * @param string $bodyText
     * @return null
     */
    public static function extractUserKey(string $bodyText)
    {
        if (preg_match_all(
            '/\[~(.*?)\]/',
            $bodyText,
            $matches,
            PREG_SET_ORDER,
            0
        )) {
            return $matches;
        }

        return null;
    }
}
