<?php defined('_JEXEC') or die;

// Prepare document head
require_once __DIR__.'/includes.php';
use BestProject\Bootstrap3;

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

    <body class="site <?php echo $class ?>">
		
        <!--Page header-->
        <header>

            <!--Page navigation-->
            <nav id="nav" class="navbar navbar-default" <?php echo ($has_menu_fixed ? ' data-spy="affix" data-offset-top="1"':'') ?>>
					
				<!--Navigation header-->
				<div class="navbar-header navbar-left sm-block">

					<!--Menu button-->
					<?php if( $has_menu ): ?>
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navigation" aria-expanded="false">
						<span class="sr-only"><?php echo JText::_('TPL_BASETHEME_TOGGLE_NAVIGATION') ?></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<?php endif ?>

					<!--Logo-->
					<?php if( $is_frontpage ): ?>
						<h1 class="logo">
						   <a class="navbar-brand" href="<?php echo JURI::Base() ?>" title="<?php echo $sitename ?>">
								<?php if ($this->params->get('logoFile')): ?>
									<img src="<?php echo $logoFile ?>" alt="<?php echo $sitename ?>" />
								<?php else: ?>
									<?php echo $sitename ?>
								<?php endif ?>
								<?php if( !empty($slogan) ): ?>
									<small class="xs-block"><?php echo $slogan ?></small>
								<?php endif ?>
							</a>
						</h1>
					<?php else: ?>
						<figure class="logo">
							<a class="navbar-brand" href="<?php echo JURI::Base() ?>" title="<?php echo $sitename ?>">
								<?php if ($this->params->get('logoFile')): ?>
									<img src="<?php echo $logoFile ?>" alt="<?php echo $sitename ?>" />
								<?php else: ?>
									<?php echo $sitename ?>
								<?php endif ?>
								<?php if( !empty($slogan) ): ?>
									<small class="xs-block"><?php echo $slogan ?></small>
								<?php endif ?>
							</a>
						</figure>
					<?php endif ?>
					
				</div>

				<?php if( $has_menu ): ?>
					<!--Main navigation-->
					<div id="main-navigation" class="navbar-collapse collapse navbar-right sm-block">
						<?php echo Bootstrap3::renderModulesPosition('menu',false) ?>
					</div>
				<?php endif ?>
            </nav>

			<?php if( $has_slider ): ?>
			<div class="slider">
				<?php echo Bootstrap3::renderModulesPosition('slider', 'jumbotron text-center') ?>
			</div>
			<?php endif ?>
			
        </header>

        <!--System messages-->
        <jdoc:include type="message" />

		<?php if( $has_content ):
		$content_class = ($has_left AND $has_right) ? 'col-sm-6' : ''; // Both columns has content
		$content_class = (empty($content_class) AND ($has_left OR $has_right)) ? 'col-sm-8': $content_class; // Only one column has content
		?>
		
			<!--Content blocks-->
			<div class="container">
				<div id="content" class="row">

					<?php if( $has_left ): ?>
					<!--Left column-->
					<aside class="col-xs-12 <?php echo ($has_right?'col-sm-3':'col-sm-4') ?>">
						<?php echo Bootstrap3::renderModulesPosition('left') ?>
					</aside>
					<?php endif ?>

					<!--Main content-->
					<main class="col-xs-12 <?php echo $content_class ?>">

						<jdoc:include type="component" />

					</main>

					<?php if( $has_right ): ?>
					<!--Right column-->
					<aside class="col-xs-12 <?php echo ($has_left?'col-sm-3':'col-sm-4') ?>">
						<?php echo Bootstrap3::renderModulesPosition('right') ?>
					</aside>
					<?php endif ?>
					
				</div>
			</div>
		<?php endif ?>

        <?php if( $has_debug ): ?>
        <!--System debug data-->
        <div class="container">
            <jdoc:include type="modules" name="debug" style="none" />
        </div>
        <?php endif ?>
		
        <!--Footer-->
        <footer>

			<!--Copyrights info-->
			<p class="copyrights pull-left muted">© <?php echo date('Y') ?> <?php echo $copyrights ?></p>

        </footer>

    </body>
</html>
<?php

// Remove Bootstrap 2
$document = JFactory::getDocument();
unset($document->_scripts['/media/jui/js/bootstrap.min.js']);
unset($document->_scripts['/media/jui/js/bootstrap.js']);