<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;


class ManpowerRequisition extends SpotlightCommand
{
    protected string $name = 'MRF';
    protected string $description = 'Create MRF';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/hod/manpowerrequisition');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'H';
    }
}
