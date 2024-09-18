<?php

use Carbon\Carbon;
use App\Models\Assignment;
use Livewire\Volt\Component;

new class extends Component {
    public Assignment $assignment;

    public bool $isBeforeDeadline;

    public function mount(Assignment $assignment): void
    {
        $this->assignment = $assignment;

        $this->isBeforeDeadline = Carbon::now()->isBefore($assignment->deadline);
    }
}; ?>

<div class="p-6 mx-2 my-4 border border-gray-300 rounded-md shadow-lg">
    <div class="flex items-center justify-between">
        <div class="space-y-2">
            <div class="font-bold">
                {{ $assignment->title }}
            </div>

            <div class="text-xs">
                created by <a class="underline" href="{{ route('profile.show', $assignment->user->id) }}" wire:navigate>{{ $assignment->user->name}}</a>
            </div>
        </div>
        <div class="relative inline-block">
            <div class="absolute transform translate-x-2 translate-y-2 bg-black rounded-lg inset-1"></div>
            @if (auth()->check() && $assignment->user->is(auth()->user()))
                <a href="{{ route('assignments.show', $assignment->id) }}"
                    class="relative inline-block px-4 py-2 text-sm font-bold text-center text-black bg-white border-2 border-black rounded-lg"
                    wire:navigate>
                    {{ __('MORE ;') }}
                </a>
            @else
                <a
                href="{{ route('bids.create', $assignment->id) }}"
                class="relative inline-block px-4 py-2 font-bold text-center text-black bg-white border-2 border-black rounded-lg" wire:navigate>
                {{ __('APPLY ;') }}
                </a>
            @endif
        </div>
    </div>

    <div class="items-center justify-between mt-4 text-gray-700 md:flex text-md">
        <small>
            posted {{ $assignment->created_at->diffForHumans() }}
        </small>

        <div class="flex flex-wrap gap-2 my-2">
            @foreach($assignment->skills as $skill)
                <x-badge flat emerald :label="$skill->name" class="py-2"/>
            @endforeach
        </div>
    </div>

    <div class="flex items-end justify-between">
        <div class="mt-4 text-xl">
            ${{  $assignment->budget   }}
        </div>

        @if ($isBeforeDeadline)
            <div>
                <span class="text-sm">{{ __('due')}}</span>
                <x-badge flat orange :label="$assignment->deadline->diffForHumans()" />
            </div>
        @else
            <div>
                <span class="text-sm">{{ __('expired')}}</span>
                <x-badge flat red :label="$assignment->deadline->diffForHumans()" />
            </div>
        @endif
        </div>
    </div>
</div>
