<header class="h-[116px] bg-white px-[303px] pt-[25px] pb-[22px] flex justify-between items-center">
    <div class="logo">
        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="h-[69px] w-[150px] object-contain">
        </a>
    </div>
    <div class="flex items-center gap-4">
        <div class="inline-flex items-center gap-4">
            {{-- Tìm xung quanh --}}
            <a href="#" class="inline-flex items-end gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-8 text-blue-500">
                    <path fill-rule="evenodd" d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
                </svg>
                <span class="uppercase font-bold text-base">Tìm xung quanh</span>
            </a>

            {{-- Thông báo --}}
            <a href="#" class="inline-flex items-end gap-2">
                <svg width="35" height="43" viewBox="0 0 35 43"  fill="currentColor" xmlns="http://www.w3.org/2000/svg" class="size-8 text-red-500">
                    <path d="M17.5039 0C16.1208 0 15.0033 1.17972 15.0033 2.63994V2.90393C9.29895 4.12491 5.00112 9.45428 5.00112 15.8396V17.6299C5.00112 21.598 3.71958 25.4507 1.37531 28.5526L0.609511 29.5591C0.210985 30.0788 0 30.7223 0 31.3905C0 33.0075 1.24246 34.3192 2.77406 34.3192H32.2259C33.7575 34.3192 35 33.0075 35 31.3905C35 30.7223 34.789 30.0788 34.3905 29.5591L33.6247 28.5526C31.2882 25.4507 30.0067 21.598 30.0067 17.6299V15.8396C30.0067 9.45428 25.7089 4.12491 20.0045 2.90393V2.63994C20.0045 1.17972 18.887 0 17.5039 0ZM12.6591 38.2791C13.2139 40.5561 15.1753 42.239 17.5039 42.239C19.8326 42.239 21.7939 40.5561 22.3487 38.2791H12.6591Z"/>
                </svg>
                <span class="uppercase font-bold text-base">Thông báo</span>
            </a>
        </div>
        <div class="inline-flex items-center gap-2">
            <a href="#" class="btn bg-green-600 hover:bg-green-400 border-0 text-white text-base font-medium rounded-lg">
                Viết Review
            </a>
            <a href="#" class="btn bg-blue-600 hover:bg-blue-400 border-0 text-white text-base font-medium rounded-lg">
                Đăng nhập
            </a>
        </div>
    </div>
</header>
