
import Swiper from 'swiper/bundle';

import 'swiper/css/bundle';


new Swiper('#banner__header', {
    // Ẩn Pagination
    pagination: false,  // Hoặc bạn có thể không khai báo pagination
    // Bật Auto Scroll
    autoplay: {
        delay: 3000, // Thời gian delay giữa mỗi slide (3 giây)
        disableOnInteraction: false,
    },
    loop: true, // Lặp lại các slide
    speed: 500,
});


new Swiper('#banner__ads', {
    // Bật Auto Scroll
    slidesPerView: 3,
    spaceBetween: 30,
    autoplay: {
        delay: 3000, // Thời gian delay giữa mỗi slide (3 giây)
        disableOnInteraction: false,
    },
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    loop: true, // Lặp lại các slide
    speed: 500,
});

new Swiper('.store__category', {
    // Bật Auto Scroll
    slidesPerView: 4,
    spaceBetween: 30,
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    loop: true, // Lặp lại các slide
    speed: 500,
});


