<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{ValidatorService, CategoryService};

class CategoryController {
    public function __construct(
        private TemplateEngine $view, private ValidatorService $validatorService, private CategoryService $categoryService
      ) {
      }
      public function createView()
      {
        echo $this->view->render("categories/create.php");
      }

      public function create() {
        $this->validatorService->validateCategory($_POST);
        $this->categoryService->create($_POST);
        redirectTo('/');
      }

}