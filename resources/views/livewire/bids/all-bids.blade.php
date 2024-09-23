<?php

use App\Models\Assignment;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use App\Models\AssignmentStatus;
use Livewire\Attributes\Computed;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

new class extends Component {
    public Collection $assignments;

    public string $searchedWord = '';

    public function mount(): void
    {
        $this->getAssignments();
    }

    public function updatedSearchedWord(): void
    {
        if ($this->searchedWord){
            $this->assignments = Assignment::search($this->searchedWord)->get();
        } else {
            $this->getAssignments();
        }
    }

    #[Computed]
    public function getAssignments(): void
    {
        $this->assignments = Assignment::where('assignment_status_id', 1)
            ->whereDoesntHave('bids', function (Builder $query) {
                $query->where('user_id', auth()->id());
            })
            ->latest()
            ->get();
    }
}; ?>

<div>
    <div class="mx-2 my-8 md:max-w-[50%] md:mx-auto">
        <x-input
            right-icon="magnifying-glass"
            placeholder="search"
            wire:model.live.debounce.250ms='searchedWord'
        />
    </div>
    <div>
        @if ($this->assignments->isNotEmpty())
            <div>
                @foreach ($this->assignments as $assignment)
                    <livewire:assignments.assignment :$assignment :key="$assignment->id" />
                @endforeach
            </div>
        @else
            <div
                class="flex flex-col items-center mx-2 my-4 space-y-8 text-center border border-gray-300 rounded-md shadow-xl p-14 md:max-w-md md:mx-auto">
                <x-heroicons::outline.clipboard-document-list class="w-8 h-8" />
                <p>
                    {{ __('There\'re no assignments at the moment!') }}
                </p>
            </div>
        @endif
    </div>
</div>
