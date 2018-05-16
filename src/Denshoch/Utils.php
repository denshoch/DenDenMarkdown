<?php
namespace Denshoch;

use Denshoch;

class Utils
{
    /**
     * remove Unicode control characters from input text.
     * https://stackoverflow.com/questions/1497885/remove-control-characters-from-php-string
     *
     * @param String text
     * @retrun String
     */
    public static function removeCtrlChars($text)
    {
        $text = str_replace("\xe2\x80\xa8", '', $text);
        $text = str_replace("\xe2\x80\xa9", '', $text);
        return preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', '', $text);
    }
}