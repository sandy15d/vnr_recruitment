<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;


class TraineeTracker extends SpotlightCommand
{
    protected string $name = 'Trainee Tracker';
    protected string $description = 'Trainee Tracker List';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/trainee_screening_tracker');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'R' || auth()->user()->role == 'A';
    }
}
