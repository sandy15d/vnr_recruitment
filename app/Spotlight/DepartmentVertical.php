<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;


class DepartmentVertical extends SpotlightCommand
{
    protected string $name = 'Department Vertical';
    protected string $description = 'Manage Department Vertical Master.';
    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/admin/department_vertical');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'A';
    }
}
