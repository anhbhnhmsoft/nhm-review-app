<form wire:submit="submit" class="mt-8 w-full flex flex-col items-center justify-center space-y-4 md:px-32 lg:px-64 xl:px-80">
    <input wire:model="customer_name" type="text" class="bg-white/30 w-full border-none !outline-none text-white rounded-md px-[14px] py-[12px]" placeholder="Họ và tên"/>
    <input wire:model="customer_email" type="email" class="bg-white/30 w-full border-none !outline-none text-white rounded-md px-[14px] py-[12px]" placeholder="Email"/>
    <input wire:model="customer_phone" type="text" class="bg-white/30 w-full border-none !outline-none text-white rounded-md px-[14px] py-[12px]" placeholder="Số điên thoại"/>
    <textarea wire:model="note" class="bg-white/30 w-full border-none !outline-none text-white rounded-md px-[14px] py-[12px]" placeholder="Ghi chú thêm" rows="6"></textarea>
    <button class="btn bg-red-600 text-white hover:bg-red-400 uppercase border-none w-fit !outline-none lg:!px-8 lg:!py-2">Gửi yêu cầu</button>
</form>
