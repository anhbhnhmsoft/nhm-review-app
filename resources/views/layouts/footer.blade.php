@php use App\Utils\Constants\ConfigName; @endphp
<div class="bg-white w-full h-full">
    <div class="relative mt-32 md:mt-56 lg:mt-56 xl:mt-64 2xl:mt-72 3xl:mt-80 bg-white">
        <img src="{{asset('images/bg-footer.webp')}}" alt="Background Footer"
             class="absolute -top-20 z-0 h-auto w-full md:-top-32 xl:-top-44 2xl:-top-48 3xl:-top-60 4xl:top-[-19rem] bg-white">
        <div class="bg-[#0257ff]">
            <div class="relative z-10 mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 gap-0 md:col-span-3 lg:grid-cols-6">
                    <div class="col-span-2 md:col-span-3 lg:col-span-2 lg:pr-8">
                        @if(isset($configs[ConfigName::LOGO->value]))
                            <img src="{{ $configs[ConfigName::LOGO->value] }}" alt="Logo"
                                 class="h-8 w-auto md:h-14 2xl:h-14">
                        @else
                            <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="h-8 w-auto md:h-14 2xl:h-14">
                        @endif
                        <p class="mt-7 leading-relaxed text-white text-lg">
                            @if(isset($configs[ConfigName::MANAGING_UNIT->value]))
                                {{$configs[ConfigName::MANAGING_UNIT->value]}}
                            @endif
                        </p>
                    </div>
                    <div class="col-span-2 hidden md:col-span-3 lg:col-span-2 lg:block lg:pr-0">
                        <div class="mt-4 grid grid-cols-1 gap-x-4 md:grid-cols-2">
                            <ul class="mt-6 space-y-4">
                                <li class="font-bold text-white">Hỗ trợ khách hàng</li>
                                <li>
                                    <a href="#" class="text-white hover:link">Chính sách giải quyết khiếu nại</a>
                                </li>
                                <li>
                                    <a href="#" class="text-white hover:link">Chính sách bảo mật</a>
                                </li>
                                <li>
                                    <a href="#" class="text-white hover:link">Chính sách bảo vệ và xử lý dữ liệu cá
                                        nhân</a>
                                </li>
                                <li>
                                    <a href="#" class="text-white hover:link">Quy định đăng tin</a>
                                </li>
                            </ul>
                            <ul class="mt-6 space-y-4">
                                <li class="font-bold text-white">Về Afy</li>
                                <li>
                                    <a href="#" class="text-white hover:link">Giới thiệu</a>
                                </li>
                                <li>
                                    <a href="#" class="text-white hover:link">Điều khoản sử dụng</a>
                                </li>
                                <li>
                                    <a href="#" class="text-white hover:link">Quy chế hoạt động</a>
                                </li>
                                <li>
                                    <a href="#" class="text-white hover:link">Chính sách bảo mật</a>
                                </li>
                                <li>
                                    <a href="#" class="text-white hover:link">Trung tâm khách hàng</a>
                                </li>
                                <li>
                                    <a href="#" class="text-white hover:link">Truyền thông</a>
                                </li>
                                <li>
                                    <a href="#" class="text-white hover:link">Hỏi đáp (FAQ)</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-span-2 mt-2 md:col-span-2 lg:col-span-2 lg:ml-8 2xl:ml-12">
                        <div>
                            <p class="mt-6 text-base font-bold text-white">
                                Liên kết
                            </p>
                            <ul class="mt-2 flex items-center justify-center space-x-4">
                                @if(isset($configs[ConfigName::FACEBOOK->value]) && !empty($configs[ConfigName::FACEBOOK->value]))
                                    <a href="{{$configs[ConfigName::FACEBOOK->value]}}" target="_blank"
                                       class="flex items-center justify-center">
                                        <span
                                            class="transform text-xl text-white transition-all duration-200 hover:translate-y-[-5px] md:text-3xl lg:text-2xl 2xl:text-3xl">
                                            <i class="fa-brands fa-facebook"></i>
                                        </span>
                                    </a>
                                @endif
                                @if(isset($configs[ConfigName::YOUTUBE->value]) && !empty($configs[ConfigName::YOUTUBE->value]))
                                    <a href="{{$configs[ConfigName::YOUTUBE->value]}}" target="_blank"
                                       class="flex items-center justify-center">
                                            <span
                                                class="transform text-xl text-white transition-all duration-200 hover:translate-y-[-5px] md:text-3xl lg:text-2xl 2xl:text-3xl">
                                                <i class="fa-brands fa-youtube"></i>
                                            </span>
                                    </a>
                                @endif
                                @if(isset($configs[ConfigName::INSTAGRAM->value]) && !empty($configs[ConfigName::INSTAGRAM->value]))
                                    <a href="{{$configs[ConfigName::INSTAGRAM->value]}}" target="_blank"
                                       class="flex items-center justify-center">
                                            <span
                                                class="transform text-xl text-white transition-all duration-200 hover:translate-y-[-5px] md:text-3xl lg:text-2xl 2xl:text-3xl">
                                                <i class="fa-brands fa-instagram"></i>
                                            </span>
                                    </a>
                                @endif
                                @if(isset($configs[ConfigName::TIKTOK->value]) && !empty($configs[ConfigName::TIKTOK->value]))
                                    <a href="{{$configs[ConfigName::TIKTOK->value]}}" target="_blank"
                                       class="flex items-center justify-center">
                                            <span
                                                class="transform text-xl text-white transition-all duration-200 hover:translate-y-[-5px] md:text-3xl lg:text-2xl 2xl:text-3xl">
                                                <i class="fa-brands fa-tiktok"></i>
                                            </span>
                                    </a>
                                @endif

                            </ul>
                        </div>
                    </div>
                </div>
                <hr class="mb-10 mt-6 border-gray-200"/>
                @if(isset($configs[ConfigName::FOOTER_COPYRIGHT->value]))
                    <p class="-mt-8 py-4 text-center text-xs text-white md:text-sm 2xl:text-sm">
                        {{$configs[ConfigName::FOOTER_COPYRIGHT->value]}}
                    </p>
                @endif

            </div>
        </div>

    </div>
</div>
