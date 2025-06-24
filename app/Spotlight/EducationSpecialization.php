<?php

namespace App\Spotlight;

use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use Illuminate\Http\Request;

class EducationSpecialization extends SpotlightCommand
{
    protected string $name = 'Education Specialization Master';
    protected string $description = 'Manage Education Specialization Master.';
    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/admin/eduspecialization');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'A';
    }
}
