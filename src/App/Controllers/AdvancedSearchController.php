<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Config\Paths;
use App\Services\{CategoryService, TransactionService};
class AdvancedSearchController
{
    public function __construct(private TemplateEngine $view, private CategoryService $categoryService, private TransactionService $transactionService)
    {
    }
    public function createView()
    {
        $categories = $this->categoryService->getCategories();

        // 1 is the defult
        $page = $_GET['p'] ?? 1;
        $page = (int) $page;
        
                // //number of result we want in a page
        $length = 3;
        $offset = ($page - 1) * $length;
        
                //$searchTerm null -> previousPageQuery will ignore it at http_build_query
        $searchTerm = $_GET['s'] ?? null;
        $categoryTerm = $_GET['c'] ?? null;
        
        [$transactions, $count] = $this->categoryService->getTransactionsCategory(
            $length, $offset
        );    
      
                
        $lastPage = ceil($count / $length);
                
        //create an array of 1,2,... lastPage
        $pages = $lastPage ? range(1, $lastPage) : [];
        $pageLinks = array_map(
            fn($pageNum) => http_build_query([
                'p' => $pageNum,
                's' => $searchTerm,
                'c' => $categoryTerm
        ]),$pages);
        
                echo $this->view->render("advanced_search.php", [
                    'transactions' => $transactions,
                    'currentPage' => $page,
                    'previousPageQuery' => http_build_query([
                        'p' => $page - 1,
                        's' => $searchTerm,
                        'c' => $categoryTerm
                    ]),
                    'lastPage' => $lastPage,
                    'nextPageQuery' => http_build_query([
                        'p' => $page + 1,
                        's' => $searchTerm,
                        'c' => $categoryTerm
                    ]),
                    'pageLinks' => $pageLinks,
                    'searchTerm' => $searchTerm,
                    'categoryTerm' => $categoryTerm,
                    'categories' => $categories,
                ]);
    }
}