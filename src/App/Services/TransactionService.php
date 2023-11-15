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

    public function getUserTransactions() {

        //%_ the caracters wh want to escape
        $searchTerm = addcslashes($_GET['s'] ?? '', '%_');
        
        $sql = "SELECT *, DATE_FORMAT(date, '%Y-%m-%d') AS formatted_date FROM transactions
                WHERE user_id = :user_id 
                AND description LIKE :description";
                
        $data = [
            'user_id' => $_SESSION['user'],
            'description' => "%{$searchTerm}%"
        ];
        $transactions = $this->db->query($sql, $data)->findAll();
        return $transactions;
    }
}