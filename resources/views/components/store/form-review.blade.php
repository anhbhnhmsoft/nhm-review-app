<div x-data="{ open: @entangle('isOpen') }">
    <template x-teleport="body">
        <div
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave-end="opacity-0"
            x-show="open"
            class="fixed inset-0 z-10 flex items-center justify-center bg-black/50"
        >
            <div
                @click.away="$wire.close()"
                class="bg-white w-full max-w-lg rounded-2xl shadow-xl max-h-[85vh] overflow-y-auto overscroll-contain p-6"
            >
                <form wire:submit.prevent="saveReview">
                    <h3 class="text-lg text-gray-500 font-medium mb-4">Xếp hạng của bạn</h3>
                    <div class="flex flex-col gap-6 mb-4">
                        <div x-data="{
                            rating: @entangle('rating_location'),
                            labels: ['Rất tệ','Tệ','Trung bình','Tốt','Tuyệt vời']
                        }" class="flex items-center">
                            <div class="flex-[1_1] font-medium">Vị trí</div>
                            <div class="flex-[2_1]">
                                <div class="rating justify-self-start">
                                    <!-- Star Inputs -->
                                    <input type="radio" name="rating_location" value="1" x-model="rating"
                                           class="mask mask-star-2 bg-green-600"
                                           aria-label="1 star"/>
                                    <input type="radio" name="rating_location" value="2" x-model="rating"
                                           class="mask mask-star-2 bg-green-600"
                                           aria-label="2 star"/>
                                    <input type="radio" name="rating_location" value="3" x-model="rating"
                                           class="mask mask-star-2 bg-green-600"
                                           aria-label="3 star"/>
                                    <input type="radio" name="rating_location" value="4" x-model="rating"
                                           class="mask mask-star-2 bg-green-600"
                                           aria-label="4 star"/>
                                    <input type="radio" name="rating_location" value="5" x-model="rating"
                                           class="mask mask-star-2 bg-green-600"
                                           aria-label="5 star" checked="checked"/>
                                </div>
                            </div>
                            <div class="flex-[1_1] justify-self-end">
                                <span x-text="labels[rating - 1]"
                                      class="badge badge-success bg-green-600 badge-lg text-base text-white"></span>
                                <!-- Hiển thị nhãn từ enum -->
                            </div>
                        </div>
                        <div x-data="{
                            rating: @entangle('rating_space'),
                            labels: ['Rất tệ','Tệ','Trung bình','Tốt','Tuyệt vời']
                        }" class="flex items-center">
                            <div class="flex-[1_1] font-medium">Không gian</div>
                            <div class="flex-[2_1]">
                                <div class="rating justify-self-start">
                                    <!-- Star Inputs -->
                                    <input type="radio" name="rating_space" value="1" x-model="rating"
                                           class="mask mask-star-2 bg-green-600"
                                           aria-label="1 star"/>
                                    <input type="radio" name="rating_space" value="2" x-model="rating"
                                           class="mask mask-star-2 bg-green-600"
                                           aria-label="2 star"/>
                                    <input type="radio" name="rating_space" value="3" x-model="rating"
                                           class="mask mask-star-2 bg-green-600"
                                           aria-label="3 star"/>
                                    <input type="radio" name="rating_space" value="4" x-model="rating"
                                           class="mask mask-star-2 bg-green-600"
                                           aria-label="4 star"/>
                                    <input type="radio" name="rating_space" value="5" x-model="rating"
                                           class="mask mask-star-2 bg-green-600"
                                           aria-label="5 star" checked="checked"/>
                                </div>
                            </div>
                            <div class="flex-[1_1] justify-self-end">
                                <span x-text="labels[rating - 1]"
                                      class="badge badge-success bg-green-600 badge-lg text-white"></span>
                                <!-- Hiển thị nhãn từ enum -->
                            </div>
                        </div>
                        <div x-data="{
                            rating: @entangle('rating_quality'),
                            labels: ['Rất tệ','Tệ','Trung bình','Tốt','Tuyệt vời']
                        }" class="flex items-center">
                            <div class="flex-[1_1] font-medium">Chất lượng</div>
                            <div class="flex-[2_1]">
                                <div class="rating justify-self-start">
                                    <!-- Star Inputs -->
                                    <input type="radio" name="rating_quality" value="1" x-model="rating"
                                           class="mask mask-star-2 bg-green-600"
                                           aria-label="1 star"/>
                                    <input type="radio" name="rating_quality" value="2" x-model="rating"
                                           class="mask mask-star-2 bg-green-600"
                                           aria-label="2 star"/>
                                    <input type="radio" name="rating_quality" value="3" x-model="rating"
                                           class="mask mask-star-2 bg-green-600"
                                           aria-label="3 star"/>
                                    <input type="radio" name="rating_quality" value="4" x-model="rating"
                                           class="mask mask-star-2 bg-green-600"
                                           aria-label="4 star"/>
                                    <input type="radio" name="rating_quality" value="5" x-model="rating"
                                           class="mask mask-star-2 bg-green-600"
                                           aria-label="5 star" checked="checked"/>
                                </div>
                            </div>
                            <div class="flex-[1_1] justify-self-end">
                                <span x-text="labels[rating - 1]"
                                      class="badge badge-success bg-green-600 badge-lg text-white"></span>
                                <!-- Hiển thị nhãn từ enum -->
                            </div>
                        </div>
                        <div x-data="{
                            rating: @entangle('rating_serve'),
                            labels: ['Rất tệ','Tệ','Trung bình','Tốt','Tuyệt vời']
                        }" class="flex items-center">
                            <div class="flex-[1_1] font-medium">Phục vụ</div>
                            <div class="flex-[2_1]">
                                <div class="rating justify-self-start">
                                    <!-- Star Inputs -->
                                    <input type="radio" name="rating_serve" value="1" x-model="rating"
                                           class="mask mask-star-2 bg-green-600"
                                           aria-label="1 star"/>
                                    <input type="radio" name="rating_serve" value="2" x-model="rating"
                                           class="mask mask-star-2 bg-green-600"
                                           aria-label="2 star"/>
                                    <input type="radio" name="rating_serve" value="3" x-model="rating"
                                           class="mask mask-star-2 bg-green-600"
                                           aria-label="3 star"/>
                                    <input type="radio" name="rating_serve" value="4" x-model="rating"
                                           class="mask mask-star-2 bg-green-600"
                                           aria-label="4 star"/>
                                    <input type="radio" name="rating_serve" value="5" x-model="rating"
                                           class="mask mask-star-2 bg-green-600"
                                           aria-label="5 star" checked="checked"/>
                                </div>
                            </div>
                            <div class="flex-[1_1] justify-self-end">
                                <span x-text="labels[rating - 1]"
                                      class="badge badge-success bg-green-600 badge-lg text-white"></span>
                                <!-- Hiển thị nhãn từ enum -->
                            </div>
                        </div>
                    </div>
                    <h3 class="text-lg text-gray-500 font-medium mb-4">Đánh giá của bạn</h3>
                    <div x-data="{ text: '', minLength: 10 }" class="mb-4">
                        <textarea
                            x-model="text"
                            name="review"
                            wire:model="review"
                            placeholder="Hãy nhập nội dung đánh giá"
                            class="textarea textarea-success w-full"
                            rows="6"></textarea>
                        <div class="mt-2 text-end text-xs italic text-gray-400">
                            <span x-text="text.length"></span> kí tự (tối thiểu <span x-text="minLength"></span> ký tự)
                        </div>
                        @error('review')
                        <div class="text-red-500 text-sm mt-1 italic">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="flex flex-col gap-2 mb-4">
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg text-gray-500 font-medium">Đánh giá ẩn danh</h3>
                            <input type="checkbox" class="toggle toggle-success" name="is_anonymous"
                                   wire:model="is_anonymous"/>
                        </div>
                        <p class="text-xs italic text-gray-400">Tên của bạn sẽ hiển thị ẩn danh trong danh mục đánh giá
                            địa điểm này</p>
                    </div>
                    <h6 class="text-lg text-gray-500 font-medium mb-4">Ảnh đính kèm</h6>
                    <div wire:ignore>
                        <input type="file" multiple name="review_files[]" id="review_files">
                    </div>
                    @error('review_files')
                        <div class="text-red-500 text-sm mt-1 italic">{{ $message }}</div>
                    @enderror
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="$wire.close()" class="btn btn-primary-gray btn-sm">Đóng</button>
                        <button type="submit" class="btn btn-primary-green btn-sm flex items-center" wire:loading.attr="disabled">
                            <span wire:loading.remove>Gửi đánh giá</span>
                            <span wire:loading>
                                <svg class="animate-spin size-3 text-white"
                                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>














