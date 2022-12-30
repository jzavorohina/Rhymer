<?php

/**
 * //////////////////
 * // Rhymer class//
 * ////////////////
 * 
 * A PHP сlass for finding rhymes to words. 
 * 
 * Key features:
 * - Finds rhymes to the word by the first syllable at the beginning of the word (for Russian words).
 * - Finds rhymes to the word by the last syllable at the end of the word (for Russian words).
 * 
 * Some examples:
 * Finds rhymes to the word by the first syllable at the beginning of the word
 * Rhymer::findByStart('Key word'); // return: array

 * Finds rhymes to the word by the last syllable at the end of the word
 * Rhymer::findByEnd('Key word'); // return: array
 *
 * @author jzavorohina@yandex.ru
 * 
 */

class Rhymer
{

    public static $GROUPS = array(
        "vowels" => array('а', 'е', 'ё', 'и', 'о', 'у', 'э', 'ю', 'я'),
        "consonantsSonorousOnly" => array('л', 'м', 'н', 'р', 'й'),
        "consonantsSonorous" => array('б', 'в', 'г', 'д', 'з', 'ж'),
        "consonantsMuffled" => array('к', 'п', 'с', 'ф', 'т', 'ш', 'щ', 'х', 'ц', 'ч')
    );
    public static $GROUPS_IDS = array(
        "vowels" => 4,
        "consonantsSonorousOnly" => 3,
        "consonantsSonorous" => 2,
        "consonantsMuffled" => 1
    );

    /**
     * Find rhymes by start of the word
     * 
     * @param string $string - input word	 
     * @return array - found rhymes
     */
    public static function findByStart($string)
    {
        return self::find($string, false);
    }

    /**
     * Find rhymes by end of the word
     * 
     * @param string $string - input word	 
     * @return array - found rhymes
     */
    public static function findByEnd($string)
    {
        return self::find($string, true);
    }

    private static function find($string, $end = true)
    {
        if (!$string || strlen($string) < 1) {
            return array();
        }

        $result = array();
        $parsed = self::parseString($string);
        $pattern = self::getRegExp($parsed, 2, $end);

        $fh = fopen('src/wordsBase.txt', 'r');
        while ($w = fgets($fh)) {
            $word = trim($w);
            if (preg_match($pattern, $word) && mb_strtolower($word, 'UTF-8') !== mb_strtolower($string, 'UTF-8')) {
                $result[] = $word;
            }
        }
        fclose($fh);

        return $result;
    }

    private static function getRegExp($slogi, $num = 1, $end = true)
    {
        $keys = array();
        $set = array();
        $reverse = array_reverse($slogi);

        for ($i = 0; $i < $num; $i++) {
            $keys[] = $i;
        }

        foreach ($keys as $key) {
            $set[] = ($end) ? $reverse[$key] : $slogi[$key];
        }

        if ($end) {
            $set = array_reverse($set);
        }

        if ($end) {
            return '/' . implode("", $set) . '$/iu';
        } else {
            return '/^' . implode("", $set) . '/iu';
        }
    }

    private static function parseString($string)
    {
        $result = "";
        $stringByGroupIds = array();
        $strLen = mb_strlen($string, "UTF-8");
        $symbols = mb_str_split($string, 1, "UTF-8");

        foreach ($symbols as $key => $word) {
            $found = false;

            foreach (self::$GROUPS as $groupKey => $group) {
                $isInGroup = in_array($word, $group);
                if (!empty($isInGroup)) {
                    $stringByGroupIds[$key] = self::$GROUPS_IDS[$groupKey];
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $stringByGroupIds[$key] = $symbols[$key];
            }
        }

        foreach ($stringByGroupIds as $k => $groupId) {
            $nextGroupId = ($strLen === $k + 1) ? $stringByGroupIds[$k] : $stringByGroupIds[$k + 1];

            if ($groupId - $nextGroupId == 0 && $nextGroupId == self::$GROUPS_IDS["vowels"]) {
                $result .= $symbols[$k] . (($strLen !== $k + 1) ? "-" : "");
            } else {
                if (!is_numeric($nextGroupId) || $groupId - $nextGroupId <= 0) {
                    $result .= $symbols[$k];
                } else {
                    $result .= $symbols[$k] . "-";
                }
            }
        }

        return explode("-", $result);
    }
}
