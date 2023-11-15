<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use Framework\Exceptions\ValidationException;

class TransactionService {
    public function __construct(private Database $db) {

    }

    public function create(array $formData) {

        $formattedDate = "{$formData['date']} 00:00:00";
        $sql = "INSERT INTO transactions (user_id, description, amount, date) VALUES(:user_id, :description, :amount, :date)";
        $data = [
            'user_id' => $_SESSION['user'],
            'description' => $formData['description'],
            'amount' => $formData['amount'],
            'date' => $formattedDate
        ];

        $this->db->query($sql, $data);
    }

    public function update(array $formData, int $id) {

        $formattedDate = "{$formData['date']} 00:00:00";
        $sql = "UPDATE transactions
                SET description = :description , amount = :amount, date = :date
                WHERE id = :id
                AND user_id = :user_id";
        $data = [
            'description' => $formData['description'],
            'amount' => $formData['amount'],
            'date' => $formattedDate,
            'id' => $id,
            'user_id' => $_SESSION['user']
        ];

        $this->db->query($sql, $data);
    }

    public function delete(int $id) {

        $sql = "DELETE FROM transactions
                WHERE id = :id
                AND user_id = :user_id";
        $data = [
            'id' => $id,
            'user_id' => $_SESSION['user']
        ];

        $this->db->query($sql, $data);
    }

    public function getUserTransactions(int $length, int $offset) {

        //%_ the caracters wh want to escape
        $searchTerm = addcslashes($_GET['s'] ?? '', '%_');
        
        $sql = "SELECT *, DATE_FORMAT(date, '%Y-%m-%d') AS formatted_date FROM transactions
                WHERE user_id = :user_id 
                AND description LIKE :description
                LIMIT {$length} OFFSET {$offset}";
                
        $params = [
            'user_id' => $_SESSION['user'],
            'description' => "%{$searchTerm}%"
        ];

        $transactions = $this->db->query($sql, $params)->findAll();
        
        $sql = "SELECT COUNT(*)
                FROM transactions
                WHERE user_id = :user_id
                AND description LIKE :description";

        $transactionCount = $this->db->query($sql, $params)->count();

        return [$transactions, $transactionCount];
    }

    public function getUserTransaction (string $id) {
        $sql = "SELECT *, DATE_FORMAT(date, '%Y-%m-%d') AS formatted_date FROM transactions
                WHERE user_id = :user_id 
                AND id = :id";
                
        $params = [
            'user_id' => $_SESSION['user'],
            'id' => $id
        ];
        
        return $this->db->query($sql, $params)->find();
    }
}