<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;


class ResumeDataBank extends SpotlightCommand
{
    protected string $name = 'Job Applications';
    protected string $description = 'List of Job Applications';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/job_applications');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'R' || auth()->user()->role == 'A';
    }
}
