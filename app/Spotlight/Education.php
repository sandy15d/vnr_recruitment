<?php

namespace App\Spotlight;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;


class Education extends SpotlightCommand
{
    protected string $name = 'Education Master';
    protected string $description = 'Manage Education Master.';
    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/admin/education');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'A';
    }
}
