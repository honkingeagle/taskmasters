<?php

use App\Models\Skill;
use Livewire\Volt\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;


new class extends Component {
    public Collection $skills;

    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('required|integer|min:1')]
    public string $budget = '';

    #[Validate('required|date')]
    public string $deadline = '';

    #[Validate('required')]
    public string $description = '';

    #[Validate('required|array')]
    public array $skill_ids = [];


    public function mount(): void
    {
        $this->skills = Skill::all();
    }

    public function store(): void
    {
        $this->validate();

        DB::transaction(function () {
            $assignment = auth()->user()
                ->assignments()
                ->create([
                    'title' => $this->title,
                    'budget' => $this->budget,
                    'deadline' => $this->deadline,
                    'description' => $this->description
                ]);

            $assignment->skills()->attach($this->skill_ids);

            $this->redirectRoute("dashboard");
        });
    }

}; ?>

<div class="p-6 mx-2 bg-white rounded-md shadow-md">
    <form wire:submit="store" class="space-y-4">
        <div>
            <x-input-label :value="__('Title')" />
            <x-text-input
                placeholder="{{ __('Pythonista') }}"
                wire:model="title"
                class="block w-full mt-1"
                type="text"
                required autofocus autocomplete="title"
            />
            <x-input-error :messages="$errors->get('title')" class="mt-2" />
        </div>

        <div>
            <x-input-label :value="__('Budget')" />
            <x-text-input
                placeholder="{{ __('2500') }}"
                wire:model="budget"
                class="block w-full mt-1"
                type="text"
                required autofocus autocomplete="budget"
            />
            <x-input-error :messages="$errors->get('budget')" class="mt-2" />
        </div>

        <div>
            <x-input-label :value="__('Deadline')" />

            <x-datetime-picker
                wire:model="deadline"
                placeholder="Set date and time"
            />
            <x-input-error :messages="$errors->get('deadline')" class="mb-2"/>
        </div>

        <div>
            <x-input-label :value="__('Skills')" />
            <x-select
                multiselect
                placeholder="{{ __('SQL') }}"
                wire:model="skill_ids"
                class="mt-1"
                :options="$skills"
                option-value="id"
                option-label="name"
            />
        </div>
         <div class="space-y-2">
            <x-input-label :value="__('Job description')" />
            <textarea
                wire:model="description"
                placeholder="{{ __('I\'m looking for a skilled data mining and scraping expert to assist...') }}"
                class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            ></textarea>
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
         </div>

        <x-primary-button class="mt-4">
            {{ __('Post') }}
        </x-primary-button>
    </form>
</div>
