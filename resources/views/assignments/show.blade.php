<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Assignment') }}
        </h2>
    </x-slot>

    <div class="max-w-3xl px-2 py-6 mx-auto md:text-lg lg:p-8">
        <livewire:assignments.show :$assignment>

            <div class="mt-10">
                <h2 class="mb-4 text-2xl font-bold">{{ __('Bids') }}</h2>

                @if ($assignment->bids->isNotEmpty())
                    <div>
                        @foreach ($assignment->bids as $bid)
                            <livewire:assignments.bids :$bid :key="$bid->id"/>
                        @endforeach
                    </div>
                @else
                    <div
                        class="flex flex-col items-center my-4 space-y-8 text-center bg-white border border-gray-300 rounded-md shadow-xl p-14 md:max-w-md">
                        <x-heroicons::outline.clipboard-document-list class="w-8 h-8" />
                        <p>
                            {{ __('There\'re no bids at the moment!') }}
                        </p>
                    </div>
                @endif
            </div>
    </div>
</x-app-layout>
