<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;


class OldTrainee extends SpotlightCommand
{
    protected string $name = 'Old Trainee';
    protected string $description = 'Old Trainee List';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/old_trainee');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'R' || auth()->user()->role == 'A';
    }
}
