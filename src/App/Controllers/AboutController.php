<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Config\Paths;

class Aboutcontroller
{
    private TemplateEngine $view;

    public function __construct()
    {
        $this->view = new TemplateEngine(Paths::VIEWS);
    }
    public function about()
    {
        echo $this->view->render('about.php', [
            'title' => 'About Page'
        ]);
    }
}
