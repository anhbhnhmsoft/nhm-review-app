<div x-data="{ openModalBooking: false }"
     x-on:close-modal-booking="openModalBooking = false"
>
    <button x-on:click="openModalBooking = true" class="btn btn-primary-blue btn-sm rounded-xl">
        Gửi liên hệ
    </button>
    <template x-teleport="body">
        <div
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-10 flex w-full h-full flex items-center justify-center bg-black/50 overflow-hidden"
            x-show="openModalBooking"
        >
            <div
                @click.away="openModalBooking = false"
                class="bg-white relative w-full max-w-lg rounded-2xl shadow-xl max-h-[85vh] overflow-y-auto overscroll-contain p-6"
            >
                <div class="absolute top-2 right-2">
                    <button @click="openModalBooking = false" class="btn rounded-full size-8 p-0">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <h1 class="font-bold text-lg ">Thông tin liên hệ</h1>
                <form wire:submit="submit" class="mt-8 w-full flex flex-col space-y-2 lg:space-y-4">
                    <fieldset class="fieldset w-full">
                        <legend class="fieldset-legend">Tên của bạn</legend>
                        <input wire:model="customer_name" type="text" class="input w-full" placeholder="Họ và tên"/>
                    </fieldset>
                    <fieldset class="fieldset w-full">
                        <legend class="fieldset-legend">Số điện thoại liên hệ</legend>
                        <input wire:model="customer_phone" type="text" class="input w-full" placeholder="Số điện thoại liên hệ"/>
                    </fieldset>
                    <fieldset class="fieldset w-full">
                        <legend class="fieldset-legend">Email của bạn</legend>
                        <input wire:model="customer_email" type="email" class="input w-full" placeholder="emal@email.example"/>
                    </fieldset>
                    <fieldset class="fieldset w-full">
                        <legend class="fieldset-legend">Email của bạn</legend>
                        <textarea wire:model="note" class="textarea w-full" placeholder="Ghi chú thêm" rows="6"></textarea>
                    </fieldset>
                    <button class="justify-self-end btn bg-red-600 text-white hover:bg-red-400 uppercase border-none w-fit !outline-none lg:!px-8 lg:!py-2">Gửi yêu cầu</button>
                </form>
            </div>
        </div>
    </template>
</div>














