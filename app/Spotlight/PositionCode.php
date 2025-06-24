<?php

namespace App\Spotlight;

use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use Illuminate\Http\Request;

class PositionCode extends SpotlightCommand
{
    protected string $name = 'Position Code Master';
    protected string $description = 'Manage Position Code Master.';
    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/position_code');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'A';
    }
}
