<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use Framework\Exceptions\ValidationException;

class UserService {

    public function __construct(private Database $db) {

    }

    public function isEmailTaken(string $email) {
        $emailCount = $this->db->query("SELECT COUNT(*) FROM users WHERE email = :email", ['email'=> $email])->count();
        if ($emailCount > 0) {
            
            throw new ValidationException(['email' => ['Email taken']]);
        }
    }
    public function create(array $formData) {

        $password = password_hash($formData['password'], PASSWORD_BCRYPT, ['cost'=> 12]);
        $sql = "INSERT INTO users (email, password, age, country, social_media_url) VALUES(:email, :password, :age, :country, :url)";
        $data = [
            'email' => $formData['email'],
            'password' => $password,
            'age' => $formData['age'],
            'country' => $formData['country'],
            'url' => $formData['socialMediaURL']
        ];

        $this->db->query($sql, $data);
    }

    public function login(array $formData) {
        $user = $this->db->query("SELECT * FROM users WHERE email=:email", ['email'=>$formData['email']])->find();

        //$user['password'] ?? '' - it is for the case that $user is false beacuse there is no user and the fetch method return false
        $passwordsMatch = password_verify(
            $formData['password'],
            $user['password'] ?? ''
        );

        if(!$user || !$passwordsMatch) {
            throw new ValidationException(['password' => [ 'Invalid email or password.']]);
        }

        $_SESSION['user'] = $user['id'];
    }
}