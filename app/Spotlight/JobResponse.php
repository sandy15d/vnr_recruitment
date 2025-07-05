<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;


class JobResponse extends SpotlightCommand
{
    protected string $name = 'Job Response';
    protected string $description = 'List of Candidate who have applied for the job';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/job_response');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'R' || auth()->user()->role == 'A';
    }
}
