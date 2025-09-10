
import Swiper from 'swiper/bundle';
import { Fancybox } from '@fancyapps/ui';
import '@fancyapps/ui/dist/fancybox/fancybox.css';

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
    slidesPerView: 1,
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
    breakpoints: {
        1024: {
            slidesPerView: 3,
        },
    },
});


new Swiper('.store__category', {
    // Bật Auto Scroll
    slidesPerView: 1,
    spaceBetween: 30,
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    speed: 500,
    breakpoints: {
        640: {
            slidesPerView: 2,
            spaceBetween: 16
        },
        1024: {
            slidesPerView: 4,
        },
    }
});

new Swiper('.other-articles-swiper', {
    slidesPerView: 'auto',
    spaceBetween: 24,
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
    navigation: {
        nextEl: ".other-articles-next",
        prevEl: ".other-articles-prev",
    },
    loop: false,
    speed: 500,
    breakpoints: {
        320: {
            slidesPerView: 1,
            spaceBetween: 16,
        },
        640: {
            slidesPerView: 2,
            spaceBetween: 20,
        },
        768: {
            slidesPerView: 3,
            spaceBetween: 24,
        },
        1024: {
            slidesPerView: 4,
            spaceBetween: 24,
        },
    },
});

new Swiper('.news-swiper', {
    slidesPerView: 1,
    spaceBetween: 16,
    navigation: {
        nextEl: ".news-next",
        prevEl: ".news-prev",
    },
    loop: true,
    speed: 500,
    breakpoints: {
        320: {
            slidesPerView: 1,
            spaceBetween: 16,
        },
        640: {
            slidesPerView: 2,
            spaceBetween: 20,
        },
    },
});

new Swiper('.handbook-swiper', {
    slidesPerView: 1,
    spaceBetween: 16,
    navigation: {
        nextEl: ".handbook-next",
        prevEl: ".handbook-prev",
    },
    loop: true,
    speed: 500,
    breakpoints: {
        320: {
            slidesPerView: 1,
            spaceBetween: 16,
        },
        640: {
            slidesPerView: 2,
            spaceBetween: 20,
        },
    },
});

new Swiper('.featured-videos-swiper', {
    slidesPerView: 1,
    spaceBetween: 16,
    navigation: {
        nextEl: ".featured-videos-next",
        prevEl: ".featured-videos-prev",
    },
    loop: true,
    speed: 500,
    breakpoints: {
        320: {
            slidesPerView: 1,
            spaceBetween: 16,
        },
        640: {
            slidesPerView: 2,
            spaceBetween: 20,
        },
    },
});

window.Swiper = Swiper;

Fancybox.bind('[data-fancybox="featured-videos"]', {
    Video: {
        tpl: '<video class="fancybox__html5video" playsinline controls controlsList="nodownload" poster="{{poster}}">{{caption}}</video>',
        format: '',
        autoplay: true,
    },
    Toolbar: {
        display: {
            left: ["infobar"],
            middle: [
                "play",
                "slideshow",
                "thumbs",
                "toggleFS",
            ],
            right: ["close"],
        },
    },
    Thumbs: {
        autoStart: false,
    },
    on: {
        reveal: (fancybox, slide) => {
            if (slide.type === 'video') {
                const video = slide.$content.querySelector('video');
                if (video) {
                    video.play();
                }
            }
        },
    },
});

Fancybox.bind('[data-fancybox="featured-videos-mobile"]', {
    Video: {
        tpl: '<video class="fancybox__html5video" playsinline controls controlsList="nodownload" poster="{{poster}}">{{caption}}</video>',
        format: '',
        autoplay: true,
    },
    Toolbar: {
        display: {
            left: ["infobar"],
            middle: [
                "play",
                "toggleFS",
            ],
            right: ["close"],
        },
    },
    on: {
        reveal: (fancybox, slide) => {
            if (slide.type === 'video') {
                const video = slide.$content.querySelector('video');
                if (video) {
                    video.play();
                }
            }
        },
    },
});




