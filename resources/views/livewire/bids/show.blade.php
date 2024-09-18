<?php

use App\Models\Bid;
use App\Models\Attachment;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Livewire\Volt\Component;
use Illuminate\Database\Eloquent\Collection;

new class extends Component {
    use WithFileUploads;

    public Bid $bid;

    #[Validate(['attachments.*' => 'image|max:1024'])]
    public array $attachments = [];

    #[Validate('required|integer')]
    public string $bid_amount = '';

    public Collection $paths;

    public function mount(): void
    {
        $this->bid_amount = $this->bid->bid_amount;

        $this->getPaths();
    }

    public function update(Bid $bid): void
    {
        $this->bid = $bid;

        $this->authorize($bid);

        DB::transaction(function () {
            $paths = [];

            foreach ($this->attachments as $attachment) {
                $paths[] = ['path' => $attachment->storeAs(path: 'attachments',
                name: $this->bid->user_id.'.'.$this->bid->assignment_id.'.'.$attachment->getClientOriginalName())];
            }

            $this->bid->update([
                        'bid_amount' => $this->bid_amount,
                    ]);

            $this->bid->attachments()->createMany($paths);

            $this->redirectRoute("dashboard");
        });


    }

    public function delete(Bid $bid): void
    {
        $this->authorize($bid);

        $this->bid = $bid;

        DB::transaction(function() {
            $paths = $this->bid->attachments->pluck('path');

            Storage::delete($paths);

            $this->bid->attachments()->delete();

            $this->bid->delete();
        });
    }

    public function getPaths(): void
    {
        $this->paths = $this->bid->attachments;
    }

    public function deleteAttachment(Attachment $attachment): void
    {
        Storage::delete($attachment->path);

        $attachment->delete();

        $this->getPaths();
    }
}; ?>

<div>
    <livewire:bids.assignment-show :assignment="$bid->assignment" />
        <div class="p-4 mt-4 bg-white rounded-lg shadow-md md:p-8">
            <h2 class="my-2 text-xl font-bold">{{ __('Proposal') }}</h2>

            <form wire:submit="update({{ $bid->id }})">
                <div>
                    <x-input-label for="bid_amount" :value="__('Amount')" />
                    <x-text-input
                        placeholder="{{ __('2550') }}"
                        wire:model="bid_amount"
                        class="block w-[90%] md:w-[50%] max-w-[90%] md:max-w-[50%] mt-2"
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

        @if ($paths->isNotEmpty())
        <div class="p-4 mt-5 space-y-1 bg-white rounded-md shadow-md md:p-8">
            <x-input-label for="uploadedFiles" :value="__('Uploaded files')"/>
            @foreach ($paths as $path)
                    <x-button
                    wire:key='{{ $path->id }}'
                    right-icon="x-mark" negative
                    :label="Str::of($path->path)->basename()"
                    wire:click="deleteAttachment({{ $path->id }})"
                    wire:confirm="Are you sure you want to delete this file?"/>
            @endforeach
        </div>
        @endif
</div>
