<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;


class CampusMRF extends SpotlightCommand
{
    protected string $name = 'Campus MRF';
    protected string $description = 'Allocated Campus MRF List';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/campus_mrf_allocated');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'R' || auth()->user()->role == 'A';
    }
}
