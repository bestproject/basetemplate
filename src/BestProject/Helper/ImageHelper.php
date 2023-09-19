<?php

namespace BestProject\Helper;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;
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



    /**
     * Get attributes from Joomla! image url.
     * @param string $url
     *
     * @return array
     */
    public static function getImageAttributes(string $url): array
    {
        // Get details from url
        $details = HTMLHelper::_('cleanImageURL', $url);

        // Flatten the array
        $attributes = $details->attributes;
        $attributes['src'] = $details->url;

        return $attributes;
    }

    /**
     * Get image HTML code from Joomla! image url.
     *
     * @param   string  $url            Joomla! image url
     * @param   array   $attributes     Custom attributes.
     *
     * @return string
     */
    public static function render(string $url, array $attributes = []): string
    {
        $attributes += self::getImageAttributes($url);

        if( !array_key_exists('loading', $attributes) ) {
            $attributes['loading'] = 'lazy';
        }

        return LayoutHelper::render('joomla.html.image', $attributes);
    }
}