<main>
    <div class="lg:fixed inset-0  min-h-screen bg-gradient-to-tl from-green-400 to-indigo-900 w-full lg:py-16 py-8 px-4">
        <!--- more free and premium Tailwind CSS components at https://tailwinduikit.com/ --->
        <div class="flex flex-col items-center justify-center">
            <img src="{{\App\Utils\HelperFunction::generateURLImagePath($this->logo_app->config_value)}}" alt="{{config('app.name')}}">

            <div class="bg-white shadow rounded lg:w-1/3  md:w-1/2 w-full p-10 mt-8">
                <p tabindex="0" class="focus:outline-none text-2xl font-extrabold leading-6 text-gray-800">Đăng ký</p>
                <p tabindex="0" class="focus:outline-none text-sm mt-4 font-medium leading-none text-gray-500">Bạn đã có tài khoản rồi ư?
                    <a href="{{route('frontend.login')}}"   class="hover:text-gray-500 focus:text-gray-500 focus:outline-none focus:underline hover:underline text-sm font-medium leading-none  text-gray-800 cursor-pointer">
                        Đăng nhập ngay
                    </a>
                </p>
                <form wire:submit.prevent="register" class="space-y-2 mt-4">
                    <fieldset class="fieldset bg-transparent">
                        <legend class="fieldset-legend">Tên tài khoản của bạn</legend>
                        <label class="input w-full">
                            <input type="text" id="name" wire:model="name" class="grow" placeholder="Nhập tên ở đây" />
                            <i class="fa-solid fa-user text-xl opacity-50"></i>
                        </label>
                        @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </fieldset>

                    <fieldset class="fieldset bg-transparent">
                        <legend class="fieldset-legend">Email tài khoản của bạn</legend>
                        <label class="input w-full">
                            <input type="email" wire:model="email" class="grow" placeholder="Nhập email ở đây" />
                            <i class="fa-solid fa-envelope text-xl opacity-50"></i>
                        </label>
                        @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </fieldset>

                    <fieldset class="fieldset bg-transparent">
                        <legend class="fieldset-legend">Mật khẩu</legend>
                        <label class="input w-full" >
                            <input type="password"
                                    wire:model="password"
                                    name="password"
                                   class="grow" placeholder="Mật khẩu của bạn" />
                            <!-- Toggle button -->
                        </label>
                        @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </fieldset>
                    <fieldset class="fieldset bg-transparent">
                        <legend class="fieldset-legend">Xác nhận mật khẩu</legend>
                        <label class="input w-full" >
                            <input type="password"
                                    wire:model="password_confirmation"
                                    name="password_confirmation"
                                   class="grow" placeholder="Xác nhận mật khẩu của bạn" />
                            <!-- Toggle button -->
                        </label>
                    </fieldset>
                    <div class="mt-8">
                        <button role="button" type="submit" class="focus:ring-2 focus:ring-offset-2 focus:ring-indigo-700 text-sm font-semibold leading-none cursor-pointer text-white focus:outline-none bg-indigo-700 border rounded hover:bg-indigo-600 py-4 w-full">Đăng ký</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
