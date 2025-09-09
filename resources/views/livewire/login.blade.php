<main>
    <div class="lg:fixed inset-0  min-h-screen bg-gradient-to-tl from-green-400 to-indigo-900 w-full lg:py-16 py-8 px-4">
        <!--- more free and premium Tailwind CSS components at https://tailwinduikit.com/ --->
        <div class="flex flex-col items-center justify-center">

            <a href="{{ route('dashboard') }}">
                <img src="{{ \App\Utils\HelperFunction::generateURLImagePath($this->logo_app->config_value) }}"
                    alt="{{ config('app.name') }}">
            </a>

            <div class="bg-white shadow rounded lg:w-1/3  md:w-1/2 w-full p-10 mt-8">
                <p tabindex="0" class="focus:outline-none text-2xl font-extrabold leading-6 text-gray-800">Đăng nhập</p>
                <p tabindex="0" class="focus:outline-none text-sm mt-4 font-medium leading-none text-gray-500">Không có
                    tài khoản ư?
                    <a href="{{ route('frontend.register') }}"
                        class="hover:text-gray-500 focus:text-gray-500 focus:outline-none focus:underline hover:underline text-sm font-medium leading-none  text-gray-800 cursor-pointer">
                        Đăng ký ngay
                    </a>
                </p>
                <form wire:submit.prevent="login" class="space-y-2">

                    <fieldset class="fieldset bg-transparent">
                        <legend class="fieldset-legend">Email</legend>
                        <label class="input w-full">
                            <input type="email" wire:model="email" class="grow" placeholder="email@email.com" />
                            <i class="fa-solid fa-envelope text-xl opacity-50"></i>
                        </label>
                        @error('email')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    <fieldset class="fieldset bg-transparent">
                        <legend class="fieldset-legend">Mật khẩu</legend>
                        <label class="input w-full" x-data="{ show: false }">
                            <input type="password" wire:model="password" x-bind:type="show ? 'text' : 'password'"
                                class="grow" placeholder="Mật khẩu của bạn" />
                            <!-- Toggle button -->
                            <button type="button" @click="show = !show" :aria-pressed="show.toString()"
                                :title="show ? 'Ẩn mật khẩu' : 'Hiện mật khẩu'"
                                class="text-xl opacity-50 cursor-pointer hover:animate-bounce">
                                <!-- Eye / Eye-slash -->
                                <i x-show="!show" class="fa-solid fa-lock"></i>
                                <i x-show="show" class="fa-solid fa-lock-open"></i>
                            </button>
                        </label>
                        @error('password')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </fieldset>
                    <div class="mt-8">
                        <button type="submit"
                            class="focus:ring-2 focus:ring-offset-2 focus:ring-indigo-700 text-sm font-semibold leading-none text-white focus:outline-none bg-indigo-700 border rounded hover:bg-indigo-600 py-4 w-full cursor-pointer
           disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled" wire:target="login">
                            <span wire:loading.remove wire:target="login">
                                Đăng nhập
                            </span>
                            <span wire:loading wire:target="login">
                                Đang xử lý...
                            </span>
                        </button>
                    </div>
                </form>

                <div class="mt-4 text-center">
                    <a href="{{ route('dashboard') }}"
                        class="inline-block text-sm font-medium text-indigo-700 hover:underline">
                        ← Trở về trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>
