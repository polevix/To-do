<?php
// public/create_task.php

require_once '../src/controllers/ListController.php';
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Verifica o método da requisição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica a validade do token CSRF
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $_SESSION['error'] = 'Token CSRF inválido.';
        header("Location: dashboard.php");
        exit();
    }

    $listController = new ListController();
    $userId = $_SESSION['user_id'];

    // Usa filter_input com FILTER_SANITIZE_SPECIAL_CHARS para evitar ataques XSS
    $task = filter_input(INPUT_POST, 'task', FILTER_DEFAULT);
    $time = filter_input(INPUT_POST, 'time', FILTER_DEFAULT);
    $date = filter_input(INPUT_POST, 'date', FILTER_DEFAULT);

    // Remove os segundos do valor de tempo
    if ($time) {
        $time = substr($time, 0, 5);
    }

    // Verifica se a tarefa foi criada com sucesso
    if ($listController->createList($userId, $task, $time, $date)) {
        $_SESSION['message'] = 'Tarefa criada com sucesso!';
    } else {
        $_SESSION['error'] = 'Erro ao criar a tarefa.';
    }

    header("Location: dashboard.php");
    exit();
}

// Gera o token CSRF se não existir
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$title = "Criar Nova Tarefa";
require_once '../src/views/templates/header.php';
?>

<section>
    <div class="form-box">
        <div class="form-value">
            <form method="POST" action="create_task.php">
                <h2>Criar Nova Lista</h2>
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

                <div class="inputbox">
                    <ion-icon name="clipboard-outline"></ion-icon>
                    <input type="text" name="task" required>
                    <label>O que será feito</label>
                </div>

                <div class="inputbox">
                    <ion-icon name="time-outline"></ion-icon>
                    <input type="time" name="time" required class="no-default-icon">
                </div>

                <div class="inputbox">
                    <ion-icon name="calendar-outline"></ion-icon>
                    <input type="date" name="date" required class="no-default-icon">
                </div>

                <button type="submit">Criar</button>
            </form>
        </div>
    </div>
</section>

<?php require_once '../src/views/templates/footer.php'; ?>