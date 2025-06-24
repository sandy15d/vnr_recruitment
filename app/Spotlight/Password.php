<?php

namespace App\Spotlight;

use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class Password extends SpotlightCommand
{
    protected string $name = 'Password';
    protected string $description = 'Change Password.';
    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/change-password');
    }

    public function shouldBeShown(): bool
    {
        return true;
    }
}
