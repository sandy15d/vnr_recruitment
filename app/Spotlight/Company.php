<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class Company extends SpotlightCommand
{
    /**
     * This is the name of the command that will be shown in the Spotlight component.
     */
    protected string $name = 'Company Master';

    /**
     * This is the description of your command which will be shown besides the command name.
     */
    protected string $description = 'Manage company master';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/admin/company');
    }

    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'A';
    }
}
