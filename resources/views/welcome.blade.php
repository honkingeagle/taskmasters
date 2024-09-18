<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>TaskMasters</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <wireui:scripts />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased tracking-wider">
            <div class="flex flex-col w-full min-h-screen">
                <header class="w-full px-2 text-md bg-gradient-to-r from-[#EEEFF5] to-white md:px-0">
                    <div class="flex items-center justify-between max-w-4xl py-4 mx-auto md:py-8">
                        <div class="">
                            <x-application-logo />
                        </div>

                        @if (Route::has('login'))
                        <livewire:welcome.navigation />
                        @endif
                    </div>
                </header>

                <main class="grow">
                    <section class="bg-gradient-to-r from-[#EEEFF5] to-white flex items-center justify-center">
                        <div class="max-w-4xl mx-auto py-[8%] md:py-[2%] space-y-4 text-center px-2 md:px-0">
                            <h1 class="text-4xl font-bold md:text-6xl">{{__('Get Assignments Done')}} <span class="inline-block p-2 mt-2 bg-red-400 rounded-md">{{ __('Faster🔥')}}</span></h1>
                            <div class="pb-4 md:px-10">
                                <p class="text-md">{{__('Need help with an assignment? Our platform connects you with experts who can help you finish your work quickly and efficiently.')}}</p>
                            </div>
                        </div>
                    </section>

                    <section class="relative max-w-4xl pt-20 pb-10 mx-auto ">
                        <div class="absolute inset-x-0 flex items-center w-full p-6 mx-auto space-x-2 border border-gray-300 rounded-md shadow-xl bg-lime-200 -rotate-2 max-w-fit -top-8">
                            <div class="bg-black rounded-md rotate-2">
                                <x-heroicons::solid.megaphone class="m-2 text-white"/>
                            </div>
                            <p class="text-xl font-extrabold">
                                {{ __('>)) FIND WORK NOW') }}
                            </p>
                        </div>

                        <div>
                            <livewire:bids.all-bids />
                        </div>
                    </section>
                </main>

                <footer class="py-10 text-sm text-center bg-[#F4F4F5]">
                    <p>taskmasters &copy;{{ date('Y') }}</p>
                </footer>
          </div>
    </body>
</html>
