<?php
declare(strict_types=1);

namespace App\Controllers;


use Core\View;

class Profile extends \Core\Controller
{

    public function showAction(): void
    {
        View::renderTemplate('Profile/show.html.twig');
    }
}