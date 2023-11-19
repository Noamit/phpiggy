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

    $all_categories = $this->categoryService->getCategories();

    $all_categories = array_map(function(array $category) {
      return $category['category_name'];
    }, $all_categories);

    $transaction_categories = $this->categoryService->getTransactionCategories($params['transaction']);
    $transaction_categories = array_map(function(array $category) {
      return $category['category_name'];
    }, $transaction_categories);

    $not_transaction_categories = array_diff($all_categories, $transaction_categories);

    echo $this->view->render("transactions/edit.php",
    ['transaction' => $transaction,
     'transaction_categories' => $transaction_categories,
      'not_transaction_categories' => $not_transaction_categories]); 
  }


  public function edit(array $params) {
    $transaction = $this->transactionService->getUserTransaction($params['transaction']);
    if (!$transaction) {
        redirectTo('/');
    }
    
    $this->validatorService->validateTransaction($_POST);
    $this->transactionService->update($_POST, $transaction['id']);

    // need to update the transactions_categories table
    $excludedFields = ['description', 'amount', 'date'];

    //array of category names
    $prev = $this->categoryService->getTransactionCategories($params['transaction']);
    $prev = array_map(function(array $category) {
      return $category['category_name'];
    }, $prev);

    $transaction_categories = array_diff_key($_POST, array_flip($excludedFields));

    $new_categories = array_diff_key($transaction_categories, array_flip($prev));

    $delete_categories = array_diff_key(array_flip($prev), $transaction_categories);

    foreach($new_categories as $category_name=>$val) {
      $category = $this->categoryService->getCategory($category_name);
      // check if category exists and add transaction to category if category exists
      if ($category) {
        $this->categoryService->addTransactionToCategory($category_name, $params['transaction']);
      }
    }

    foreach($delete_categories as $category_name=>$val) {
      $category = $this->categoryService->getCategory($category_name);
      // check if category exists and add transaction to category if category exists
      if ($category) {
        $this->categoryService->deleteTransactionFromCategory($category_name, $params['transaction']);
      }
    }

    redirectTo($_SERVER['HTTP_REFERER']);
  }

  public function delete(array $params) {
    $this->transactionService->delete((int)$params['transaction']);
    redirectTo('/');
  }
}