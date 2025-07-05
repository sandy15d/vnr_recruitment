<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;


class MrfManualEntry extends SpotlightCommand
{
    protected string $name = 'MRF Manual Entry';
    protected string $description = 'Create MRF manually';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/recruiter_mrf_entry');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'R' || auth()->user()->role == 'A';
    }
}
