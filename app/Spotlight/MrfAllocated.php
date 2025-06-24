<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class MrfAllocated extends SpotlightCommand
{
    protected string $name = 'MRF Allocated';
    protected string $description = 'List of MRF Allocated to you';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/recruiter/mrf_allocated');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'R';
    }
}
