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
 * Adds a back to top button when page is scrolled
 */
$.fn.backToTopButton = function (options = {}) {

    var settings = {
        button_text: 'Back to top',
        button_id: 'back-to-top',
        button_class: 'btn btn-default',
        icon_class: 'fas fa-angle-up',
    };

    $.extend(settings, options)

    // Create button
    var $btn = $("<button></button>");
    $btn.attr({
        'id': settings.button_id,
        'class': 'back-to-top ' + settings.button_class,
    });

    // Add icon
    if (settings.icon_class.length) {
        $btn.append($("<i class='" + settings.icon_class + "' aria-hidden='true'></i>"));
    }

    // Add hidden text
    if (settings.button_text.length) {
        $btn.append($("<span class='sr-only'>" + settings.button_text + "</span>"));
    }

    // Append button
    $('body').append($btn);

    // Show/hide button on scroll
    var scrollTrigger = 100, // px
        backToTop = function () {
            var scrollTop = $(window).scrollTop();
            if (scrollTop > scrollTrigger) {
                $btn.addClass('show');
            } else {
                $btn.removeClass('show');
            }
        };
    backToTop();
    $(window).on('scroll', backToTop);

    // On button click, scroll o top
    $btn.on('click', function (e) {
        e.preventDefault();
        $('html,body').animate({
            scrollTop: 0
        }, 700);
    });
};