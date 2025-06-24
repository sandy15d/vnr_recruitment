<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;


class MyTeam extends SpotlightCommand
{
    protected string $name = 'My Team';
    protected string $description = 'List of Team Members';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/hod/myteam');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'H';
    }
}
