<?php

use App\Models\Assignment;
use Livewire\WithFileUploads;
use Livewire\Volt\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;

new class extends Component {
    use WithFileUploads;

    public Assignment $assignment;

    #[Validate(['attachments.*' => 'image|max:1024'])]
    public array $attachments = [];

    #[Validate('required|integer')]
    public string $bid_amount = '';

    public function store(): void
    {
        $this->validate();

        DB::transaction(function () {
            $paths = [];

            foreach ($this->attachments as $attachment) {
                $paths[] = ['path' => $attachment->storeAs(path: 'attachments',
                name: $this->assignment->user_id.'.'.$this->assignment->id.'.'.$attachment->getClientOriginalName())];
            }

            $bid = auth()
                    ->user()
                    ->bids()
                    ->create([
                        'assignment_id' => $this->assignment->id,
                        'bid_amount' => $this->bid_amount,
                    ]);

            $bid->attachments()->createMany($paths);

            $this->redirectRoute("dashboard");
        });


    }
}; ?>

<div>
    <livewire:bids.assignment-show :$assignment />
    <div class="p-4 mt-4 bg-white rounded-lg shadow-md md:p-8">
        <h2 class="my-2 text-xl font-bold">{{ __('Proposal') }}</h2>
        <form wire:submit="store">
            <div>
                <x-input-label for="bid_amount" :value="__('Amount')" />
                <x-text-input
                    placeholder="{{ __('2550') }}"
                    wire:model="bid_amount"
                    class="block w-[80%] md:w-[50%] max-w-[80%] md:max-w-[50%] mt-2"
                    type="text"
                    required autofocus autocomplete="bid_amount"
                />
                <x-input-error :messages="$errors->get('bid_amount')" class="mt-2" />
            </div>

            <div class="my-4">
                <x-input-label for="attachments" :value="__('Attach any relevant attachments')" />
                <div class="max-w-[90%] md:max-w-[50%] my-1">
                    <input
                    id="file-upload"
                    type="file"
                    multiple
                    wire:model="attachments"
                    class="flex w-full h-10 px-3 py-2 text-sm border rounded-md border-input bg-background ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                </div>
                <x-input-error :messages="$errors->get('attachments')" class="mt-2" />
            </div>
            <div>
                <x-primary-button>{{ __('save') }}</x-primary-button>
            </div>
        </form>
    </div>
</div>
