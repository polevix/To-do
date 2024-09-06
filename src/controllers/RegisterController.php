<?php
// src/controllers/RegisterController.php
require_once '../src/models/Database.php';
require_once '../src/models/User.php';

class RegisterController
{
    private $db;
    private $user;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function register($name, $email, $password)
    {
        // Verifica se o e-mail já está cadastrado
        $stmt = $this->user->findUserByEmail($email);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            echo "Este e-mail já está registrado.";
            return false;
        }

        // Cria o novo usuário com nome, email e senha
        if ($this->user->createUser($name, $email, $password)) {
            header("Location: login.php");
            return true;
        } else {
            echo "Erro ao registrar o usuário.";
            return false;
        }
    }
}
