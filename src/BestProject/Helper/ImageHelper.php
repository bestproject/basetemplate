<?php

namespace BestProject\Helper;

use Joomla\Utilities\ArrayHelper;

/**
 * Helper class providing functions used in images html rendering.
 */
abstract class ImageHelper
{

    /**
     * Get width and height attributes for provided image URL (
     *
     * @param   string  $url
     *
     * @return string
     */
    public static function getSizeAttributes(string $url): string
    {

        // Nothing to do
        if( strpos($url, '#')===false ) {
            return '';
        }

        // Get image details
        [$src, $details] = explode('#', $url, 2);
        $attributes = [];

        // if this is SVG read size from file
        if( pathinfo($src, PATHINFO_EXTENSION)==='svg' ) {
            $svg = file_get_contents(JPATH_ROOT.$src);
            $xml = simplexml_load_string($svg);
            $xml_attributes = $xml->attributes();
            $width = (string) $xml_attributes->width;
            $height = (string) $xml_attributes->height;
            if( $width>0 && $height>0 ) {
                $attributes['width'] = $width;
                $attributes['height'] = $height;
            }
        } else {

            parse_str( parse_url( $details, PHP_URL_QUERY), $details );

            if( array_key_exists('width', $details) && $details['width']>0 && array_key_exists('height', $details) && $details['height']>0 ) {
                $attributes['width'] = $details['width'];
                $attributes['height'] = $details['height'];
            }
        }

        if( $attributes!==[] ) {
            return ArrayHelper::toString($attributes);
        }

        return '';
    }
}