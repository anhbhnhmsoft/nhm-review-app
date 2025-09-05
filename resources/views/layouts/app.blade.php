<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @livewireStyles
        @include('layouts.head')
    </head>
    <body class="">
        @include('layouts.header')
        <div class="w-full">
            {{ $slot }}
        </div>
        @include('layouts.footer')
        <div x-data="{ showButton: false }" x-init="window.addEventListener('scroll', () => { showButton = window.scrollY > 500 })">
            <!-- Nút Scroll to Top -->
            <button x-show="showButton"
                    x-transition
                    x-transition.opacity
                    @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
                    class="fixed bottom-4 right-4 h-12 w-12 bg-white flex items-center justify-center cursor-pointer border border-slate-100 text-black p-3 rounded-full shadow-lg transition-opacity duration-300 ease-in-out z-50"
                    >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                    <path fill-rule="evenodd" d="M11.47 7.72a.75.75 0 0 1 1.06 0l7.5 7.5a.75.75 0 1 1-1.06 1.06L12 9.31l-6.97 6.97a.75.75 0 0 1-1.06-1.06l7.5-7.5Z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        @livewireScriptConfig
    </body>
</html>
