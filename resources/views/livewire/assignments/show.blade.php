<?php

use Livewire\Volt\Component;
use App\Models\Assignment;

new class extends Component {
    public Assignment $assignment;

    public function delete(Assignment $assignment): void
    {
        $this->authorize($assignment);

        $assignment->delete();

        $this->redirectRoute('dashboard');
    }
}; ?>

<div class="p-4 bg-white rounded-lg shadow-lg md:p-8">
    <div class="space-y-1">
        <div class="text-2xl font-bold">
            {{ $assignment->title }}
        </div>

        <div class="text-xs">
            created by <a class="underline" href="{{ route('profile.show', $assignment->user->id) }}"
                wire:navigate>{{ $assignment->user->name }}</a>
        </div>
    </div>
    <div class="flex items-end justify-between my-4">
        <small>posted {{ $assignment->created_at->diffForHumans() }}</small>
        <div>
            <span class="text-sm">{{ __('due') }}</span>
            <x-badge flat orange :label="$assignment->deadline->diffForHumans()" />
        </div>
    </div>
    <div>
        <span class="text-xl">${{ $assignment->budget }}</span>
    </div>
    <div class="my-10">
        <p>
            {{ $assignment->description }}
        </p>
    </div>

    <div class="flex flex-wrap gap-2">
        @foreach ($assignment->skills as $skill)
            <div class="p-4 bg-green-200 rounded-md shadow-md max-w-fit">
                {{ $skill->name }}
            </div>
        @endforeach
    </div>

    <div class="mt-4 space-x-6">
        <x-primary-button :href="route('assignments.edit', $assignment->id)" class="mt-4" wire:navigate>{{ __('Edit') }}
        </x-primary-button>
        <x-button flat rose label="Delete" wire:click="delete({{ $assignment->id }})"
            wire:confirm="Are you sure you want to delete this assignment?" />
    </div>
</div>
