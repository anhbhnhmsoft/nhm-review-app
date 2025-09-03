<main>
    <section class="max-w-6xl [padding-block:18px!important] mx-auto px-4 ">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <aside class="col-span-1">
                <div id="filter-sidebar" class="bg-white rounded-lg shadow p-4 sm:p-3 sticky top-6">
                    <h2
                        class="text-lg font-semibold text-gray-800 mb-4 flex border-b-[1px] pb-3 border-b-[#ccc] items-center gap-2">
                        <x-heroicon-o-funnel class="h-5 w-5 text-gray-600" />
                        Lọc kết quả
                    </h2>

                    <form method="GET" action="{{ route('search') }}" class="space-y-4">
                        <div class="collapse collapse-arrow ">
                            <input type="checkbox" checked />
                            <div class="collapse-title text-md font-medium">
                                Giờ mở cửa
                            </div>
                            <div class="collapse-content max-h-48 overflow-y-auto">
                                <label class="flex items-center gap-2 py-1">
                                    <input type="checkbox" class="checkbox checkbox-sm" /> Tất cả
                                </label>
                                <label class="flex items-center gap-2 py-1">
                                    <input type="checkbox" class="checkbox checkbox-sm" /> Đang mở cửa
                                </label>
                            </div>
                        </div>
                        <div class="collapse collapse-arrow ">
                            <input type="checkbox" checked />
                            <div class="collapse-title text-md font-medium">
                                Danh mục
                            </div>
                            <div class="collapse-content max-h-48 overflow-y-auto">
                                <label class="flex items-center gap-2 py-1">
                                    <input type="checkbox" class="checkbox checkbox-sm" /> Ăn uống
                                </label>
                                <label class="flex items-center gap-2 py-1">
                                    <input type="checkbox" class="checkbox checkbox-sm" /> Du lịch
                                </label>
                                <label class="flex items-center gap-2 py-1">
                                    <input type="checkbox" class="checkbox checkbox-sm" /> Giải trí
                                </label>
                                <label class="flex items-center gap-2 py-1">
                                    <input type="checkbox" class="checkbox checkbox-sm" /> Khách sạn
                                </label>
                            </div>
                        </div>

                        <div class="collapse collapse-arrow ">
                            <input type="checkbox" checked />
                            <div class="collapse-title text-md font-medium">
                                Bộ sưu tập
                            </div>
                            <div class="collapse-content max-h-48 overflow-y-auto">
                                <label class="flex items-center gap-2 py-1">
                                    <input type="checkbox" class="checkbox checkbox-sm" /> Ăn Chiều
                                </label>
                                <label class="flex items-center gap-2 py-1">
                                    <input type="checkbox" class="checkbox checkbox-sm" /> Ăn Sáng
                                </label>
                                <label class="flex items-center gap-2 py-1">
                                    <input type="checkbox" class="checkbox checkbox-sm" /> Ăn Trưa
                                </label>
                                <label class="flex items-center gap-2 py-1">
                                    <input type="checkbox" class="checkbox checkbox-sm" /> Ăn Vặt
                                </label>
                            </div>
                        </div>

                        <div class="collapse collapse-arrow ">
                            <input type="checkbox" checked />
                            <div class="collapse-title text-md font-medium">
                                Tiện ích
                            </div>
                            <div class="collapse-content max-h-48 overflow-y-auto">
                                <label class="flex items-center gap-2 py-1">
                                    <input type="checkbox" class="checkbox checkbox-sm" /> Bàn ngoài trời
                                </label>
                                <label class="flex items-center gap-2 py-1">
                                    <input type="checkbox" class="checkbox checkbox-sm" /> Ăn Sáng
                                </label>
                                <label class="flex items-center gap-2 py-1">
                                    <input type="checkbox" class="checkbox checkbox-sm" /> Ăn Trưa
                                </label>
                                <label class="flex items-center gap-2 py-1">
                                    <input type="checkbox" class="checkbox checkbox-sm" /> Ăn Vặt
                                </label>
                            </div>
                        </div>

                    </form>

                    {{-- nếu bạn muốn component filter rời, mình có thể tách ra sau --}}
                </div>
            </aside>

            <!-- RIGHT: Summary / Results -->
            <section class="col-span-3">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                    <div>
                        <p class="text-sm text-base-content/70">
                            Hiển thị <strong>{{ $stores->total() ?? $stores->count() }}</strong> địa điểm phù hợp với
                            tìm kiếm của bạn
                            @if (request()->has('q'))
                                cho "<span class="italic">{{ request('q') }}</span>"
                            @endif
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <label class="text-sm">Sắp xếp:</label>
                        <form method="GET" action="{{ route('search') }}" class="inline-block">
                            <input type="hidden" name="q" value="{{ request('q') }}">
                            <select name="sort" onchange="this.form.submit()" class="select select-sm">
                                <option value="">Mặc định</option>
                                <option value="nearest" @if (request('sort') == 'nearest') selected @endif>Theo
                                    khoảng cách</option>
                                <option value="rating" @if (request('sort') == 'rating') selected @endif>Đánh giá
                                </option>
                            </select>
                        </form>
                    </div>
                </div>

                {{-- Results grid / list: sử dụng component store-card --}}
                <div class="grid grid-cols-1 gap-4">
                    @forelse ($stores as $store)
                        {{-- giữ nguyên cách include component (Livewire / Blade) --}}
                        <x-search.store-card :store="$store" />
                    @empty
                        <div class="p-6 text-center text-gray-500">
                            Không có địa điểm nào phù hợp.
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if ($stores->hasPages())
                    <div class="mt-4 flex justify-center">
                        {{ $stores->links() }}
                    </div>
                @endif
            </section>
        </div>
    </section>
</main>
