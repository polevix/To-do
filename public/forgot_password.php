<?php
// public/forgot_password.php

$title = "Redefinir Senha";
require_once '../src/views/templates/header.php';
?>

<section>
    <div class="form-box">
        <div class="form-value">
            <form id="forgotPasswordForm">
                <h2>Redefinir Senha</h2>
                <div class="inputbox">
                    <ion-icon name="mail-outline"></ion-icon>
                    <input type="email" name="email" required>
                    <label>Email</label>
                </div>
                <button type="submit">Enviar Link</button>
            </form>
            <div class="register">
                <p>Lembrou sua senha? <a href="login.php">Faça login</a></p>
            </div>
        </div>
    </div>
</section>

<script>
    document.getElementById('forgotPasswordForm').addEventListener('submit', async function(event) {
        event.preventDefault();

        const email = event.target.email.value;

        try {
            const response = await fetch('http://localhost:3000/api/auth/forgot-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email })
            });

            const data = await response.json();

            if (response.ok) {
                alert('Um link de redefinição de senha foi enviado para o seu email.');
            } else {
                alert(data.message || 'Erro ao enviar o link de redefinição de senha.');
            }
        } catch (error) {
            console.error('Erro:', error);
            alert('Ocorreu um erro ao tentar enviar o link de redefinição.');
        }
    });
</script>

<?php require_once '../src/views/templates/footer.php'; ?>
