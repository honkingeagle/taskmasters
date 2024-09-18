<nav class="flex justify-end flex-1 ">
    @auth

        <div class="relative inline-block">
            <div class="absolute transform translate-x-2 translate-y-2 bg-black rounded-md inset-1"></div>
            <button
                wire:navigate
                href="{{ url('/dashboard') }}"
                class="relative px-4 py-2 font-bold text-black bg-white border-2 border-black rounded-md" wire:navigate>
                {{ __('Dashboard ;') }}
            </button>
        </div>
    @else
        <div class="space-x-1 md:space-x-6">

            <a
            href="{{ route('login') }}"
            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
        >
            Log in
        </a>

        @if (Route::has('register'))
            <div class="relative inline-block">
                <div class="absolute transform translate-x-2 translate-y-2 bg-black rounded-md -inset-0"></div>
                <button
                    href="{{route('register')}}"
                    class="relative px-4 py-2 font-bold text-black bg-white border-2 border-black rounded-md" wire:navigate>
                    {{ __('Register') }}
                </button>
            </div>
        @endif
        </div>
    @endauth
</nav>
