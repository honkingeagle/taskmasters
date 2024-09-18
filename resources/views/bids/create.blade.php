<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Create proposal') }}
        </h2>
    </x-slot>

    <div class="max-w-3xl px-2 py-6 mx-auto md:text-lg lg:p-8">
        <livewire:bids.create :$assignment />
    </div>
</x-app-layout>
