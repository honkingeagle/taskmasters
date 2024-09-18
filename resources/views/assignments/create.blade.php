<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Create assignment') }}
        </h2>
    </x-slot>

    <div class="max-w-xl py-6 mx-auto md:text-lg lg:p-8">
        <livewire:assignments.create>
    </div>
</x-app-layout>
