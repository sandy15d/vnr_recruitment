<?php

namespace App\Spotlight;

use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use Illuminate\Http\Request;

class Eligibility extends SpotlightCommand
{
    protected string $name = 'Eligibility Master';
    protected string $description = 'Manage Eligibility Master.';
    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/admin/lodging');
    }
    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'A';
    }
}
