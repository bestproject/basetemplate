<?php defined('_JEXEC') or die;

// Prepare document head
require_once __DIR__.'/includes.php';
use BestProject\Bootstrap;


/**
 * == CUSTOM VARIABLES =========================================================
 */
// Enter your custom variables here

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $language; ?>" lang="<?php echo $language; ?>" dir="<?php echo $direction; ?>">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <jdoc:include type="head" />
        <!--[if lt IE 9]>
            <script src="<?php echo JUri::root(true); ?>/media/jui/js/html5.js"></script>
        <![endif]-->
    </head>

    <body class="site <?php echo $class ?> modal-view">

        <!--Main content-->
        <main class="col-xs-12">

            <jdoc:include type="component" />

        </main>

    </body>
</html>
<?php

// Remove Bootstrap 2
$document = JFactory::getDocument();
unset($document->_scripts['/media/jui/js/bootstrap.min.js']);
unset($document->_scripts['/media/jui/js/bootstrap.js']);