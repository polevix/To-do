<?php
// public/view_tasks.php

require_once '../src/controllers/ListController.php';
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$title = "Visualizar Tarefas";
require_once '../src/views/templates/header.php';

// Obter as tarefas incompletas do usuário autenticado
$listController = new ListController();
$userId = $_SESSION['user_id'];

// Função para formatar a data no padrão brasileiro (DD/MM/YYYY)
function formatDateToBR($date)
{
    return date('d/m/y', strtotime($date));
}

// Função para formatar a hora no padrão HH:MM (sem os segundos)
function formatTimeToBR($time)
{
    return date('H:i', strtotime($time));
}

// Verificar se o método getIncompleteTasks retorna dados corretamente
$tasks = $listController->getIncompleteTasks($userId);

// Função para escapar a saída HTML
function escape($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

?>
<section>
    <div class="dashboard-box">
        <div class="dashboard">
            <div class="view-header">
                <h2>Tarefas Pendentes</h2>
                <div class="top-bar">
                    <div class="search-bar">
                        <form method="GET" action="view_tasks.php">
                            <input type="text" name="search" placeholder="Pesquisar tarefas..." value="<?php echo isset($_GET['search']) ? escape($_GET['search']) : ''; ?>">
                            <button type="submit">Buscar</button>
                        </form>
                    </div>
                </div>
                <div class="view-tasks-header">
                    <table border="1" cellpadding="10" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Tarefa</th>
                                <th>Data</th>
                                <th>Hora</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <div class="view-body">
                <?php
                if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
                    $searchTerm = trim($_GET['search']);
                    $filteredTasks = array_filter($tasks, function ($task) use ($searchTerm) {
                        return stripos($task['task'], $searchTerm) !== false;
                    });
                } else {
                    $filteredTasks = $tasks;
                }
                ?>

                <?php if (!empty($filteredTasks)): ?>
                    <table border="1" cellpadding="10" cellspacing="0">
                        <tbody>
                            <?php foreach ($filteredTasks as $task): ?>
                                <tr>
                                    <td><?php echo html_entity_decode(escape($task['task'])); ?></td>
                                    <td><?php echo formatDateToBR(escape($task['date'])); ?></td>
                                    <td><?php echo formatTimeToBR(escape($task['time'])); ?></td>
                                    <td class="task-actions">
                                        <!-- Ícone Concluir -->
                                        <form class="complete-task" data-task-id="<?php echo escape($task['id']); ?>" style="border: none; background: none;">
                                            <ion-icon name="checkmark-done-outline"></ion-icon>
                                        </form>
                                        <!-- Ícone Editar -->
                                        <form class="edit-task" method="POST" action="edit_task.php">
                                            <input type="hidden" name="task_id" value="<?php echo escape($task['id']); ?>">
                                            <button type="submit" style="border: none; background: none;">
                                                <ion-icon name="create-outline"></ion-icon>
                                            </button>
                                        </form>
                                        <!-- Ícone Excluir -->
                                        <form class="delete-task" method="POST" action="delete_task.php" onsubmit="return confirm('Tem certeza que deseja excluir esta tarefa?');">
                                            <input type="hidden" name="task_id" value="<?php echo escape($task['id']); ?>">
                                            <button type="submit" style="border: none; background: none;">
                                                <ion-icon name="trash-outline"></ion-icon>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Nenhuma tarefa pendente encontrada.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Modal para mostrar mensagens -->
<div id="popup" class="popup">
    <p id="popup-message"></p>
    <button id="popup-close">OK</button>
</div>

<script>
    // Função para mostrar o modal
    function showModal(message, status) {
        const popup = document.getElementById('popup');
        const popupMessage = document.getElementById('popup-message');
        popupMessage.textContent = message;

        // Adicionar classe de sucesso ou erro
        if (status === 'success') {
            popup.classList.remove('popup-error');
            popup.classList.add('popup-success');
        } else {
            popup.classList.remove('popup-success');
            popup.classList.add('popup-error');
        }

        // Exibir o modal
        popup.style.display = 'block';
    }

    // Fechar modal
    document.getElementById('popup-close').addEventListener('click', function() {
        document.getElementById('popup').style.display = 'none';
        window.location.reload(); // Recarregar a página para atualizar as tarefas
    });

    // Função para enviar a requisição de concluir tarefa via AJAX
    document.querySelectorAll('.complete-task').forEach(function(button) {
        button.addEventListener('click', function() {
            const taskId = this.getAttribute('data-task-id');
            fetch('complete_task.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        'task_id': taskId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Mostrar a mensagem no modal
                    showModal(data.message, data.status);

                    // Substituir a URL atual no histórico (remove complete_task.php)
                    history.replaceState(null, '', 'view_tasks.php');
                })
                .catch(error => {
                    showModal('Erro ao concluir a tarefa.', 'error');
                });
        });
    });
</script>

<?php require_once '../src/views/templates/footer.php'; ?>