<div class="mx-auto w-full max-w-[1200px] px-4 py-6">
    <div class="md:flex">
        <div class="md:w-1/4 flex flex-col p-3">
            <div class="w-full h-[152px] bg-cover bg-center flex items-center justify-center rounded-xl mb-5"
                style="background-image: url('{{ asset('images/img_map.png') }}');">
                <x-button label="Xem bản đồ" />
            </div>
            <div class="rounded-lg bg-white p-4 shadow-sm">
                <h2 class="mb-3 text-xl font-semibold py-4 border-b-1 border-gray-200">Lọc kết quả</h2>
                <x-select title="Giờ mở cửa" :options="['Tất cả', 'Đang mở cửa']" type="radio" model="openingNow" />
                <x-select title="Danh mục" :options="['Ăn Uống', 'Du lịch', 'Giải Trí', 'Khách sạn', 'Mua sắm']" type="checkbox" />
                <x-select title="Khu vực" :options="['Khu vực Khác', 'phường Bảo Vinh', 'phường Bàu Sen', 'phường Phú Bình', 'phường Suối Tre', 'phường Xuân An', 'phường Xuân Bình', 'phường Xuân Hòa', 'phường Xuân Lập', 'phường Xuân Tân']" type="checkbox" />
                <x-select title="Tiện ích" :options="['Bàn ngoài trời', 'Chỉ Bán Mang Đi', 'Chỗ chơi cho trẻ em', 'Chỗ đậu ôtô', 'Giao hàng', 'Giữ xe máy', 'Máy lạnh, & điều hòa']" type="checkbox" />
            </div>
        </div>
        <div class="md:w-3/4 p-3">
            <div class="mb-2.5 flex items-center justify-between">
                <div class="text-lg">
                    <b>912</b> địa điểm khớp với tìm kiếm của bạn:
                </div>
                <div class="flex items-center gap-3 min-w-0">
                    <div class="whitespace-nowrap">Sắp xếp theo:</div>
                    <select name="sort" id="sort" class="select select-bordered w-36 shrink-0">
                        <option value="1">Đúng nhất</option>
                        <option value="2">Điểm đánh giá</option>
                        <option value="3">Gần tôi nhất</option>
                    </select>
                </div>
            </div>
            @foreach ($stores ?? [] as $store)
                <x-card-product 
                    :image="$store->image_url" 
                    :title="$store->name" 
                    :description="$store->short_description ?? $store->description" 
                    :rating="(float) ($store->reviews_avg_rating ?? 0)"
                    :reviews_count="$store->reviews_count"
                    :address="$store->address"
                    :status="$store->status"
                    :status_label="$store->status_label"
                    :opening_time="$store->opening_time"
                    :closing_time="$store->closing_time"
                    :latitude="$store->latitude"
                    :longitude="$store->longitude"
                    href="#" 
                />
            @endforeach
            <div class="mt-5">
                {{ $stores->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
</div>
@vite(['resources/js/geo.js'])