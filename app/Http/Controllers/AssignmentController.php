<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Skill;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function create(): View
    {
        return view('assignments.create', []);
    }

    public function edit(Assignment $assignment): View
    {
        return view('assignments.edit', [
            'skills' => Skill::select('id', 'name')->get(),
            'assignment' => $assignment,
        ]);
    }

    public function show(Assignment $assignment): View
    {
        return view('assignments.show', [
            'assignment' => $assignment,
        ]);
    }
}
