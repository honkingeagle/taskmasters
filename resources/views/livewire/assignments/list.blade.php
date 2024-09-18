<?php

use App\Models\Assignment;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use App\Models\AssignmentStatus;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

new class extends Component {
    public Collection $assignments;

    public Collection $statuses;

    public function mount(): void
    {
        $this->getAssignments();

        $this->getStatuses();
    }

    #[Computed]
    public function getAssignments(): void
    {
        $this->assignments = auth()->user()->assignments()->latest()->get();
    }

    #[Computed]
    public function getStatuses(): void
    {
        $this->statuses = AssignmentStatus::all();
    }

    public function changeStatus(Assignment $assignment, $statusId): void
    {
        $this->authorize($assignment);

        $assignment->update(['assignment_status_id' => $statusId]);

        $this->getAssignments();
    }

    public function delete(Assignment $assignment): void
    {
        $this->authorize($assignment);

        $assignment->delete();

        $this->getAssignments();
    }
}; ?>

<div>
    @if ($this->assignments->isNotEmpty())
        <div class="grid grid-cols-1 overflow-hidden gap-y-4 md:grid-cols-2 text-md md:text-lg">
            @foreach ($this->assignments as $assignment)
                <div class="p-4 mx-2 bg-white border border-gray-300 rounded-md shadow-xl md:p-8" wire:key="{{ $assignment->id }}">
                    <div class="flex items-center justify-between">
                        <div class="space-y-1">
                            <div class="font-bold">
                                {{ $assignment->title }}
                            </div>

                            <div class="text-xs">
                                created by <a class="underline" href="{{ route('profile.show', $assignment->user->id) }}" wire:navigate>{{ $assignment->user->name}}</a>
                            </div>
                        </div>

                        <div class="relative inline-block">
                            <div class="absolute transform translate-x-2 translate-y-2 bg-black rounded-lg inset-1">
                            </div>
                            <a href="{{ route('assignments.show', $assignment->id) }}"
                                class="relative inline-block px-4 py-2 text-sm font-bold text-center text-black bg-white border-2 border-black rounded-lg"
                                wire:navigate>
                                {{ __('MORE ;') }}
                            </a>
                        </div>
                    </div>

                    <div class="my-4 text-gray-700 text-md">
                        <div class="gap-2 my-2">
                            @foreach ($assignment->skills as $skill)
                                <x-badge flat emerald :label="$skill->name" class="py-2" />
                            @endforeach
                        </div>

                        <small>
                            {{ __('posted') }} {{ $assignment->created_at->diffForHumans() }}
                        </small>
                    </div>

                    <div class="flex items-center justify-between text-xl">
                        <div>
                            ${{ $assignment->budget }}
                        </div>

                        <div class="flex items-center space-x-4">
                            @switch($assignment->status->name)
                                @case('Bidding')
                                    <x-badge flat red :label="Str::upper($assignment->status->name)" />
                                @break

                                @case('In progress')
                                    <x-badge flat orange :label="Str::upper($assignment->status->name)" />
                                @break

                                @case('Complete')
                                    <x-badge flat emerald :label="Str::upper($assignment->status->name)" />
                                @break

                                @default
                                    <x-badge flat slate :label="Str::upper($assignment->status->name)" />
                            @endswitch

                            <div class="p-1 bg-gray-100 rounded-md shadow">
                                <x-dropdown>
                                    {{-- <x-dropdown.header label="Status">
                                        @foreach ($this->statuses as $status)
                                            @if ($assignment->status->id !== $status->id)
                                                <x-dropdown.item
                                                    wire:click="changeStatus({{ $assignment->id }}, {{ $status->id }})"
                                                    :label="$status->name" />
                                            @endif
                                        @endforeach
                                    </x-dropdown.header> --}}

                                    <x-dropdown.header label="Actions">
                                        <x-dropdown.item :href="route('assignments.edit', $assignment->id)" label="Edit" wire:navigate />
                                        <x-dropdown.item wire:click="delete({{ $assignment->id }})" label="Delete"
                                            wire:confirm="Are you sure to delete this assignment?" />
                                    </x-dropdown.header>
                                </x-dropdown>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div
            class="flex flex-col items-center max-w-md mx-2 space-y-8 bg-white rounded-md shadow-md md:mx-auto p-14">

            <div class="flex flex-col items-center space-y-2 text-center">
                <x-heroicons::outline.clipboard-document-check class="w-8 h-8" />
                <p class="text-gray-600">
                    {{ __('Get started by creating an assignment.') }}
                </p>
            </div>

            <div class="relative">
                <div class="absolute transform translate-x-2 translate-y-2 bg-black rounded-lg inset-1"></div>
                <a href="{{ route('assignments.create') }}"
                    class="relative flex items-center px-4 py-2 space-x-2 text-sm font-bold text-center text-black bg-white border-2 border-black rounded-lg"
                    wire:navigate>

                    <span>
                        {{ __('CREATE ;') }}
                    </span>
                    <x-heroicons::solid.arrow-long-right class="w-5 h-5" />
                </a>
            </div>
        </div>
    @endif
</div>
