<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    @php
        $ratingLabel = [
            'rating_location' => 'Vị trí',
            'rating_space' => 'Không gian',
            'rating_quality' => 'Chất lượng',
            'rating_serve' => 'Phục vụ',
        ];
    @endphp
    <header class="bg-gradient-to-b from-[#c8e4cc] to-[#fafafa] rounded-b-2xl h-64 relative overflow-hidden">
        <div class="absolute inset-x-0 bottom-0 flex justify-center pb-6">
            <div class="relative" wire:key="avatar-{{ $user->id }}-{{ $user->updated_at }}">
                <img src="{{ \App\Utils\HelperFunction::generateURLImagePath($user->avatar_path) }}"
                    onerror="this.src='{{ asset('images/avatar.png') }}'" onclick="avatar_upload.showModal()"
                    class="w-44 h-44 sm:w-48 sm:h-48 p-2 cursor-pointer bg-white rounded-full shadow-lg hover:shadow-xl transition-shadow duration-300"
                    alt="Avatar" />

                <button onclick="avatar_upload.showModal()"
                    class="absolute bottom-4 right-4 w-12 h-12 bg-primary text-white rounded-full shadow-lg hover:bg-primary/90 transition-colors duration-200 flex items-center justify-center"
                    aria-label="Cập nhật ảnh đại diện">
                    <i class="fa-solid fa-camera text-lg"></i>
                </button>
            </div>
        </div>
    </header>

    <section class="mt-8 space-y-6">
        <h1 class="text-center text-2xl sm:text-3xl font-bold pb-4 border-b border-gray-200">
            {{ $user->name }}
        </h1>

        <div class="flex justify-center">
            <div class="bg-white rounded-lg p-1 shadow-sm">
                <a href="#reviews"
                    class="tab-btn px-6 py-2 text-lg cursor-pointer font-semibold rounded-md transition-all duration-200"
                    data-tab="reviews">
                    Đánh giá
                </a>
                <a href="#saved"
                    class="tab-btn px-6 py-2 text-lg cursor-pointer font-semibold rounded-md transition-all duration-200"
                    data-tab="saved">
                    Đã lưu
                </a>
            </div>
        </div>

        <div class="lg:hidden text-center">
            <button onclick="update_info_modal.showModal()" class="btn bg-blue-600 text-white">Cập nhật
                thông tin</button>
        </div>
    </section>

    <!-- Main Content -->
    <main class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Information -->
        <aside class="hidden lg:block lg:col-span-1">
            <div class="bg-white shadow-sm rounded-2xl p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 text-center">
                    Thông tin cá nhân
                </h2>

                <form wire:submit.prevent="update" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Họ tên
                        </label>
                        <input wire:model="name" type="text" value="Nguyễn Văn A"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-primary focus:border-primary transition-colors">
                    </div>
                    @error('name')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <input type="email" wire:model="email" disabled
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-50 text-gray-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Địa chỉ
                        </label>
                        <input type="text" wire:model="address"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-50 text-gray-500">
                    </div>
                    @error('address')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Số điện thoại
                        </label>
                        <input type="text" wire:model="phone"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-50 text-gray-500">
                    </div>
                    @error('phone')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Giới thiệu
                        </label>
                        <textarea type="text" wire:model="introduce"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-50 text-gray-500"> </textarea>
                    </div>
                    @error('introduce')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror

                    <button type="submit"
                        class="w-full bg-primary text-white py-2.5 px-4 rounded-lg font-medium hover:bg-primary/90 focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all duration-200"
                        wire:loading.attr="disabled" wire:target="update">
                        <span wire:loading.remove wire:target="update">
                            Cập nhật
                        </span>
                        <span wire:loading wire:target="update">
                            Đang xử lý...
                        </span>
                    </button>
                </form>
            </div>
        </aside>

        <section class="lg:col-span-2 space-y-4" id="reviews-content">
            @if (count($reviews) > 0)
                @foreach ($reviews as $review)
                    <article class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-shrink-0">
                                <img src="{{ \App\Utils\HelperFunction::generateURLImagePath($user->avatar_path) }}"
                                    onerror="this.src='{{ asset('images/avatar.png') }}'"
                                    class="w-14 h-14 rounded-full object-cover" alt="Avatar" />
                            </div>

                            <div class="flex-1 space-y-3">
                                <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                    <div class="font-semibold text-gray-900">
                                        {{ $user->name }}
                                        <span class="text-gray-500">› {{ $review->store->name }}</span>
                                    </div>
                                    <time class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($review->created_at)->locale('vi')->diffForHumans() }}
                                    </time>
                                </header>

                                <div class="flex flex-wrap gap-4 text-sm">
                                    <div class="relative inline-block group">
                                        <div
                                            class="size-8 rounded-full bg-green-600 flex items-center justify-center text-xs text-white">
                                            <b>
                                                {{ \App\Utils\HelperFunction::avgRatingReview(
                                                    location: $review->rating_location,
                                                    space: $review->rating_space,
                                                    quality: $review->rating_quality,
                                                    serve: $review->rating_serve,
                                                ) }}
                                            </b>
                                        </div>

                                        <!-- Popover -->
                                        <div class="absolute left-1/2 bottom-10 mt-2 -translate-x-1/2
                                           w-56 rounded-xl bg-white shadow-lg
                                           z-10
                                           p-3 text-sm
                                           opacity-0 scale-95 pointer-events-none
                                           transition duration-150 ease-out
                                           group-hover:opacity-100 group-hover:scale-100 group-hover:pointer-events-auto"
                                            role="tooltip">
                                            <div class="flex flex-col gap-2">
                                                <div class="flex flex-col">
                                                    @foreach ($ratingLabel as $field => $label)
                                                        <div class="flex items-center gap-2 mb-2">
                                                            <span
                                                                class="w-24 text-sm text-gray-600">{{ $label }}:</span>
                                                            <div class="rating rating-sm">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <input type="radio" disabled
                                                                        class="mask mask-star-2 bg-green-600"
                                                                        value="{{ $i }}"
                                                                        {{ $review->$field == $i ? 'checked' : '' }} />
                                                                @endfor
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <!-- Mũi tên -->
                                            <div
                                                class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-0 h-0
                                                border-l-8 border-l-transparent
                                                border-r-8 border-r-transparent
                                                border-t-8 border-b-white">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <blockquote class="text-gray-800 leading-relaxed">
                                    {{ $review->review }}
                                </blockquote>

                                @if (count($review->reviewImages) > 0)
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                                        @foreach ($review->reviewImages as $image)
                                            <div class="col-span-1">
                                                <img src="{{ \App\Utils\HelperFunction::generateURLImagePath($image->image_path) }}"
                                                    alt="{{ $image->image_name }}"
                                                    onerror="this.src='{{ asset('images/no-image.jpg') }}'"
                                                    class="w-full aspect-square object-cover rounded-2xl" />
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                            </div>
                        </div>
                    </article>
                @endforeach
                <div class="mt-6">
                    {{ $reviews->links() }}
                </div>
            @endif

            <div class="text-center py-12 text-gray-500" id="no-reviews">
                <i class="fas fa-comment-slash text-4xl mb-4"></i>
                <p class="text-lg">Chưa có đánh giá nào</p>
            </div>
        </section>

        <section class="lg:col-span-2 space-y-4 " id="saved-content">
            @if ($storesSaved)
                @foreach ($storesSaved as $store)
                    <div wire:key="store-{{ $store->id . time() }}">
                        <livewire:search-store.card-store :store="$store" :key="$store->id . time()" />
                    </div>
                @endforeach
            @else
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-bookmark text-4xl mb-4"></i>
                    <p class="text-lg">Chưa có nội dung đã lưu</p>
                </div>
            @endif
        </section>
    </main>
    <dialog id="avatar_upload" class="modal modal-bottom sm:modal-middle " wire:ignore.self>
        <div class="modal-box bg-white">
            <h3 class="text-lg font-bold mb-4">Cập nhật ảnh đại diện</h3>

            <div class="space-y-4">
                <div class="flex justify-center">
                    @if ($avatar_preview)
                        <div class="relative">
                            <img src="{{ $avatar_preview }}"
                                class="w-32 h-32 rounded-full object-cover border-4 border-gray-200" alt="Preview">
                            <button wire:click="removeAvatar"
                                class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full text-xs hover:bg-red-600 transition-colors">
                                ×
                            </button>
                        </div>
                    @else
                        <img src="{{ \App\Utils\HelperFunction::generateURLImagePath($user->avatar_path) }}"
                            onerror="this.src='{{ asset('images/avatar.png') }}'"
                            class="w-32 h-32 rounded-full object-cover border-4 border-gray-200" alt="Current Avatar">
                    @endif
                </div>

                <div id="drop-zone"
                    class="relative border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer group">
                    <input type="file" id="file-input" wire:model="avatar" accept="image/*"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />

                    <div class="space-y-3">
                        <p class="text-lg font-medium text-gray-700">Kéo thả ảnh vào đây</p>
                        <p class="text-xs text-gray-400">PNG, JPG, GIF tối đa 20MB</p>
                    </div>

                    <div id="drag-overlay"
                        class="absolute inset-0 bg-primary bg-opacity-10 border-primary rounded-lg hidden items-center justify-center">
                        <div class="text-center">
                            <p class="text-primary font-semibold text-lg">Thả ảnh vào đây</p>
                        </div>
                    </div>
                </div>

                @error('avatar')
                    <div class="text-red-500 text-sm text-center">{{ $message }}</div>
                @enderror

                <div wire:loading wire:target="avatar" class="text-center">
                    <span class="loading loading-spinner loading-md"></span>
                    <p class="text-sm text-gray-500">Đang xử lý...</p>
                </div>
            </div>

            <div class="modal-action">
                <button wire:click="uploadAvatar" wire:loading.attr="disabled" wire:target="uploadAvatar"
                    class="btn bg-blue-600 text-white" @disabled(!$avatar_ready)>
                    <span wire:loading.remove wire:target="uploadAvatar">Cập nhật</span>
                    <span wire:loading wire:target="uploadAvatar" class="loading loading-spinner loading-sm"></span>
                </button>

                <form method="dialog">
                    <button class="btn">Hủy</button>
                </form>
            </div>
        </div>
    </dialog>
    <dialog id="update_info_modal" class="modal modal-bottom sm:modal-middle lg:hidden" wire:ignore.self>
        <div class="modal-box bg-white">
            <h2 class="text-xl font-semibold mb-4">Thông tin cá nhân</h2>
            <form wire:submit.prevent="update" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Họ tên
                    </label>
                    <input wire:model="name" type="text" value="Nguyễn Văn A"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-primary focus:border-primary transition-colors">
                </div>
                @error('name')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input type="email" wire:model="email" disabled
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-50 text-gray-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Địa chỉ
                    </label>
                    <input type="text" wire:model="address"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-50 text-gray-500">
                </div>
                @error('address')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Số điện thoại
                    </label>
                    <input type="text" wire:model="phone"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-50 text-gray-500">
                </div>
                @error('phone')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Giới thiệu
                    </label>
                    <textarea type="text" wire:model="introduce"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-50 text-gray-500"> </textarea>
                </div>
                @error('introduce')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
                <button type="submit" class="btn bg-blue-600 w-full text-white">Cập nhật</button>
            </form>
            <form method="dialog" class="modal-action">
                <button class="btn">Đóng</button>
            </form>
        </div>
    </dialog>
    <script>
        document.addEventListener('livewire:init', () => {
            const dropZone = document.getElementById('drop-zone');
            const fileInput = document.getElementById('file-input');
            const dragOverlay = document.getElementById('drag-overlay');

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(name => {
                dropZone.addEventListener(name, e => {
                    preventDefaults(e);
                    dragOverlay.classList.remove('hidden');
                    dragOverlay.classList.add('flex');
                }, false);
            });

            ['dragleave', 'drop'].forEach(name => {
                dropZone.addEventListener(name, e => {
                    preventDefaults(e);
                    dragOverlay.classList.add('hidden');
                    dragOverlay.classList.remove('flex');
                }, false);
            });

            dropZone.addEventListener('drop', e => {
                preventDefaults(e);
                const files = e.dataTransfer.files;
                if (!files || files.length === 0) return;

                const file = files[0];
                if (!file.type.startsWith('image/')) {
                    alert('Vui lòng chọn file hình ảnh!');
                    return;
                }

                const dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;

                fileInput.dispatchEvent(new Event('change', {
                    bubbles: true
                }));
            }, false);

            Livewire.on('avatarUploaded', () => {
                const dlg = document.getElementById('avatar_upload');
                if (dlg && typeof dlg.close === 'function') {
                    dlg.close();
                }
            });

            function showTab(tab) {
                const reviews = document.getElementById('reviews-content');
                const saved = document.getElementById('saved-content');
                const tabs = document.querySelectorAll('.tab-btn');

                reviews.classList.add('hidden');
                saved.classList.add('hidden');
                tabs.forEach(t => t.classList.remove('bg-green-600', 'text-white', 'active'));

                if (tab === 'saved') {
                    saved.classList.remove('hidden');
                    document.querySelector('[data-tab="saved"]').classList.add('bg-green-600',
                        'text-white', 'active');
                } else {
                    reviews.classList.remove('hidden');
                    document.querySelector('[data-tab="reviews"]').classList.add('bg-green-600',
                        'text-white', 'active');
                }
            }

            // Khi load trang
            showTab(location.hash.replace('#', '') || 'reviews');

            // Khi click link tab
            window.addEventListener('hashchange', () => {
                showTab(location.hash.replace('#', '') || 'reviews');
            });
        });
    </script>
</div>
