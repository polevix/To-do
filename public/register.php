<?php
// public/register.php

require_once '../src/controllers/RegisterController.php';

$title = "Register";
require_once '../src/views/templates/header.php';

$registerController = new RegisterController();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if ($registerController->register($name, $email, $password)) {
        header("Location: login.php?message=Usuário cadastrado com sucesso! Faça login.");
        exit();
    } else {
        $error = 'Ocorreu um erro ao tentar cadastrar. Tente novamente.';
    }
}
?>

<section>
    <div class="form-box">
        <div class="form-value">
            <form method="POST" action="register.php">
                <h2>Cadastro</h2>
                <?php if ($error): ?>
                    <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <div class="inputbox">
                    <ion-icon name="person-outline"></ion-icon>
                    <input type="text" name="name" required>
                    <label>Nome</label>
                </div>
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
                <button type="submit">Cadastrar</button>
            </form>
            <div class="register">
                <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
            </div>
        </div>
    </div>
</section>

<?php require_once '../src/views/templates/footer.php'; ?>