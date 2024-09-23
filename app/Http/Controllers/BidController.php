<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use Illuminate\View\View;
use App\Models\Assignment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class BidController extends Controller
{
    public function create(Assignment $assignment): View | RedirectREsponse
    {
        $userId = Auth::user()->id;

        $bid = $assignment->bids()
                ->where('user_id', $userId)
                ->first();

        if ($bid) {
            return redirect()->route('bids.show', ['bid' => $bid->id]);
        }

        return view('bids.create', [
            'assignment' => $assignment,
        ]);
    }

    public function show(Bid $bid): View
    {
        Gate::authorize('update', $bid);

        return view('bids.show', [
            'bid' => $bid,
        ]);
    }
}
