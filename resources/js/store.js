import { Fancybox } from '@fancyapps/ui/dist/fancybox/';
import '@fancyapps/ui/dist/fancybox/fancybox.css';
import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';
import 'filepond/dist/filepond.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';
import { create, registerPlugin } from 'filepond';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
registerPlugin(FilePondPluginImagePreview, FilePondPluginFileValidateSize, FilePondPluginFileValidateType);

Fancybox.bind('[data-fancybox]', {
    compact: true,                // UI gọn trên mobile
    dragToClose: true,            // vuốt để đóng
    contentClick: "toggleZoom",   // chạm để zoom/fit
    Images: {
        initialSize: "fit",         // contain trong viewport
        zoom: true,
    },
    Thumbs: { autoStart: false },
    Toolbar: {
        display: {
            left: ["infobar"],
            right: ["zoom","slideshow","fullscreen","close"]
        }
    }
});

new Swiper('#store_utilities', {
    pagination: false,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false,
    },
    slidesPerView: 3,
    spaceBetween: 30,
    speed: 500,
    breakpoints: {
        1024: {
            slidesPerView: 5,
        },
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const input = document.querySelector('#review_files');
    if (input){
        const instance = create(input, {
            storeAsFile: true,
            maxFiles: 5,
            maxFileSize: '10MB',
            acceptedFileTypes: ['image/jpg','image/jpeg','image/png'],
            labelIdle:`Kéo thả file của bạn hoặc <span class="filepond--label-action"> Tìm kiếm </span>`,

            // Callback khi có file được thêm
            onaddfile: (error, fileItem) => {
                if (!error) {
                    updateLivewireFiles(instance);
                }
            },

            // Callback khi file được xóa
            onremovefile: (error, fileItem) => {
                if (!error) {
                    updateLivewireFiles(instance);
                }
            }

        });

        function updateLivewireFiles(pondInstance) {
            const files = pondInstance.getFiles().map(fileItem => fileItem.file);
            const filePromises = files.map(file => {
                return new Promise((resolve) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        resolve({
                            name: file.name,
                            type: file.type,
                            size: file.size,
                            content: e.target.result
                        });
                    };
                    reader.readAsDataURL(file);
                });
            });
            Promise.all(filePromises).then(filesData => {
                window.Livewire.dispatch('filesUpdated', { files: filesData });
            });
        }

        // Lắng nghe event từ Livewire để reset FilePond khi cần
        window.addEventListener('resetFilePond', () => {
            instance.removeFiles();
        });

    }
});





