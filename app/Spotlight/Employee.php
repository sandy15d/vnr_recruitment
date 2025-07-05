<?php

namespace App\Spotlight;

use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use Illuminate\Http\Request;

class Employee extends SpotlightCommand
{
    protected string $name = 'Employee Master';
    protected string $description = 'Manage Employee Master.';
    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/admin/employee');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'A';
    }
}
