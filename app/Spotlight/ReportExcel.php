<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;


class ReportExcel extends SpotlightCommand
{
    protected string $name = 'Report in Excel';
    protected string $description = 'Download Reports in Excel Format.';
    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/reports_download');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'A';
    }
}
