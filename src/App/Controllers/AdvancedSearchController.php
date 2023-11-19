<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Config\Paths;

class AdvancedSearchController
{
    public function __construct(private TemplateEngine $view)
    {
    }
    public function createView()
    {
        
        echo $this->view->render('advanced_search.php', [
            'title' => 'Advanced Search Page'
        ]);
    }
}