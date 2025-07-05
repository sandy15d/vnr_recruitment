<?php

namespace App\Spotlight;

use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use Illuminate\Http\Request;

class Headquarter extends SpotlightCommand
{
    protected string $name = 'Headquarter Master';
    protected string $description = 'Manage Headquarter Master.';
    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/admin/headquarter');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'A';
    }
}
