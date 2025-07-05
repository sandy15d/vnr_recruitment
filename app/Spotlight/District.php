<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;


class District extends SpotlightCommand
{
    protected string $name = 'District Master';
    protected string $description = 'Manage District Master.';
    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/admin/district');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'A';
    }
}
