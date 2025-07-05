<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class TraineeMRF extends SpotlightCommand
{
    protected string $name = 'Trainee MRF';
    protected string $description = 'Allocated Trainee MRF List';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/trainee_mrf_allocated');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'R' || auth()->user()->role == 'A';
    }
}
