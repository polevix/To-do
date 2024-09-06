<?php
// src/controllers/AuthController.php
require_once '../src/models/Database.php';
require_once '../src/models/User.php';

session_start();

class AuthController
{
    private $db;
    private $user;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function login($email, $password)
    {
        // Sanitizar email antes de usá-lo na query
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        $stmt = $this->user->findUserByEmail($email);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Regenerate session ID to prevent session fixation attacks
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email']; // Optional: store user email
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Invalid email or password.";
        }
    }

    public function logout()
    {
        // Unset all session values
        $_SESSION = [];

        // Destroy the session
        session_destroy();

        // Redirect to login page
        header("Location: login.php");
        exit();
    }
}
