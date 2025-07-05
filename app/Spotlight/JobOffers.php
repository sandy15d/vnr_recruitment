<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class JobOffers extends SpotlightCommand
{
    protected string $name = 'Job Offers';
    protected string $description = 'List of Candidate who selected for Job';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/offer_letter');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'R' || auth()->user()->role == 'A';
    }
}
