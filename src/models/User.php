<?php
// src/models/User.php

class User
{
    private $conn;
    private $table_name = "users";

    public $id;
    public $name;
    public $email;
    public $password;
    public $reset_token;
    public $reset_token_expiry;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function findUserByEmail($email)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt;
    }

    public function storeResetToken($email, $token, $expiry)
    {
        $query = "UPDATE " . $this->table_name . " SET reset_token = :reset_token, reset_token_expiry = :expiry WHERE email = :email";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':reset_token', $token, PDO::PARAM_STR);
        $stmt->bindParam(':expiry', $expiry, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function verifyResetToken($token)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE reset_token = :token AND reset_token_expiry > NOW() LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":token", $token, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($name, $email, $password)
    {
        $query = "INSERT INTO " . $this->table_name . " (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $this->conn->prepare($query);

        // Hash da senha antes de armazenar
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);

        return $stmt->execute();
    }
}
