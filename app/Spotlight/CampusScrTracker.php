<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class CampusScrTracker extends SpotlightCommand
{
    protected string $name = 'Campus Screening Tracker';
    protected string $description = 'List of Students for Screening';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/campus_screening_tracker');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'R' || auth()->user()->role == 'A';
    }
}
