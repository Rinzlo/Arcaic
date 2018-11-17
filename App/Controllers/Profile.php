<?php
declare(strict_types=1);

namespace App\Controllers;


use App\Auth;
use Core\View;

class Profile extends Authenticated
{

    public function showAction(): void
    {
        View::renderTemplate('Profile/show.html.twig', [
            'user' => Auth::getUser()
        ]);
    }

    public function editAction(): void
    {
        View::renderTemplate('Profile/edit.html.twig', [
            'user' => Auth::getUser()
        ]);
    }
}