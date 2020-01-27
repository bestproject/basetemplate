import jquery from 'jquery';
import 'swiper/dist/css/swiper.css';
import Swiper from 'swiper';

// Expose jQuery
const $ = jquery;
global.$ = global.jQuery = $;

window.Swiper = global.Swiper = Swiper;