<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;


class Designation extends SpotlightCommand
{
    protected string $name = 'Designation Master';
    protected string $description = 'Manage Designation Master.';
    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/admin/designation');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'A';
    }
}
