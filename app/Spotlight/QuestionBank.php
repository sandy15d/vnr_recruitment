<?php

namespace App\Spotlight;

use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class QuestionBank extends SpotlightCommand
{
    protected string $name = 'Question Bank';

    protected string $description = 'Manage Question Bank for Online Test Module.';



    public function execute(Spotlight $spotlight)
    {
        $spotlight->redirectRoute('question_bank.index');
    }


    public function shouldBeShown(): bool
    {
        return auth()->user()->role == 'A';
    }
}
