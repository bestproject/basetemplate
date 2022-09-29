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

    for (let i = 0, ic = $elements.length; i < ic; i++) {
        const $href = $($elements[i]).attr('href');
        if ($href.charAt(0) === '#' && $href.length > 1) {
            $($elements[i]).click(function (e) {
                e.preventDefault();

                $('html,body').animate({
                    scrollTop: $($(this).attr('href')).offset().top
                }, speed);
            });
        }

    }
};