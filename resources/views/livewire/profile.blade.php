<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <header class="bg-gradient-to-b from-[#c8e4cc] to-[#fafafa] rounded-b-2xl h-64 relative overflow-hidden">
        <div class="absolute inset-x-0 bottom-0 flex justify-center pb-6">
            <div class="relative" wire:key="avatar-{{ $user->id }}-{{ $user->updated_at }}">
                <img data-open-avatar-modal
                    src="{{ \App\Utils\HelperFunction::generateURLImagePath($user->avatar_path) }}"
                    onerror="this.src='{{ asset('images/avatar.png') }}'"
                    class="w-44 h-44 sm:w-48 sm:h-48 p-2 cursor-pointer bg-white rounded-full shadow-lg hover:shadow-xl transition-shadow duration-300"
                    alt="Avatar" />

                <button data-open-avatar-modal
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
                <button
                    class="tab-btn active px-6 py-2 text-lg cursor-pointer font-semibold rounded-md transition-all duration-200 bg-amber-600 text-white">
                    Đánh giá
                </button>
                <button
                    class="tab-btn px-6 py-2 text-lg cursor-pointer font-semibold rounded-md transition-all duration-200">
                    <a href="http://" target="_blank" rel="noopener noreferrer">Đã lưu</a>
                </button>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Information -->
        <aside class="lg:col-span-1">
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
                                    <div class="flex items-center gap-1">
                                        <span class="text-gray-600">Vị trí:</span>
                                        <div class="flex">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($review->rating_location >= $i)
                                                    <i class="fas fa-star text-yellow-400"></i>
                                                @else
                                                    <i class="far fa-star text-gray-300"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span class="text-gray-600">Không gian:</span>
                                        <div class="flex">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($review->rating_space >= $i)
                                                    <i class="fas fa-star text-yellow-400"></i>
                                                @else
                                                    <i class="far fa-star text-gray-300"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span class="text-gray-600">Chất lượng:</span>
                                        <div class="flex">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($review->rating_quality >= $i)
                                                    <i class="fas fa-star text-yellow-400"></i>
                                                @else
                                                    <i class="far fa-star text-gray-300"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span class="text-gray-600">Phục vụ:</span>
                                        <div class="flex">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($review->rating_serve >= $i)
                                                    <i class="fas fa-star text-yellow-400"></i>
                                                @else
                                                    <i class="far fa-star text-gray-300"></i>
                                                @endif
                                            @endfor
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

            <div class="text-center py-12 text-gray-500 hidden" id="no-reviews">
                <i class="fas fa-comment-slash text-4xl mb-4"></i>
                <p class="text-lg">Chưa có đánh giá nào</p>
            </div>
        </section>

        <section class="lg:col-span-2 space-y-4 hidden" id="saved-content">
            <div class="text-center py-12 text-gray-500">
                <i class="fas fa-bookmark text-4xl mb-4"></i>
                <p class="text-lg">Chưa có nội dung đã lưu</p>
            </div>
        </section>
    </main>
    <dialog id="avatar_upload" class="modal modal-bottom sm:modal-middle" wire:ignore.self>
        <div class="modal-box">
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
                    class="btn btn-primary" @disabled(!$avatar_ready)>
                    <span wire:loading.remove wire:target="uploadAvatar">Cập nhật</span>
                    <span wire:loading wire:target="uploadAvatar" class="loading loading-spinner loading-sm"></span>
                </button>

                <form method="dialog">
                    <button class="btn">Hủy</button>
                </form>
            </div>
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

            document.addEventListener('click', e => {
                const openBtn = e.target.closest('[data-open-avatar-modal]');
                if (openBtn) {
                    const dlg = document.getElementById('avatar_upload');
                    if (dlg) {
                        try {
                            dlg.showModal();
                        } catch (err) {}
                    }
                }
            });

            Livewire.on('avatarUploaded', () => {
                const dlg = document.getElementById('avatar_upload');
                if (dlg && typeof dlg.close === 'function') {
                    dlg.close();
                }
            });
        });
    </script>
</div>
