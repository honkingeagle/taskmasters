<?php

use App\Models\Bid;
use App\Models\Assignment;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\DB;

new class extends Component {
    public Bid $bid;

    public ?Assignment $assignment = null;

    // Update multiple databases
    //      -> assignments and bids tables.
    // Take the bids table
    //      -> update the bid_status_id column of one specific user
    //      -> update the bid_status_id column for all the other users associated with that assignment
    // Take the assignments table and update the assignment_status_id

    public function accept(Assignment $assignment): void
    {
        $this->authorize('update', $assignment);

        $this->assignment = $assignment;

        DB::transaction(function () {
            $this->bid->update([
                'bid_status_id' => 2
            ]);

            Bid::where('assignment_id', $this->assignment->id)
                ->where('id', '!=', $this->bid->id)
                ->update([
                    'bid_status_id' => 1
                ]);

            $this->assignment->update(['assignment_status_id' => 2]);
        });
    }

    public function cancel(Assignment $assignment): void
    {
        $this->authorize('update', $assignment);

        $this->assignment = $assignment;

        DB::transaction(function () {
            $this->bid->update([
                'bid_status_id' => 1
            ]);

            $this->assignment->update(['assignment_status_id' => 1]);
        });
    }

}; ?>

<div class="p-4 md:p-8 space-y-4 bg-white rounded-lg shadow-md md:max-w-[60%]" wire:key="{{ $bid->id }}">
    <div class="flex items-center justify-between">
        <a class="underline" href="{{ route('profile.show', $bid->user->id) }}" wire:navigate>{{ $bid->user->name }}</a>

        @if ($bid->status->name === "Not Accepted")
            <x-button light amber label="Accept" wire:click="accept({{ $bid->assignment_id }})" wire:confirm='Are you sure you want to accept this bid?'/>
        @else
            <x-button light negative label="Cancel" wire:click="cancel({{ $bid->assignment_id }})" wire:confirm='Are you sure you want to cancel this bid?'/>
        @endif
    </div>

    <div class="p-4 text-xl bg-green-200 rounded-md shadow-md max-w-fit">
        ${{ $bid->bid_amount }}
    </div>

    <div>
        @if ($bid->attachments->isNotEmpty())
            <div class="space-y-2">
                <x-input-label for="uploadedFiles" :value="__('Uploaded files')" />
                @foreach ($bid->attachments as $attachment)
                    <x-button wire:key='{{ $attachment->id }}' icon="arrow-down-tray" negative :label="Str::of($attachment->path)->basename()" />
                @endforeach
            </div>
        @endif
    </div>

</div>

</div>
