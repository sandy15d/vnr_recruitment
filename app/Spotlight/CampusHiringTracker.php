<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class CampusHiringTracker extends SpotlightCommand
{
    protected string $name = 'Campus Hiring Tracker';
    protected string $description = 'List of Campus Candidates for Hiring';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/campus_hiring_tracker');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'R' || auth()->user()->role == 'A';
    }
}
