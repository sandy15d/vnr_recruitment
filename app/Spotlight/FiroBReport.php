<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;


class FiroBReport extends SpotlightCommand
{
    protected string $name = 'FiroB Report';
    protected string $description = 'Show FiroB Report.';
    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/Firob_Reports');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'A';
    }
}
