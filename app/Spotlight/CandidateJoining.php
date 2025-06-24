<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class CandidateJoining extends SpotlightCommand
{
    protected string $name = 'Candidate Joining';
    protected string $description = 'List of Candidate Who are Joining';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/candidate_joining');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'R' || auth()->user()->role == 'A';
    }
}
