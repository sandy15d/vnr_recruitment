<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class CampusCosting extends SpotlightCommand
{
    protected string $name = 'Campus Hiring Costing';
    protected string $description = 'Campus Hiring Costing List';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/campus_hiring_costing');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'R' || auth()->user()->role == 'A';
    }
}
