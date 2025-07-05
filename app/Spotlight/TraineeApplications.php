<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;


class TraineeApplications extends SpotlightCommand
{
    protected string $name = 'Trainee Applications';
    protected string $description = 'Trainee Applications List';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/trainee_applications');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'R' || auth()->user()->role == 'A';
    }
}
