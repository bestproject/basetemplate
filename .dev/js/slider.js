import Swiper from 'swiper';
import { Navigation, Autoplay, A11y } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css';

Swiper.use([Navigation, Autoplay, A11y]);

window.Swiper = global.Swiper = Swiper;