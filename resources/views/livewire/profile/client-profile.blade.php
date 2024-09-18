<?php

use App\Models\Client;
use Livewire\Volt\Component;
use Livewire\Attributes\Validate;

new class extends Component
{
    public ?Client $client = null;

    #[Validate('required|string|max:255')]
    public string $organization;

    public function mount(): void
    {
        $this->client = auth()->user()->client;

        $this->organization = $this->client ? $this->client->organization : '';
    }

    public function update(): void
    {
        if ($this->client) {
            $this->authorize('update', $this->client);

            $this->client->update([
                'organization' => $this->organization,
            ]);
        }

        $client = auth()->user()->client()->create([
            'organization' => $this->organization,
        ]);

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
                <x-input-label :value="__('Organization')" />
                <x-text-input
                wire:model="organization"
                id="organization"
                placeholder="{{ __('e.g EggHead') }}"
                name="organization"
                type="text"
                class="block w-full mt-1"
                required
                autofocus
                autocomplete="organization" />
                <x-input-error class="mt-2" :messages="$errors->get('organization')" />
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
