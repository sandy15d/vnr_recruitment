<?php

namespace App\Spotlight;

use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use Illuminate\Http\Request;

class EducationInstitute extends SpotlightCommand
{
    protected string $name = 'Education Institute Master';
    protected string $description = 'Manage Education Institute Master.';
    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/admin/institute');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'A';
    }
}
