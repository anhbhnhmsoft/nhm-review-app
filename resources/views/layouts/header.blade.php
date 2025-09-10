@php use App\Utils\Constants\ConfigName; @endphp
<div x-data="{
    async findNearby() {
        try {
            if ($GeoPlugin.hasStoredLocation()) {
                window.location.href = '{{ route('frontend.search-store') }}?sortBy=distance';
                return;
            }
            
            const location = await $GeoPlugin.getCurrentLocation();
            $GeoPlugin.storeLocation(location.lat, location.lng);
            window.location.href = '{{ route('frontend.search-store') }}?sortBy=distance';
            
        } catch (error) {
            alert('Lỗi: ' + error.message);
        }
    }
}">
    <header class="bg-white d_section">
        <div class="container flex py-4 lg:py-6 justify-between items-center w-full">
            <div class="logo">
                <a href="{{ route('dashboard') }}">
                    @if (isset($configs[ConfigName::LOGO->value]))
                        <img src="{{ $configs[ConfigName::LOGO->value] }}" alt="Logo"
                            class="h-12 w-full object-contain">
                    @else
                        <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="h-16 w-full object-contain">
                    @endif
                </a>
            </div>
            <div class="hidden lg:flex items-center gap-4">
                <div class="inline-flex items-center gap-4">
                    {{-- Tìm xung quanh --}}
                    <button @click="findNearby()" class="inline-flex items-center gap-2 group">
                        <i class="fa-solid fa-location-dot text-blue-500 text-xl group-hover:animate-bounce"></i>
                        <span class="uppercase font-bold text-base transition-colors duration-300 group-hover:text-blue-500">Tìm xung quanh</span>
                    </button>
                    {{-- Thông báo --}}
                    {{-- <a href="#" class="inline-flex items-center gap-2 group">
                        <i class="fa-solid fa-bell text-red-500 text-xl group-hover:animate-bounce"></i>
                        <span class="uppercase font-bold text-base transition-colors duration-300 group-hover:text-red-500">Thông báo</span>
                    </a> --}}
                </div>
                <div class="inline-flex items-center gap-2">
                    <a href="{{ route('frontend.search-store') }}" class="btn btn-primary-green text-white text-base font-medium rounded-lg">
                        Viết Review
                    </a>
                    @if (auth('web')->check())
                        <div class="dropdown dropdown-end">
                            <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                                <div class="w-12 rounded-full">
                                    <img alt="Avatar"
                                        src="{{ \App\Utils\HelperFunction::generateURLImagePath(auth('web')->user()['avatar_path']) }}"
                                        loading="lazy" onerror="this.src='{{ asset('images/avatar.png') }}'" />
                                </div>
                            </div>
                            <ul tabindex="0"
                                class="menu menu-md dropdown-content bg-white rounded-box z-1 mt-3 w-52 p-2 shadow">
                                <li>
                                    <a class="justify-between" href="{{ route('frontend.profile') }}#review">
                                        Đánh giá của tôi
                                    </a>
                                </li>
                                <li><a href="{{ route('frontend.profile') }}#saved">Địa điểm đã lưu</a></li>
                                <li><a href="{{ route('frontend.logout') }}">Đăng xuất</a></li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('frontend.login') }}"
                            class="btn btn-primary-blue text-white text-base font-medium rounded-lg">
                            Đăng nhập
                        </a>
                    @endif

                </div>
            </div>
            {{-- mobile --}}
            <div class="block lg:hidden">
                <div x-data="{ open: false }">
                    <button @click="open = true" class="px-3 py-2 bg-transparent text-green-600">
                        <i class="fa-solid fa-bars text-2xl"></i>
                    </button>
                    <!-- Overlay -->
                    <div x-show="open" x-transition.opacity class="fixed inset-0 z-40 bg-black/50"
                        @click="open = false"></div>

                    <!-- Panel -->
                    <aside x-show="open" x-transition:enter="transform transition ease-out duration-300"
                        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                        x-transition:leave="transform transition ease-in duration-200"
                        x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                        class="fixed inset-y-0 right-0 z-50 w-full max-w-md bg-white shadow-xl"
                        @click.outside="open = false">
                        <!-- Header -->
                        <div class="flex items-center justify-between px-4 py-3 border-b">
                            @if (auth('web')->check())
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full overflow-hidden">
                                        <img alt="Avatar"
                                            src="{{ \App\Utils\HelperFunction::generateURLImagePath(auth('web')->user()['avatar_path']) }}"
                                            loading="lazy" onerror="this.src='{{ asset('images/avatar.png') }}'"
                                            class="w-full h-full object-cover" />
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ auth('web')->user()->name }}</p>
                                        <p class="text-sm text-gray-500">{{ auth('web')->user()->email }}</p>
                                    </div>
                                </div>

                                {{-- Nút đăng xuất --}}
                                <form method="GET" action="{{ route('frontend.logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="ml-3 px-3 py-1 text-sm font-medium text-white bg-red-500 rounded-md hover:bg-red-600 transition">
                                        Đăng xuất
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('frontend.login') }}" class="btn btn-primary-green">
                                    Đăng nhập
                                </a>
                            @endif

                            {{-- Nút đóng modal/menu --}}
                            <button @click="open = false"
                                class="inline-flex items-center justify-center rounded-full border p-2 hover:bg-gray-100"
                                aria-label="Đóng">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>


                        <!-- Nội dung scroll được -->
                        <div class="h-[calc(100vh-57px)] overflow-y-auto p-4">
                            <nav class="flex flex-col gap-4 py-4">

                                <a href="{{ route('frontend.profile') }}#review" class="text-base font-bold ">Đánh giá</a>

                                <a href="{{ route('frontend.profile') }}#saved" class="text-base font-bold ">Đã lưu</a>

                                <button @click="findNearby()" class="text-base font-bold text-left flex items-center gap-2">
                                    <i class="fa-solid fa-location-dot text-blue-500"></i>
                                    Tìm xung quanh
                                </button>
                                <a href="{{ route('frontend.search-store', ['sortBy' => '']) }}" class="text-base font-bold ">
                                    Mới nhất
                                </a>
                                <a href="{{ route('frontend.search-store', ['sortBy' => 'rating']) }}" class="text-base font-bold ">
                                    Địa điểm uy tín
                                </a>    
                                <a href="{{ route('frontend.articles.promotion') }}" class="text-base font-bold ">
                                    Khuyến mãi hot
                                </a>
                                {{-- <a href="#" class="text-base font-bold ">
                                    Video
                                </a> --}}
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
            <a href="{{ route('frontend.search-store', ['sortBy' => '']) }}" class="text-base font-bold text-white">
                Mới nhất
            </a>
            <a href="{{ route('frontend.search-store', ['sortBy' => 'rating']) }}" class="text-base font-bold text-white">
                Địa điểm uy tín
            </a>
            <a href="{{ route('frontend.articles.promotion') }}" class="text-base font-bold text-white">
                Khuyến mãi hot
            </a>
            {{-- <a href="#" class="text-base font-bold text-white">
                Video
            </a> --}}
            <a href="{{ route('frontend.news') }}" class="text-base font-bold text-white">
                Tin tức & Cẩm nang
            </a>
        </nav>
    </div>

</div>
