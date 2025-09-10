@php use App\Utils\Constants\ConfigName; @endphp
<div>
    <header class="bg-white d_section">
        <div class="container flex py-4 lg:py-6 justify-between items-center w-full">
            <div class="logo">
                <a href="{{ route('dashboard') }}">
                    @if(isset($configs[ConfigName::LOGO->value]))
                        <img src="{{ $configs[ConfigName::LOGO->value] }}" alt="Logo" class="h-12 w-full object-contain">
                    @else
                        <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="h-16 w-full object-contain">
                    @endif
                </a>
            </div>
            <div class="hidden lg:flex items-center gap-4">
                <div class="inline-flex items-center gap-4">
                    {{-- Tìm xung quanh --}}
                    <a href="#" class="inline-flex items-center gap-2 group">
                        <i class="fa-solid fa-location-dot text-blue-500 text-xl group-hover:animate-bounce"></i>
                        <span class="uppercase font-bold text-base transition-colors duration-300 group-hover:text-blue-500">Tìm xung quanh</span>
                    </a>
                    {{-- Thông báo --}}
                    <a href="#" class="inline-flex items-center gap-2 group">
                        <i class="fa-solid fa-bell text-red-500 text-xl group-hover:animate-bounce"></i>
                        <span class="uppercase font-bold text-base transition-colors duration-300 group-hover:text-red-500">Thông báo</span>
                    </a>
                </div>
                <div class="inline-flex items-center gap-2">
                    <a href="#" class="btn btn-primary-green text-white text-base font-medium rounded-lg">
                        Viết Review
                    </a>
                    @if(auth('web')->check())
                        <a href="#" class="btn btn-primary-blue text-white text-base font-medium rounded-lg">
                            hehe
                        </a>
                    @else
                        <a href="{{route('frontend.login')}}"
                           class="btn btn-primary-blue text-white text-base font-medium rounded-lg">
                            Đăng nhập
                        </a>
                    @endif

                </div>
            </div>
            {{-- mobile--}}
            <div class="block lg:hidden">
                <div x-data="{open: false}">
                    <button
                        @click="open = true"
                        class="px-3 py-2 bg-transparent text-green-600"
                    >
                        <i class="fa-solid fa-bars text-2xl"></i>
                    </button>
                    <!-- Overlay -->
                    <div
                        x-show="open"
                        x-transition.opacity
                        class="fixed inset-0 z-40 bg-black/50"
                        @click="open = false"
                    ></div>

                    <!-- Panel -->
                    <aside
                        x-show="open"
                        x-transition:enter="transform transition ease-out duration-300"
                        x-transition:enter-start="translate-x-full"
                        x-transition:enter-end="translate-x-0"
                        x-transition:leave="transform transition ease-in duration-200"
                        x-transition:leave-start="translate-x-0"
                        x-transition:leave-end="translate-x-full"
                        class="fixed inset-y-0 right-0 z-50 w-full max-w-md bg-white shadow-xl"
                        @click.outside="open = false"
                    >
                        <!-- Header -->
                        <div class="flex items-center justify-between px-4 py-3 border-b">
                            <a href="{{ route('frontend.login') }}" class="btn btn-primary-green">
                                Đăng nhập
                            </a>
                            <button
                                @click="open = false"
                                class="inline-flex items-center justify-center rounded-full border p-2 hover:bg-gray-100"
                                aria-label="Đóng"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Nội dung scroll được -->
                        <div class="h-[calc(100vh-57px)] overflow-y-auto p-4">
                            <nav class="flex flex-col gap-4 py-4">
                                <a href="#" class="text-base font-bold ">
                                    Mới nhất
                                </a>
                                <a href="#" class="text-base font-bold ">
                                    Địa điểm uy tín
                                </a>
                                <a href="{{ route('frontend.articles.promotion') }}" class="text-base font-bold ">
                                    Khuyến mãi hot
                                </a>
                                <a href="#" class="text-base font-bold ">
                                    Video
                                </a>
                                <a href="{{ route('frontend.news') }}" class="text-base font-bold ">
                                    Tin tức & Cẩm nang
                                </a>
                            </nav>
                        </div>
                    </aside>
                </div>

            </div>
        </div>
    </header>

    <div class="hidden lg:flex items-center justify-center bg-[#52ab5c]">
        <nav class="container !flex items-center justify-between gap-4 py-4">
            <a href="#" class="text-base font-bold text-white">
                Mới nhất
            </a>
            <a href="#" class="text-base font-bold text-white">
                Địa điểm uy tín
            </a>
            <a href="{{ route('frontend.articles.promotion') }}" class="text-base font-bold text-white">
                Khuyến mãi hot
            </a>
            <a href="#" class="text-base font-bold text-white">
                Video
            </a>
            <a href="{{ route('frontend.news') }}" class="text-base font-bold text-white">
                Tin tức & Cẩm nang
            </a>
        </nav>
    </div>

</div>
