<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class InterviewSchedule extends SpotlightCommand
{
   
    protected string $name = 'Interview Schedule';
    protected string $description = 'List of Candidate for Interview';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/hod/interviewschedule');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'H';
    }
}
