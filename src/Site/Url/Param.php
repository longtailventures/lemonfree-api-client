<?php

namespace LemonFree\Api\Site\Url;

class Param
{
    public static function encode($param, $capitalize = true)
    {
        // RULES:
        // '-' => '='
        // '/' => '+-'
        // ', ' => '--'
        // ' ' => '-'
        // ',' => '~'
        // ' - ' => '---'
        $formattedParam = str_replace(
            array(
                ' - ',
                '-',
                '/',
                ', ',
                ' ',
                ',',
                '[SPACE_DASH_SPACE]',
                '[DASH]',
                '[SLASH]',
                '[COMMA_SPACE]',
                '[SPACE]',
                '[COMMA]'
            ),
            array(
                '[SPACE_DASH_SPACE]',
                '[DASH]',
                '[SLASH]',
                '[COMMA_SPACE]',
                '[SPACE]',
                '[COMMA]',
                '---',
                '=',
                '+-',
                '--',
                '-',
                '~'
            ),
            $param
        );

        $capitalize = false;

        return $capitalize
            ? strtoupper($formattedParam)
            : $formattedParam;
    }


    public static function decode($param, $urlencode = true)
    {
        // we need to url encode to param because browsers encode '+' which is one of our special characters
        // also... do not apply url encode for params with '=' character, should probably have a better fix for this
        // only for web, not unit tests!
        if (php_sapi_name() !== "cli" && $urlencode && stripos($param, '=') === false)
            $param = urlencode($param);

        // RULES:
        // '=' => '-',
        // '+-' => '/',
        // '--' => ', ',
        // '-' => ' ',
        // '~' => ',',
        // '---' => ' - '
        return str_replace(
            array(
                '=',
                '+-',
                '---',
                '--',
                '-',
                '~',
                '[EQUALS]',
                '[PLUS_DASH]',
                '[DASH_DASH_DASH]',
                '[DASH_DASH]',
                '[DASH]',
                '[TILDE]'
            ),
            array(
                '[EQUALS]',
                '[PLUS_DASH]',
                '[DASH_DASH_DASH]',
                '[DASH_DASH]',
                '[DASH]',
                '[TILDE]',
                '-',
                '/',
                ' - ',
                ', ',
                ' ',
                ','
            ),
            $param
        );
    }
}