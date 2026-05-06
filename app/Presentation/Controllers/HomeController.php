<?php

declare(strict_types=1);

namespace App\Presentation\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Presentation\ViewModels\EmptyViewModel;

final class HomeController
{
    public function index(): void
    {
        Auth::requireLogin();
        View::renderWith('home', new EmptyViewModel());
    }
}
