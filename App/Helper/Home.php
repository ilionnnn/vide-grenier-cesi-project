<?php

namespace App\Controllers;

use App\Models\Articles;
use \Core\View;
use Exception;

class Home extends \Core\Controller
{
    public function indexAction()
    {

        View::renderTemplate('Home/index.html', []);
    }
}