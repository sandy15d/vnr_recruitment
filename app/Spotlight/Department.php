<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class Department extends SpotlightCommand
{
    protected string $name = 'Department Master';
    protected string $description = 'Manage Department Master.';
    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/admin/department');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'A';
    }
}
