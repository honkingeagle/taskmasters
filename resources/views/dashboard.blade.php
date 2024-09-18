<x-app-layout>
    <x-slot name="header">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                @if (auth()->user()->role->name === "Client")
                    {{ __('Assignments') }}
                @else
                    {{ __('Bids') }}
                @endif
            </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if (auth()->user()->role->name === "Client")
                <livewire:assignments.list />
            @else
                <livewire:bids.my-bids />
            @endif
        </div>
    </div>
</x-app-layout>
