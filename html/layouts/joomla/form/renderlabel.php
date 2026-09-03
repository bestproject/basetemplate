<?php

/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   (C) 2014 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

extract($displayData);

/**
 * Layout variables
 * -----------------
 * @var   string   $text      The label text
 * @var   string   $for       The id of the input this label is for
 * @var   boolean  $required  True if a required field
 * @var   array    $classes   A list of classes
 */

$classes[] = 'form-label';
$classes = array_filter((array) $classes);
$id      = $for . '-lbl';

if ($required) {
    $classes[] = 'required';
}

/**
 * @var Joomla\CMS\Form\FormField $field
 */
$field = $displayData['field'];
$type = $field->getAttribute('type');
$required_html  = '<span class="text-danger" aria-hidden="true">&#160;*</span>';
if( $required && $type ==='checkbox')
{
    if( str_contains($text, '</') ) {
        $text = substr_replace($text,$required_html, strrpos($text, '</'), 0);
    } else {
        $text.= $required_html;
    }
}

?>
<label id="<?php echo $id; ?>" for="<?php echo $for; ?>"<?php if (!empty($classes)) {
    echo ' class="' . implode(' ', $classes) . '"';
} ?>>
    <?php echo $text; ?><?php if ($required && $type !=='checkbox') :
        echo $required_html;
    endif; ?>
</label>
