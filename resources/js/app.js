require('./bootstrap');
import Alpine from 'alpinejs'
import Swal from 'sweetalert2'
import persist from '@alpinejs/persist'
import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
Swiper.use([Autoplay]);
window.Swal = Swal
window.Swiper = Swiper;
window.SwiperCustom = {
    Navigation,
    Pagination,
}
Alpine.plugin(persist)
window.Alpine = Alpine

Alpine.start()