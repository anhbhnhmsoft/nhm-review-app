<button
    wire:click="saveLocation()"
    @if($absolute)
        class="absolute tooltip top-2 right-2 z-10 shadow-sm hover:shadow-lg transition-all duration-300 {{$status_save_loc ?  "bg-green-600 text-white hover:text-green-600 hover:bg-white" : "bg-white hover:text-green-600"}} rounded-[20px] w-8 h-8 flex items-center justify-center cursor-pointer"
    @else
        class="tooltip shadow-sm hover:shadow-lg transition-all duration-300 {{$status_save_loc ?  "bg-green-600 text-white hover:text-green-600 hover:bg-white" : "bg-white hover:text-green-600"}} rounded-[20px] w-8 h-8 flex items-center justify-center cursor-pointer"
    @endif
    @if($status_save_loc)
        data-tip="Bỏ lưu địa điểm yêu thích"
    @else
        data-tip="Lưu địa điểm yêu thích"
    @endif
>
    <i class="fa-regular fa-bookmark"></i>
</button>
