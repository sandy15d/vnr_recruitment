<?php

namespace App\Spotlight;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;


class UserList extends SpotlightCommand
{

    protected string $name = 'User';
    protected string $description = 'Show a list of users';
    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect('/admin/userlist');
    }

    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'A';
    }
}
