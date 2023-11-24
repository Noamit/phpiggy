<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{CategoryService, ChartService};

class Chartcontroller
{
    public function __construct(private TemplateEngine $view, private CategoryService $categoryService, private ChartService $chartService)
    {
    }
    
      //need to change the location(after creation) to Chart controller
      public function categoryChartView()
      {
        $categories = $this->categoryService->getCategories();

        $categories = array_map(function(array $category) {
          return $category['category_name'];
        }, $categories);

        $totals = array_map(function(String $category) {
          return (($this->chartService->getTotalAmoutByCategory($category))['total']);
        }, $categories);
        
        echo $this->view->render("charts/category_chart.php", ['categories' => $categories, 'totals' => $totals]);
      }

      //need to change the location(after creation) to Chart controller
      public function monthChartView()
      {
        $months = ['1','2','3','4','5','6','7','8','9','10','11','12'];

        $totals = array_map(function(String $month) {
          return (($this->chartService->getTotalAmoutByMonth($month))['total']);
        }, $months);
        
        echo $this->view->render("charts/month_chart.php", ['totals' => $totals]);
      }
}