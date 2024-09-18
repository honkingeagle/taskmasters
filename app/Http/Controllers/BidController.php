<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Bid;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class BidController extends Controller
{
    public function create(Assignment $assignment): View
    {
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
