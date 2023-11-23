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

      public function chartView()
      {
        $categories = $this->categoryService->getCategories();

        $categories = array_map(function(array $category) {
          return $category['category_name'];
        }, $categories);

        $totals = array_map(function(String $category) {
          return (($this->categoryService->getTotalAmout($category))['total']);
        }, $categories);
        
        echo $this->view->render("charts/category_chart.php", ['categories' => $categories, 'totals' => $totals]);
      }

      public function create() {
        $this->validatorService->validateCategory($_POST);
        $this->categoryService->create($_POST);
        redirectTo('/');
      }

}