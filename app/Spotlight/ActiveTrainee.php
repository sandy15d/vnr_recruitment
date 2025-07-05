<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;


class ActiveTrainee extends SpotlightCommand
{
    protected string $name = 'Active Trainee';
    protected string $description = 'Active Trainee List';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/active_trainee');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'R' || auth()->user()->role == 'A';
    }
}
