<?php

namespace App\Spotlight;

use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class Dashboard extends SpotlightCommand
{

    protected string $name = 'Dashboard';

    protected string $description = 'Go to Dashboard';

    protected array $synonyms = [
        'Dashboard',
        'Home'

    ];
    public function execute(Spotlight $spotlight): void
    {
        if(auth()->user()->role == 'A'){
            $spotlight->redirect('/admin/dashboard');
        }elseif(auth()->user()->role == 'R'){
            $spotlight->redirect('/recruiter/dashboard');
        }elseif(auth()->user()->role == 'H'){
            $spotlight->redirect('/hod/dashboard');
        }
      
    }

    public function shouldBeShown(): bool
    {
        return true;
    }
}
