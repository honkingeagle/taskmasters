<?php

use App\Models\Assignment;
use App\Models\Bid;
use Livewire\Volt\Component;
use Livewire\Attributes\Computed;
use App\Models\AssignmentStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;

new class extends Component {
    protected Bid $bid;

    public Collection $bids;

    public function mount(): void
    {
        $this->getBids();
    }

    #[Computed]
    public function getBids(): void
    {
        $this->bids = auth()->user()->bids()->latest()->get();
    }

    public function delete(Bid $bid): void
    {
        $this->authorize($bid);

        $this->bid = $bid;

        DB::transaction(function () {
            $paths = $this->bid->attachments->pluck('path');

            Storage::delete($paths);

            $this->bid->attachments()->delete();

            $this->bid->delete();

            $this->bid->assignment->update([
                'assignment_status_id' => 1
            ]);

            $this->getBids();
        });
    }
}; ?>

<div>
    @if ($this->bids->isNotEmpty())
        <div class="grid grid-cols-1 overflow-hidden gap-y-4 md:grid-cols-2">
            @foreach ($this->bids as $bid)
                <div class="p-4 mx-2 bg-white border border-gray-300 rounded-md shadow-xl md:p-8" wire:key="{{ $bid->id }}">
                    <div class="flex items-center justify-between">
                        <div class="space-y-1">
                            <div class="font-bold">
                                {{ $bid->assignment->title }}
                            </div>

                            <div class="text-xs">
                                created by <a class="underline" href="{{ route('profile.show', $bid->assignment->user->id) }}" wire:navigate>{{ $bid->assignment->user->name}}</a>
                            </div>
                        </div>
                        <div class="relative inline-block">
                            <div class="absolute transform translate-x-2 translate-y-2 bg-black rounded-lg inset-1">
                            </div>
                            <a href="{{ route('bids.show', $bid->id) }}"
                                class="relative inline-block px-4 py-2 text-sm font-bold text-center text-black bg-white border-2 border-black rounded-lg"
                                wire:navigate>
                                {{ __('MORE ;') }}
                            </a>
                        </div>
                    </div>

                    <div class="my-4 text-gray-700 text-md">
                        <div class="my-2 space-x-2">
                            @foreach ($bid->assignment->skills as $skill)
                                <x-badge flat emerald :label="$skill->name" class="py-2" />
                            @endforeach
                        </div>

                        <small>
                            {{ __('posted ').$bid->assignment->created_at->diffForHumans() }}
                        </small>
                    </div>

                    <div class="flex items-center justify-between text-xl">
                        <div>
                            ${{ $bid->assignment->budget }}
                        </div>

                        <div class="flex items-center space-x-2">
                            @if ($bid->status && $bid->status->name === "Accepted")
                                <x-badge flat amber :label="$bid->status->name" class="py-2" />
                            @else
                                <x-badge flat rose :label="$bid->status->name" class="py-2" />
                            @endif

                            <x-button flat icon="trash" gray interaction="negative"
                                wire:click="delete({{ $bid->id }})"
                                wire:confirm='Are you sure you want to withdraw from this assignment?' />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div
            class="flex flex-col items-center max-w-md mx-2 space-y-8 bg-white rounded-md shadow-md md:mx-auto p-14">

            <div class="flex flex-col items-center space-y-2">
                <x-heroicons::solid.bookmark class="w-8 h-8"/>
                <p class="text-gray-600">
                    {{ __('Get started by making a bid.') }}
                </p>
            </div>

            <div class="relative">
                <div class="absolute transform translate-x-2 translate-y-2 bg-black rounded-lg inset-1"></div>
                <a href="{{ route('welcome') }}"
                    class="relative flex items-center px-4 py-2 space-x-2 text-sm font-bold text-center text-black bg-white border-2 border-black rounded-lg"
                    wire:navigate>

                    <span>
                        {{ __('HOME ;') }}
                    </span>

                    <x-heroicons::solid.arrow-long-right class="w-5 h-5" />
                </a>
            </div>
        </div>
    @endif
</div>
