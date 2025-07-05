<?php

namespace App\Spotlight;

use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class SubjectMaster extends SpotlightCommand
{
 
    protected string $name = 'Subject Master';

    protected string $description = 'Manage Subject Master for Online Test Module.';



    public function execute(Spotlight $spotlight)
    {
        $spotlight->redirectRoute('subject_master.index');
    }


    public function shouldBeShown(): bool
    {
        return auth()->user()->role == 'A';
    }
}
