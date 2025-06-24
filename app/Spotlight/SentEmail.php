<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class SentEmail extends SpotlightCommand
{

    protected string $name = 'Sent Email';
    protected string $description = 'List of Email Sent by Application';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/admin/sentemails', '_blank');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'A' || auth()->user()->role == 'R';
    }
}
