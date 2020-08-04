<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2018.06.10
 * Time: 18:18
 */

namespace app\filters;


class RegexModifierFilter
{
    public static function filter($value) {
        $modifiers = "imsxADSUXu";
        return count_chars(preg_replace("/[^{$modifiers}]/", "", $value), 3);
    }
}