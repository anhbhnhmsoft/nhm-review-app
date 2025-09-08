<main>
    <div class="fixed inset-0 h-full bg-gradient-to-tl from-green-400 to-indigo-900 w-full py-16 px-4">
        <!--- more free and premium Tailwind CSS components at https://tailwinduikit.com/ --->
        <div class="flex flex-col items-center justify-center">
            <img src="{{\App\Utils\HelperFunction::generateURLImagePath($this->logo_app->config_value)}}" alt="{{config('app.name')}}">

            <div class="bg-white shadow rounded lg:w-1/3  md:w-1/2 w-full p-10 mt-16">
                <p tabindex="0" class="focus:outline-none text-2xl font-extrabold leading-6 text-gray-800">Đăng nhập</p>
                <p tabindex="0" class="focus:outline-none text-sm mt-4 font-medium leading-none text-gray-500">Không có tài khoản ư?
                    <a href="{{route('frontend.register')}}"   class="hover:text-gray-500 focus:text-gray-500 focus:outline-none focus:underline hover:underline text-sm font-medium leading-none  text-gray-800 cursor-pointer">
                        Đăng ký ngay
                    </a>
                </p>
                <form class="space-y-2">
                    <fieldset class="fieldset bg-transparent">
                        <legend class="fieldset-legend">Email</legend>
                        <label class="input w-full">
                            <input type="email" class="grow" placeholder="email@email.com" />
                            <i class="fa-solid fa-envelope text-xl opacity-50"></i>
                        </label>
                    </fieldset>

                    <fieldset class="fieldset bg-transparent">
                        <legend class="fieldset-legend">Email</legend>
                        <label class="input w-full">
                            <input type="email" class="grow" placeholder="email@email.com" />
                            <i class="fa-solid fa-envelope text-xl opacity-50"></i>
                        </label>
                    </fieldset>

                    <fieldset class="fieldset bg-transparent">
                        <legend class="fieldset-legend">Mật khẩu</legend>
                        <label class="input w-full" x-data="{ show: false }">
                            <input type="email"
                                   x-bind:type="show ? 'text' : 'password'"
                                   class="grow" placeholder="Mật khẩu của bạn" />
                            <!-- Toggle button -->
                            <button
                                type="button"
                                @click="show = !show"
                                :aria-pressed="show.toString()"
                                :title="show ? 'Ẩn mật khẩu' : 'Hiện mật khẩu'"
                                class="text-xl opacity-50 cursor-pointer hover:animate-bounce"
                            >
                                <!-- Eye / Eye-slash -->
                                <i x-show="!show" class="fa-solid fa-lock"></i>
                                <i x-show="show"  class="fa-solid fa-lock-open"></i>
                            </button>
                        </label>
                    </fieldset>
                    <div class="mt-8">
                        <button role="button" class="focus:ring-2 focus:ring-offset-2 focus:ring-indigo-700 text-sm font-semibold leading-none text-white focus:outline-none bg-indigo-700 border rounded hover:bg-indigo-600 py-4 w-full">Create my account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
