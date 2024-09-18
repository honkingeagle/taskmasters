<?php

use App\Models\User;
use App\Models\Review;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Illuminate\Database\Eloquent\Collection;

new class extends Component {
    public User $user;

    public Collection $reviews;

    public ?Review $editing = null;

    public function edit(Review $review): void
    {
        $this->editing = $review;
    }

    #[On('review-updated')]
    public function disableEditing(): void
    {
        $this->editing = null;
    }

    public function delete(Review $review): void
    {
        $this->authorize($review);

        $review->delete();

        $this->dispatch('review-created');
    }

}; ?>

<div class="mt-6 text-sm bg-white divide-y rounded-lg shadow-sm">
    @foreach ($reviews as $review)
        <div class="flex p-6 space-x-2" wire:key="{{ $review->id }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>


            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <div>
                        <a class="underline" href="{{ route('profile.show', $review->user->id) }}" wire:navigate>{{ $review->user->name}}</a>
                        <small class="ml-2 text-gray-600 text-md">{{ $review->created_at->format('j M Y, g:i a') }}</small>
                    </div>
                    @if ($review->user->is(auth()->user()))
                        <x-dropdown-laravel>
                            <x-slot name="trigger">
                                <button>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                    </svg>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link
                                wire:click="edit({{ $review->id }})"
                                >
                                    {{ __('Edit') }}
                                </x-dropdown-link>
                                <x-dropdown-link
                                wire:click="delete({{ $review->id }})"
                                >
                                    {{ __('Delete') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown-laravel>
                    @endif
                </div>
                <div class="flex items-center space-x-1">
                    @for ($i = 0; $i < $review->rating; $i++)
                        <div wire:key="{{ $i }}">
                            <svg width="15" height="15" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_12042_8589)">
                                    <path
                                        d="M9.10326 2.31699C9.47008 1.57374 10.5299 1.57374 10.8967 2.31699L12.7063 5.98347C12.8519 6.27862 13.1335 6.48319 13.4592 6.53051L17.5054 7.11846C18.3256 7.23765 18.6531 8.24562 18.0596 8.82416L15.1318 11.6781C14.8961 11.9079 14.7885 12.2389 14.8442 12.5632L15.5353 16.5931C15.6754 17.41 14.818 18.033 14.0844 17.6473L10.4653 15.7446C10.174 15.5915 9.82598 15.5915 9.53466 15.7446L5.91562 17.6473C5.18199 18.033 4.32456 17.41 4.46467 16.5931L5.15585 12.5632C5.21148 12.2389 5.10393 11.9079 4.86825 11.6781L1.94038 8.82416C1.34687 8.24562 1.67438 7.23765 2.4946 7.11846L6.54081 6.53051C6.86652 6.48319 7.14808 6.27862 7.29374 5.98347L9.10326 2.31699Z"
                                        fill="#FBBF24" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_12042_8589">
                                        <rect width="20" height="20" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </div>
                    @endfor
                </div>
                @if ($review->is($editing))
                    <livewire:reviews.edit :$review :$user :key="$review->id" />
                @else
                    <p class="mt-2 text-gray-900">{{ $review->comments }}</p>
                @endif
            </div>
        </div>
    @endforeach
</div>
