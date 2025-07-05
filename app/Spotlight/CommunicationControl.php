<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class CommunicationControl extends SpotlightCommand
{
    protected string $name = 'Communication Control';
    protected string $description = 'Enables / Disable Communication Controls.';
    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/admin/communication_control');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'A';
    }
}
