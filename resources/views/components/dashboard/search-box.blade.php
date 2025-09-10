<div
    x-data="{openSearch: false}"
    class="absolute flex top-[-30px] lg:top-[-50px] left-0 right-0 z-10">
    <div class="grid grid-cols-4 lg:grid-cols-5 w-full items-center p-2 lg:px-[40px] lg:py-[20px] gap-4 bg-white rounded-lg shadow-sm">
        <input type="text"
               placeholder="Bạn muốn tìm kiếm gì và ở đâu? Hãy tìm trên Afy ngay nhé"
               autocomplete="off"
               class="input !border-none !outline-none lg:px-[25px] lg:!py-[23px] w-full col-span-3"
        />
        <button class="btn text-white bg-blue-600 hover:bg-blue-700 rounded-lg lg:px-[25px] lg:py-[23px] ">
            <i class="fa-solid fa-magnifying-glass"></i>
            <span class="text-base hidden lg:inline"> Tìm kiếm</span>
        </button>
        <a href="{{ route('frontend.search-store') }}"
           class="btn text-white bg-green-600 hover:bg-green-700 rounded-lg px-[25px] py-[23px] hidden lg:flex">
            <span class="text-base">Tìm địa điểm</span>
        </a>
    </div>
</div>
