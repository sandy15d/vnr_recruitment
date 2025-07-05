<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;


class Grade extends SpotlightCommand
{
    protected string $name = 'Grade Master';
    protected string $description = 'Manage Grade Master.';
    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/admin/grade');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'A';
    }
}
