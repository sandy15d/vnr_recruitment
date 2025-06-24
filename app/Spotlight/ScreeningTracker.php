<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class ScreeningTracker extends SpotlightCommand
{
    protected string $name = 'Screening Tracker';
    protected string $description = 'List of Candidate for Technical Screening';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/TechnicalScreening');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'R' || auth()->user()->role == 'A';
    }
}
