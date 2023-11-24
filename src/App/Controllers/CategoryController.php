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

      //need to change the location(after creation) to Chart controller
      public function categoryChartView()
      {
        $categories = $this->categoryService->getCategories();

        $categories = array_map(function(array $category) {
          return $category['category_name'];
        }, $categories);

        $totals = array_map(function(String $category) {
          return (($this->categoryService->getTotalAmoutByCategory($category))['total']);
        }, $categories);
        
        echo $this->view->render("charts/category_chart.php", ['categories' => $categories, 'totals' => $totals]);
      }

      //need to change the location(after creation) to Chart controller
      public function monthChartView()
      {
        $months = ['1','2','3','4','5','6','7','8','9','10','11','12'];

        $totals = array_map(function(String $month) {
          return (($this->categoryService->getTotalAmoutByMonth($month))['total']);
        }, $months);
        
        echo $this->view->render("charts/month_chart.php", ['totals' => $totals]);
      }

      public function create() {
        $this->validatorService->validateCategory($_POST);
        $this->categoryService->create($_POST);
        redirectTo('/');
      }

}