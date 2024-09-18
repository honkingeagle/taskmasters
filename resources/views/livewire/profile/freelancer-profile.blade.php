<?php

use App\Models\Skill;
use App\Models\Freelancer;
use Livewire\Volt\Component;
use Livewire\Attributes\Validate;
use Illuminate\Database\Eloquent\Collection;

new class extends Component
{
    public Collection $skills;

    public ?Freelancer $freelancer = null;

    #[Validate('integer')]
    public string $hourly_rate = '';

    #[Validate('max:255')]
    public string $description = '';

    #[Validate('array')]
    public array $skill_ids = [];

    public function mount(): void
    {
        $this->skills = Skill::all();

        $this->freelancer = auth()->user()->freelancer;

        $this->hourly_rate = $this->freelancer ? $this->freelancer->hourly_rate : '';

        $this->description = $this->freelancer ? $this->freelancer->description : '';

        $this->skill_ids = $this->freelancer ? $this->freelancer->skills->pluck('id')->all() : [];
    }

    public function update(): void
    {
        if ($this->freelancer) {
            $this->authorize('update', $this->freelancer);

            $this->freelancer->update([
                'hourly_rate' => $this->hourly_rate,
                'description' => $this->description
            ]);

            $this->freelancer->skills()->sync($this->skill_ids);

            $this->dispatch('profile-updated');

            return;
        }

        $freelancer = auth()->user()->freelancer()->create([
            'hourly_rate' => $this->hourly_rate,
            'description' => $this->description
        ]);

        $freelancer->skills()->attach($this->skill_ids);

        $this->dispatch('profile-updated');
    }

}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Additional Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Provide more context to who you are") }}
        </p>
    </header>

    <div>
        <form wire:submit='update' class="mt-6 space-y-6">
            <div>
                <x-input-label :value="__('Hourly Rate')" />
                <x-text-input
                wire:model="hourly_rate"
                id="hourly_rate"
                name="hourly_rate"
                placeholder="{{ __('25')}}"
                type="text"
                class="block w-full mt-1"
                autofocus
                autocomplete="hourly_rate" />
                <x-input-error class="mt-2" :messages="$errors->get('hourly_rate')" />
            </div>

            <div>
                <x-select
                    multiselect
                    label="Skills"
                    placeholder="{{ __('Linear Algebra') }}"
                    wire:model="skill_ids"
                    class="mt-1"
                    :options="$skills"
                    option-value="id"
                    option-label="name"
                />
            </div>

            <div class="space-y-2">
                <x-input-label :value="__('Description')" />
                <textarea
                    wire:model="description"
                    placeholder="{{ __('Mathematics enthusiast with a deep passion for problem-solving through linear algebra and calculus. Whether it\'s breaking down complex systems or optimizing processes, I thrive on turning abstract concepts into practical solutions. Let me help you tackle your toughest equations and bring clarity to your most challenging projects!') }}"
                    class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                ></textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
             </div>

            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Save') }}</x-primary-button>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>
    </div>

</section>
