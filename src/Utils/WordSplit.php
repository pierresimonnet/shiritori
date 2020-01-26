<?php


namespace App\Utils;


class WordSplit
{
    /**
     * @param string $word
     * @return array
     */
    public static function split(string $word)
    {
        $wordSplit = preg_split("//u", $word, -1, PREG_SPLIT_NO_EMPTY);
        return count($wordSplit) == 1 ? [$word] : ['first' => reset($wordSplit), 'last' => end($wordSplit)];
    }
}