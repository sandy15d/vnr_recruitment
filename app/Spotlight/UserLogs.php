<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class UserLogs extends SpotlightCommand
{

    protected string $name = 'User Logs';
    protected string $description = 'View your user logs.';
    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/admin/userlogs');
    }

    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'A';
    }
}
