<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{ValidatorService,TransactionService, CategoryService};

class TransactionController
{
  public function __construct(
    private TemplateEngine $view, private ValidatorService $validatorService, private TransactionService $transactionService, private CategoryService $categoryService
  ) {
  }

  public function createView()
  {
    $categories =  $this->categoryService->getCategories();
    echo $this->view->render("transactions/create.php", ['categories' => $categories]);
  }

  public function create() {
    $this->validatorService->validateTransaction($_POST);
    $this->transactionService->create($_POST);

    $excludedFields = ['description', 'amount', 'date'];
    //array of categories
    $filteredFormData = array_diff_key($_POST, array_flip($excludedFields));
    $last_id = $this->transactionService->lastId();

    foreach($filteredFormData as $category_name=>$val) {
      $category = $this->categoryService->getCategory($category_name);
      // check if category exists and add transaction to category if category exists
      if ($category) {
        $this->categoryService->addTransactionToCategory($category_name, $last_id);
      }
    }
    
    redirectTo('/');
  }

  public function editView(array $params)
  {
    //$param['transaction'] == the id of the transaction.
    //$param['transaction'] = 3 in this example : http://phpiggy.local/transaction/3 
    $transaction = $this->transactionService->getUserTransaction($params['transaction']);
    if (!$transaction) {
        redirectTo('/');
    }
    echo $this->view->render("transactions/edit.php", ['transaction' => $transaction]); 
  }


  public function edit(array $params) {
    $transaction = $this->transactionService->getUserTransaction($params['transaction']);
    if (!$transaction) {
        redirectTo('/');
    }
    
    $this->validatorService->validateTransaction($_POST);
    $this->transactionService->update($_POST, $transaction['id']);
    redirectTo($_SERVER['HTTP_REFERER']);
  }

  public function delete(array $params) {
    $this->transactionService->delete((int)$params['transaction']);
    redirectTo('/');
  }
}