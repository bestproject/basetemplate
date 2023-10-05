<?php

namespace BestProject\Helper;

/**
 * Text processing helper class.
 */
class StringHelper
{

    /**
     * Conversion settings for lonelyLetter method.
     *
     * @since 1.5.0
     */
    protected const  LONELY = [
        [' i ', ' z ', ' o ', ' a ', ' u '],
        [' i&nbsp;', ' z&nbsp;', ' o&nbsp;', ' a&nbsp;', ' u&nbsp;']
    ];

    /**
     * Split text into parts as equal as possible and adds an "i" tag with the breakpoint class.
     *
     * @param   string  $text              Text to split.
     * @param   int     $lines             Numer of lines to display.
     * @param   string  $breakpoint_class  A class that will be used to split the words
     *
     * @return string
     */
    public static function split(string $text, int $lines, string $breakpoint_class = 'd-none d-lg-block'): string
    {
        $text                = trim($text);
        $length              = strlen($text);
        $part_minimum_length = ceil($length / $lines);
        $parts               = explode(' ', $text);
        $parts_count         = count($parts);
        $html                = '';

        // Split short lines into two lines
        if( $parts_count<3 ) {
            return str_ireplace(' ', ' <i aria-hidden="true" class="' . $breakpoint_class . '"></i>', $text);
        }

        $line_length = 0;
        for ($i = 0, $word = $parts[$i]; $i < $parts_count; $i++, $word = $parts[$i] ?? '') {

            // No short signs at line end
            if (strlen($word) < 3) {
                $html        .= $word . "&nbsp;";
                $line_length += strlen("$word ");
                continue;
            }


            // If this line exceeds max length, break it
            if ($line_length + strlen($word) >= $part_minimum_length) {
                $html        .= $word . ' <i aria-hidden="true" class="' . $breakpoint_class . '"></i>';
                $line_length = 0;
            } else {
                $html        .= $word . ' ';
                $line_length += strlen("$word ");
            }
        }

        return $html;
    }

    /**
     * Get phone numbers links.
     *
     * @param   string  $phones
     *
     * @return string
     */
    public static function getPhoneNumbersHtml(string $phones, string $protocol = 'tel:'): string
    {
        $parts = explode(PHP_EOL, str_ireplace([',', ';', '|'], PHP_EOL, $phones));

        $html = '';
        foreach ($parts as $phone) {
            $html .= '<div><a href="' . $protocol . self::getPhoneAttrSafe($phone) . '">' . $phone . '</a></div>';
        }

        return $html;
    }

    /**
     * Return phone number safe for use in tel: prefix of href attribute.
     *
     * @param   string  $phone
     *
     * @return string
     */
    public static function getPhoneAttrSafe(string $phone): string
    {
        return preg_replace('/[^0-9\-+]/', '', $phone);
    }

    /**
     * Bold the first word in sentence.
     *
     * @param   string  $text
     *
     * @return string
     */
    public static function boldFirst(string $text): string
    {
        $parts = explode(' ', $text);

        // Don't bold a single word
        if (count($parts) === 1) {
            return $text;
        }

        $parts[0] = "<strong>{$parts[0]}</strong>";

        return implode(' ', $parts);
    }

    /**
     * Convert lonely letters in text to always join next word.
     *
     * @param   string  $text  Text to convert.
     *
     * @return string
     */
    public static function lonelyLetter(string $text): string
    {
        return str_replace(static::LONELY[0], static::LONELY[1], $text);
    }

}