<?php

use App\Models\User;
use App\Models\Review;
use Livewire\Volt\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;

new class extends Component {
    public Review $review;

    public User $user;

    #[Validate('required|string|max:255')]
    public string $comment = '';

    #[Validate('required|integer|min:1|max:5')]
    public int $rating;

    #[Locked]
    public int $stars = 5;

    public function mount(): void
    {
        $this->rating = $this->review->rating;

        $this->comment = $this->review->comments;
    }

    public function changeRating(int $rating): void
    {
        $this->rating = $rating;
    }

    public function update(): void
    {
        $this->authorize($this->review);

        $this->validate();

        $this->review->update([
            'reviewer_id' => auth()->user()->id,
            'reviewee_id' => $this->user->id,
            'rating' => $this->rating,
            'comments' => $this->comment,
        ]);

        $this->dispatch('review-updated');
    }
}; ?>

<div class="p-4 space-y-4 md:p-8">
    <form wire:submit="update" class="">
        <div class="my-4 space-y-2">
            <div>
                {{ __('Rating') }}
            </div>

            <div>
                <div class="flex items-center space-x-1">
                    @for ($i = 0; $i < $stars; $i++)
                        <div wire:key="{{ $i + 1 }}" wire:click='changeRating({{ $i + 1 }})'>
                            <svg width="30" height="30" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_12042_8589)">
                                    <path
                                        d="M9.10326 2.31699C9.47008 1.57374 10.5299 1.57374 10.8967 2.31699L12.7063 5.98347C12.8519 6.27862 13.1335 6.48319 13.4592 6.53051L17.5054 7.11846C18.3256 7.23765 18.6531 8.24562 18.0596 8.82416L15.1318 11.6781C14.8961 11.9079 14.7885 12.2389 14.8442 12.5632L15.5353 16.5931C15.6754 17.41 14.818 18.033 14.0844 17.6473L10.4653 15.7446C10.174 15.5915 9.82598 15.5915 9.53466 15.7446L5.91562 17.6473C5.18199 18.033 4.32456 17.41 4.46467 16.5931L5.15585 12.5632C5.21148 12.2389 5.10393 11.9079 4.86825 11.6781L1.94038 8.82416C1.34687 8.24562 1.67438 7.23765 2.4946 7.11846L6.54081 6.53051C6.86652 6.48319 7.14808 6.27862 7.29374 5.98347L9.10326 2.31699Z"
                                        fill="{{ $i + 1 <= $rating ? '#FBBF24' : '#4E4123' }}" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_12042_8589">
                                        <rect width="20" height="20" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </div>
                    @endfor
                    <x-input-error :messages="$errors->get('rating')" class="mt-2" />
                </div>
            </div>
        </div>

        <div class="space-y-2">
            <x-input-label :value="__('Comment')" />
            <textarea wire:model="comment" placeholder="{{ __('Leave a comment...') }}"
                class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
            <x-input-error :messages="$errors->get('comment')" class="mt-2" />
        </div>

        <x-primary-button class="mt-4">
            {{ __('Post') }}
        </x-primary-button>
    </form>
</div>
