<?php
// public/complete_task.php

require_once '../src/controllers/ListController.php';
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Você precisa estar autenticado para concluir a tarefa.']);
    exit();
}

$listController = new ListController();
$taskId = $_POST['task_id'];

// Concluir a tarefa e retornar o resultado via JSON
if ($listController->completeTask($taskId)) {
    echo json_encode(['status' => 'success', 'message' => 'Tarefa concluída com sucesso!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao concluir a tarefa.']);
}
exit();
