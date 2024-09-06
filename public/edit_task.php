<?php
// public/edit_task.php

require_once '../src/controllers/ListController.php';
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$listController = new ListController();
$userId = $_SESSION['user_id'];

// Verifica se o ID da tarefa foi passado
if (!isset($_POST['task_id'])) {
    header("Location: view_tasks.php?error=ID da tarefa não fornecido.");
    exit();
}

// Obter os dados da tarefa pelo ID
$taskId = $_POST['task_id'];
$task = $listController->getTaskById($taskId);

// Verifica se a tarefa existe e pertence ao usuário
if (!$task || $task['user_id'] != $userId) {
    header("Location: view_tasks.php?error=Tarefa não encontrada ou você não tem permissão.");
    exit();
}

// Atualizar tarefa se o formulário for submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task'], $_POST['time'], $_POST['date'])) {
    $task = filter_input(INPUT_POST, 'task', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $time = filter_input(INPUT_POST, 'time', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if ($listController->updateTask($taskId, $task, $time, $date)) {
        header("Location: view_tasks.php?message=Tarefa atualizada com sucesso!");
        exit();
    } else {
        $error = "Erro ao atualizar a tarefa.";
    }
}

$title = "Editar Tarefa";
require_once '../src/views/templates/header.php';
?>

<section>
    <div class="form-box">
        <div class="form-value">
            <form method="POST" action="edit_task.php">
                <h2>Editar Tarefa</h2>
                <?php if (isset($error)): ?>
                    <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <input type="hidden" name="task_id" value="<?php echo htmlspecialchars($taskId); ?>">
                <div class="inputbox">
                    <ion-icon name="create-outline"></ion-icon>
                    <input type="text" name="task" value="<?php echo htmlspecialchars($task['task']); ?>" required>
                    <label>Tarefa</label>
                </div>
                <div class="inputbox">
                    <ion-icon name="time-outline"></ion-icon>
                    <input type="time" name="time" required class="no-default-icon" value="<?php echo htmlspecialchars($task['time']); ?>" required>
                    <label>Horário</label>
                </div>
                <div class="inputbox">
                    <ion-icon name="calendar-outline"></ion-icon>
                    <input type="date" name="date" required class="no-default-icon" value="<?php echo htmlspecialchars($task['date']); ?>" required>
                    <label>Data</label>
                </div>
                <button type="submit">Atualizar</button>
            </form>
        </div>
    </div>
</section>

<?php require_once '../src/views/templates/footer.php'; ?>