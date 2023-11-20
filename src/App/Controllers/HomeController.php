<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Config\Paths;
use App\Services\{TransactionService, CategoryService};
class HomeController
{
    public function __construct(private TemplateEngine $view, private TransactionService $transactionService, private CategoryService $categoryService)
    {
    }
    public function home()
    {
        // 1 is the defult
        $page = $_GET['p'] ?? 1;
        $page = (int) $page;

        //number of result we want in a page
        $length = 3;
        $offset = ($page - 1) * $length;

        //$searchTerm null -> previousPageQuery will ignore it at http_build_query
        $searchTerm = $_GET['s'] ?? null;

        [$transactions, $count] = $this->transactionService->getUserTransactions(
            $length, $offset
        );        
        
        $lastPage = ceil($count / $length);
        
        //create an array of 1,2,... lastPage
        $pages = $lastPage ? range(1, $lastPage) : [];
        $pageLinks = array_map(
            fn($pageNum) => http_build_query([
                'p' => $pageNum,
                's' => $searchTerm
            ]),$pages);

        echo $this->view->render("index.php", [
            'transactions' => $transactions,
            'currentPage' => $page,
            'previousPageQuery' => http_build_query([
                'p' => $page - 1,
                's' => $searchTerm
            ]),
            'lastPage' => $lastPage,
            'nextPageQuery' => http_build_query([
                'p' => $page + 1,
                's' => $searchTerm
            ]),
            'pageLinks' => $pageLinks,
            'searchTerm' => $searchTerm,
        ]);
    }
}