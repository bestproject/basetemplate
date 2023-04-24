/* 
 * Copyright (C) 2016 Artur Stępień
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

import $ from 'jquery';

/**
 * Scroll to section if link starts with #
 */
$.fn.scrollToSection = (speed = 700)=>{
    const $elements = $('a[href*="#"]');
    const $navbarOffset = $('#navigation').height() + 25;

    // Scrolling function
    const scrollTo = function(query){

        $('html,body').animate({
            scrollTop: $(query).offset().top - $navbarOffset
        }, speed);
    }

    // Bind to every link
    for (let i = 0, ic = $elements.length; i < ic; i++) {
        const $href = $($elements[i]).attr('href');
        if ($href.charAt(0) === '#' && $href.length > 1) {

            // Click scrolls to section
            $($elements[i]).click(function (e) {
                e.preventDefault();

                scrollTo($(this).attr('href'));
            });
        }
    }

    // On first run detect if this is a current link
    if( window.location.hash && $(window.location.hash).length ) {
        scrollTo($(window.location.hash));
    }
};