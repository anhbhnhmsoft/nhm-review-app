<div id="reviews">
    @php
        $ratingLabel = [
            'rating_location' => 'Vị trí',
            'rating_space'    => 'Không gian',
            'rating_quality'  => 'Chất lượng',
            'rating_serve'    => 'Phục vụ',
        ];
    @endphp
    @if($reviews->total() > 0)
        <div class="flex flex-col gap-4">
            @foreach ($reviews as $review)
                <div class="grid grid-cols-10 gap-4">
                    <div class="flex items-start justify-center col-span-2 lg:col-span-1">
                        @if($review->user->avatar_path)
                            <div class="avatar">
                                <div class="w-full rounded-full">
                                    <img src="{{\App\Utils\HelperFunction::generateURLImagePath($review->user->avatar_path)}}" alt="Avatar Người dùng"/>
                                </div>
                            </div>
                        @elseif($review->is_anonymous)
                            <div class="avatar avatar-placeholder w-full">
                                <div class="bg-neutral text-neutral-content w-full rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                </div>
                            </div>
                        @else
                            <div class="avatar avatar-placeholder w-full">
                                <div class="bg-gray-400 text-neutral-content w-full rounded-full">
                                    <span class="text-2xl uppercase">{{\Illuminate\Support\Str::substr($review->user->name,0,1)}}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="card card-md col-span-8 lg:col-span-9 bg-gray-100 relative">
                        <div class="absolute top-[30px] left-[-12px] w-0 h-0 border-l-[8px] border-l-transparent border-r-[8px] border-r-transparent border-b-[8px] border-b-gray-100 -translate-y-1/2 -rotate-90"></div>
                        <div class="card-body px-4 py-2">
                            <div class="flex items-center justify-between">
                                <div class="flex flex-col">
                                    <h3 class="capitalize text-lg font-medium">
                                        @if($review->is_anonymous)
                                            <span class="italic">Người dùng ẩn danh</span>
                                        @else
                                            {{$review->user->name}}
                                        @endif
                                    </h3>
                                    <p class="text-sm text-gray-400">{{\App\Utils\HelperFunction::humanReviewTime($review->created_at)}}</p>
                                </div>
                                {{-- Rating --}}
                                <div class="relative inline-block group">
                                    <div
                                        class="size-8 rounded-full bg-green-600 flex items-center justify-center text-xs text-white"
                                    >
                                        <b>
                                            {{\App\Utils\HelperFunction::avgRatingReview(
                                            location: $review->rating_location,
                                            space: $review->rating_space,
                                            quality: $review->rating_quality,
                                            serve: $review->rating_serve
                                        )}}
                                        </b>
                                    </div>

                                    <!-- Popover -->
                                    <div
                                        class="absolute left-1/2 bottom-10 mt-2 -translate-x-1/2
                                           w-56 rounded-xl bg-white shadow-lg
                                           z-10
                                           p-3 text-sm
                                           opacity-0 scale-95 pointer-events-none
                                           transition duration-150 ease-out
                                           group-hover:opacity-100 group-hover:scale-100 group-hover:pointer-events-auto"
                                        role="tooltip"
                                    >
                                        <div class="flex flex-col gap-2">
                                            <div class="flex flex-col">
                                                @foreach ($ratingLabel as $field => $label)
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <span class="w-24 text-sm text-gray-600">{{ $label }}:</span>
                                                        <div class="rating rating-sm">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <input
                                                                    type="radio"
                                                                    disabled
                                                                    class="mask mask-star-2 bg-green-600"
                                                                    value="{{ $i }}"
                                                                    {{ $review->$field == $i ? 'checked' : '' }}
                                                                />
                                                            @endfor
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Mũi tên -->
                                        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-0 h-0
                                                border-l-8 border-l-transparent
                                                border-r-8 border-r-transparent
                                                border-t-8 border-b-white"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="divider h-0 my-2"></div>
                            <p class="text-sm">
                                @if(!empty(trim($review->review)))
                                    {{$review->review}}
                                @else
                                    <span class="italic text-gray-400">Người dùng không bình luận gì</span>
                                @endif
                            </p>
                            @if($review->reviewImages->count() > 0)
                                <div class="flex items-center gap-2 flex-wrap">
                                    @foreach($review->reviewImages as $image)
                                        <a
                                            data-fancybox
                                            data-src="{{\App\Utils\HelperFunction::generateURLImagePath($image->image_path)}}"
                                        >
                                            <img alt="Người dùng đánh giá" src="{{\App\Utils\HelperFunction::generateURLImagePath($image->image_path)}}"
                                                 class="w-24 h-24 cursor-pointer shadow-md object-cover rounded-md"/>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="lg:ms-8 mt-8">
            {{ $reviews->links(data: ['scrollTo' => false]) }}
        </div>
    @else
        <div class="flex items-center justify-center flex-col gap-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>
            </svg>
            <h2 class="text-base font-medium">Chưa có đánh giá nào cho địa điểm này. Hãy là người đầu tiên làm chuyện
                ấy!</h2>
        </div>
    @endif
</div>
