<?php

namespace App\Helpers;

/**
 * Class JiraCommentHelper
 * @package App\Helpers
 */
class JiraCommentHelper
{
    /**
     * @param $bodyText
     * @return bool
     */
    public static function containsMention($bodyText)
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
    public static function extractUserKey($bodyText)
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