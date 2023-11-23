<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use Framework\Exceptions\ValidationException;

class CategoryService {
    public function __construct(private Database $db) {

    }

    public function create(array $formData) {
        $sql = "INSERT INTO categories(category_name) VALUES(:category_name)";
        $data = [
            'category_name' => $formData['category_name']
        ];

        $this->db->query($sql, $data);
    }


    public function getCategories() {
        $sql = "SELECT * FROM categories";
                
        return $this->db->query($sql)->findAll();
    }

    public function getCategory(string $category_name) {
        $sql = "SELECT * FROM categories WHERE category_name = :category_name";
        $data = [
            'category_name' => $category_name
        ];
        
        return $this->db->query($sql, $data)->find();
    }

    public function getTransactionCategories(string $transaction_id) {
        $sql = "SELECT category_name FROM transactions_categories WHERE transaction_id = :transaction_id";
        $data = [
            'transaction_id' => $transaction_id
        ];


        return $this->db->query($sql, $data)->findAll();
    }

    public function addTransactionToCategory(string $category_name, string $transaction_id) {
        $sql = "INSERT INTO transactions_categories(category_name, transaction_id) VALUES(:category_name, :transaction_id)";
        $data = [
            'category_name' => $category_name,
            'transaction_id' => $transaction_id
        ];

        $this->db->query($sql, $data);
    }

    public function getTransactionsCategory(int $length, int $offset) {

        $searchTerm = addcslashes($_GET['s'] ?? '', '%_');
        $category_name = $_GET['c'] ?? '';
        $category_name = $_GET['c'] === 'all' ? '' : $_GET['c'];
        $fromTerm = $_GET['from'] ?? null;
        $toTerm = $_GET['to'] ?? null;

        $sql_select = "SELECT *, DATE_FORMAT(date, '%Y-%m-%d') AS formatted_date ";
        $sql_count = "SELECT COUNT(*) ";
        
        $sql = "FROM transactions
        JOIN transactions_categories ON transactions.id = transactions_categories.transaction_id
        WHERE category_name LIKE :category_name
        AND description LIKE :description";

        $params = [
            'category_name' => "%{$category_name}%",
            'description' => "%{$searchTerm}%"
        ];

        if ($fromTerm != null) {
            $sql .= " AND DATE(date) >= :fromTerm ";
            $params['fromTerm'] = $fromTerm;

        } 
        if ($toTerm != null) {
            $sql .= " AND DATE(date) <= :toTerm ";
            $params['toTerm'] = $toTerm;
        }
        
        $sql_limit = " LIMIT {$length} OFFSET {$offset}";

        $transactions = $this->db->query($sql_select . $sql . $sql_limit , $params)->findAll();

        $transactions = array_map(function(array $transaction) {
            $sql = "SELECT * FROM receipts WHERE transaction_id = :transaction_id";
            $data = ['transaction_id' => $transaction['id']];
            
            $transaction['receipts'] = $this->db->query($sql, $data)->findAll();
            return $transaction;
        } , $transactions);
        
        $transactions = array_map(function(array $transaction) {
            $sql = "SELECT * FROM transactions_categories WHERE transaction_id = :transaction_id";
            $data = ['transaction_id' => $transaction['id']];
            
            $transaction['categories'] = $this->db->query($sql, $data)->findAll();
            return $transaction;
        } , $transactions);
        
        $transactionCount = $this->db->query($sql_count . $sql, $params)->count();
        return [$transactions, $transactionCount];
    }

    public function deleteTransactionFromCategory(string $category_name, string $transaction_id) {
        $sql = "DELETE FROM transactions_categories
                WHERE transaction_id = :transaction_id 
                AND category_name = :category_name";
        $data = [
            'category_name' => $category_name,
            'transaction_id' => $transaction_id
        ];

        $this->db->query($sql, $data);
    }
    
    public function getTotalAmout(string $category_name) {

        
        $sql = "SELECT transactions_categories.category_name as category_name, SUM(transactions.amount) as total
                FROM transactions_categories 
                JOIN transactions ON transactions.id = transactions_categories.transaction_id
                WHERE category_name = :category_name
                GROUP BY category_name";
        $data = [
            'category_name' => $category_name
        ];
        
        $result = $this->db->query($sql, $data);

        if ($result->count() == 0) {
            return ['total' => 0];
        }
        else {
            return $this->db->query($sql, $data)->find();
        }
    }
}