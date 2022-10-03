<?php defined('_JEXEC') or die;

// Prepare document head
require_once __DIR__.'/includes.php';

/**
 * == CUSTOM VARIABLES =========================================================
 */
// Enter your custom variables here

$this->setMetaData('viewport', 'width=device-width, initial-scale=1');
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <jdoc:include type="metas" />
    <jdoc:include type="styles" />
</head>
<body class="<?php echo $this->direction === 'rtl' ? 'rtl' : ''; ?>">
<jdoc:include type="message" />
<jdoc:include type="component" />
<jdoc:include type="scripts" />
</body>
</html>