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
 * Changes elements class on scroll
 */
$.fn.classOnScroll = function (scrolledClass = "scrolled") {

    this.each(function (idx, el) {
        $(window).scroll(function () {
            var scroll = $(window).scrollTop();
            var $element = $(el);
            if (scroll > 0) {
                $element.addClass(scrolledClass);
            } else {
                $element.removeClass(scrolledClass);
            }
        });
    });

};