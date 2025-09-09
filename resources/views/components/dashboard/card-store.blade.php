<a href="{{route('frontend.store',['slug' => $store->slug])}}" class="card w-full card-sm shadow-md border border-[#e8e8e8] bg-white py-5 px-4 transform transition-all duration-300 hover:scale-[103%] hover:shadow-lg cursor-pointer">
    <div class="relative w-full">
        <img src="{{\App\Utils\HelperFunction::generateURLImagePath($store->logo_path)}}" class="h-56 w-full object-cover rounded-md" alt="{{$store->name}}" loading="lazy">
        @if($store->featured)
            <div class="absolute right-1 top-1 px-3 py-1 text-sm font-medium text-red-600 bg-red-100 rounded-full">
                Nổi bật
            </div>
        @elseif(\App\Utils\HelperFunction::checkIsNewStore($store->created_at))
            <div class="absolute right-1 top-1 px-3 py-1 text-sm font-medium text-white bg-green-600 rounded-full">
                Mới
            </div>
        @endif
    </div>
    <div class="pt-4 flex flex-col space-y-4">
        <div class="space-y-2 flex flex-col">
            <div class="tooltip max-h-8 tooltip-top" data-tip="{{$store->name}}">
                <h1 class="truncate font-bold text-black text-2xl capitalize">{{$store->name}}</h1>
            </div>
            <div class="tooltip max-h-8 tooltip-top" data-tip="{{$store->address}}">
                <h1 class="truncate text-gray-400">{{$store->address}}</h1>
            </div>
        </div>
        <div class="flex items-center justify-between space-x-2">
            <div class="flex items-center space-x-1">
                <i class="fa-solid fa-location-dot text-blue-600"></i>
                <span class="text-base truncate text-gray-400">{{$store->province->name}}</span>
            </div>
            @if(\App\Utils\HelperFunction::checkIsStoreOpen(openingTime: $store->opening_time,closingTime: $store->closing_time))
                <div class="px-3 py-1 text-sm font-medium text-green-600 bg-green-100 rounded-full">
                    Mở cửa
                </div>
            @else
                <div class="px-3 py-1 text-sm font-medium text-red-600 bg-red-100 rounded-full">
                    Đóng cửa
                </div>
            @endif
        </div>
    </div>
</a>
