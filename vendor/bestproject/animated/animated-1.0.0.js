/*
	Name:			Animated
	Version:		1.0.0
	Description:	Script that uses animate.css on scroll
	Created on:		2016-02-29
	Author:			Artur Stępień  (artur.stepien@bestproject.pl)
	Author URI:		http://www.bestproject.pl
	Copyrights:		BestProject
	License:		GNU GPL 3.0 (See LICENSE file)
	Requires:		animated.css and jQuery
	How to use:		See example.html
*/
(function ($) {

	$.fn.Animated = function () {

		var $window = $(window);


		$window.on('scroll', revealOnScroll);

		function revealOnScroll() {
			var win_height_padded = $window.height() * 1.1;
			var scrolled = $window.scrollTop(),
					win_height_padded = $window.height() * 1.1;

			// Showed...
			$('[class*="animated-"]').each(function () {
				var $this = $(this);
				var offsetTop = $this.offset().top;

				if (scrolled + win_height_padded > offsetTop) {
					$this[0].className = $this[0].className.replace('animated-','animated ');
				}
			});

			// Hidden...
			$(".animated").each(function (index) {
				var $this = $(this);
				var offsetTop = $this.offset().top;
				if (scrolled + win_height_padded < offsetTop) {
					$this[0].className = $this[0].className.replace('animated ','animated-');
				}
			});
		}

		setTimeout(function(){
			revealOnScroll();
		}, 100);

		return this;
	};

}(jQuery));
