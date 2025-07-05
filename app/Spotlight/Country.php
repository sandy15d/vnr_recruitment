<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;


class Country extends SpotlightCommand
{
    protected string $name = 'Country Master';
    protected string $description = 'Manage Country Master.';
    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/admin/country');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'A';
    }
}
