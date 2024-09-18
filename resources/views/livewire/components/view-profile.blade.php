<?php

use App\Models\User;
use App\Models\Review;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\Attributes\Computed;
use Illuminate\Database\Eloquent\Collection;

new class extends Component {
    public Collection $reviews;

    public User $user;

    public ?float $averageRating = null;

    public function mount(): void
    {
        $this->getReviews();
    }

    #[Computed]
    #[On('review-created')]
    #[On('review-updated')]
    public function getReviews(): void
    {
        $this->reviews = Review::where('reviewee_id', $this->user->id)->get();

        $this->averageRating = Review::where('reviewee_id', $this->user->id)->avg('rating');
    }
}; ?>

<div class="max-w-2xl mx-2 space-y-4 md:mx-auto">
    <section class="p-4 mt-8 bg-white rounded-md shadow md:p-8">
        <header class="space-y-6">
            <div class="space-y-1">
                <div class="flex items-center space-x-1">
                    <h2 class="text-lg font-bold text-gray-900">
                        {{ $user->name }}
                    </h2>

                    @if ($user->role->name === 'Client')
                        <sup class="p-1 text-xs bg-green-200 rounded-md">
                            {{ __('client') }}
                        </sup>
                    @else
                        <sup class="p-1 text-xs bg-green-200 rounded-md">
                            {{ __('freelancer') }}
                        </sup>
                    @endif
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
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
                        <p class="text-sm font-bold text-gray-900 ms-2 dark:text-white">
                            {{ $averageRating ? $averageRating : '0.0' }}
                        </p>
                        <span class="w-1 h-1 mx-1.5 bg-gray-500 rounded-full dark:bg-gray-400"></span>
                        <div class="text-sm font-medium text-gray-900 underline hover:no-underline dark:text-white">
                            {{ $reviews->count() }} reviews
                        </div>
                    </div>
                    @if (auth()->user()->is($user))
                        <a class="text-sm underline" href="{{ route('update-profile') }}" wire:navigate>
                            {{ __('update profile') }}
                        </a>
                    @endif
                </div>

            </div>

            <div class="space-y-1 text-sm">
                <div class="mb-1">
                    {{ $user->email }}
                </div>

                @if ($user->client)
                    <div class="flex items-center space-x-2">
                        <div>
                            {{ __('heads :: the ') }}
                        </div>

                        <x-badge flat amber :label="$user->client->organization" />

                        <div>
                            {{ __(' organization') }}
                        </div>
                    </div>
                @elseif ($user->freelancer)
                    <div class="space-y-4">
                        <div class="flex items-center space-x-2">
                            @if ($user->freelancer->hourly_rate)
                                <div>
                                    {{ __('works for ') }}
                                </div>

                                <x-badge flat amber :label="$user->freelancer->hourly_rate . __(' / hr')" />
                            @endif
                        </div>

                        @if ($user->freelancer->description)
                            <p>
                                {{ $user->freelancer->description }}
                            </p>
                        @endif

                        @if ($user->freelancer->skills->isNotEmpty())
                            <div class="flex flex-wrap gap-2">
                                @foreach ($user->freelancer->skills as $skill)
                                    <div class="p-4 bg-green-200 rounded-md shadow-md max-w-fit"
                                        wire:key="{{ $skill->id }}">
                                        {{ $skill->name }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </header>

        <livewire:components.rating :$user />
    </section>

    @if (!auth()->user()->is($user) && $reviews->count() < 1)
        <livewire:reviews.create :$user />
    @endif

    @if ($reviews->count() > 0)
        <livewire:reviews.list :$reviews :$user />
    @endif
</div>
