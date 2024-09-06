<?php
// src/controllers/ForgotPasswordController.php
require_once '../src/config/database.php';
require_once '../src/models/User.php';

class ForgotPasswordController
{
    private $db;
    private $user;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function sendResetLink($email)
    {
        // Sanitizar email antes de usá-lo na query
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        $stmt = $this->user->findUserByEmail($email);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false;
        }

        // Gerar token de redefinição
        $token = bin2hex(random_bytes(50));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour')); // O token expira em 1 hora

        // Armazenar token e expiração no banco de dados
        if (!$this->user->storeResetToken($email, $token, $expiry)) {
            return false;
        }

        // Enviar email com o link de redefinição (simplificado)
        $resetLink = "http://localhost/login-system/public/reset_password.php?token=" . $token;

        // Aqui você enviaria um email real com o $resetLink
        // Para simplificação, vamos apenas exibir o link
        echo "Link de redefinição: " . htmlspecialchars($resetLink, ENT_QUOTES, 'UTF-8');

        return true;
    }
}
