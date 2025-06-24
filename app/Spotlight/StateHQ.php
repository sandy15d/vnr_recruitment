<?php

namespace App\Spotlight;

use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use Illuminate\Http\Request;

class StateHQ extends SpotlightCommand
{
    protected string $name = 'State Master';
    protected string $description = 'Manage State Master for HQ.';
    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/admin/state');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'A';
    }
}
