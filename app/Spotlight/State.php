<?php

namespace App\Spotlight;

use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use Illuminate\Http\Request;

class State extends SpotlightCommand
{
    protected string $name = 'State Master';
    protected string $description = 'Manage State Master For General Purpose.';
    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/admin/gen_states');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'A';
    }
}
