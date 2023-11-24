<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use Framework\Exceptions\ValidationException;

class ChartService {
    public function __construct(private Database $db) {

    }

        //need to change the location(after creation) to Chart service
        public function getTotalAmoutByCategory(string $category_name) {

            $sql = "SELECT transactions_categories.category_name as category_name, SUM(transactions.amount) as total
                    FROM transactions_categories 
                    JOIN transactions ON transactions.id = transactions_categories.transaction_id
                    WHERE category_name = :category_name
                    AND transactions_categories.user_id = :user_id
                    GROUP BY category_name";
            $data = [
                'category_name' => $category_name,
                'user_id' => $_SESSION['user']
            ];
            
            $result = $this->db->query($sql, $data);
    
            if ($result->count() == 0) {
                return ['total' => 0];
            }
            else {
                return $this->db->query($sql, $data)->find();
            }
        }
    
        //need to change the location(after creation) to Chart service
        public function getTotalAmoutByMonth(string $month) {
            $year = date("Y");
            $sql = "SELECT SUM(transactions.amount) as total FROM transactions 
                    WHERE transactions.user_id = :user_id 
                    AND MONTH(date) = :month
                    AND YEAR(date) = :year
                    GROUP BY MONTH(date)";
            $data = [
                'user_id' => $_SESSION['user'],
                'month' => $month,
                'year' => $year
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