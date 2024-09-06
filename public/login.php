<?php
// public/login.php

require_once '../src/controllers/AuthController.php';

$title = "Login";
require_once '../src/views/templates/header.php';

$authController = new AuthController();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if ($authController->login($email, $password)) {
        header("Location: dashboard.php");
        exit();
    } else {
        $error = 'Email ou senha inválidos. Tente novamente.';
    }
}
?>

<section>
    <div class="form-box">
        <div class="form-value">
            <form method="POST" action="login.php">
                <h2>Login</h2>
                <?php if ($error): ?>
                    <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <div class="inputbox">
                    <ion-icon name="mail-outline"></ion-icon>
                    <input type="email" name="email" required>
                    <label>Email</label>
                </div>
                <div class="inputbox">
                    <ion-icon name="lock-closed-outline"></ion-icon>
                    <input type="password" name="password" required>
                    <label>Senha</label>
                </div>
                <div class="forget">
                    <label><input type="checkbox" name="remember"> Lembrar-me</label>
                    <a href="forgot_password.php">Esqueceu a senha?</a>
                </div>
                <button type="submit">Log in</button>
            </form>
            <div class="register">
                <p>Não tem uma conta? <a href="register.php">Cadastre-se</a></p>
            </div>
        </div>
    </div>
</section>

<?php require_once '../src/views/templates/footer.php'; ?>