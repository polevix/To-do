<?php
// public/view_complete_tasks.php

require_once '../src/controllers/ListController.php';
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$title = "Visualizar Tarefas Concluídas";
require_once '../src/views/templates/header.php';

// Obter as tarefas concluídas do usuário autenticado
$listController = new ListController();
$userId = $_SESSION['user_id'];
$tasks = $listController->getCompletedTasks($userId); // Novo método para pegar tarefas concluídas

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
                <h2>Tarefas Concluídas</h2>
                <div class="top-bar">
                    <div class="search-bar">
                        <form method="GET" action="view_complete_tasks.php">
                            <input type="text" name="search" placeholder="Pesquisar tarefas..." value="<?php echo isset($_GET['search']) ? escape($_GET['search']) : ''; ?>">
                            <button type="submit">Buscar</button>
                        </form>
                    </div>
                </div>
                <div class="view-complete-tasks-header">
                    <table border="1" cellpadding="10" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Tarefa</th>
                                <th>Data</th>
                                <th>Hora</th>
                                <th>Concluído</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <div class="view-body">
                <?php
                // Filtragem das tarefas com base na pesquisa, se aplicável
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
                    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
                        <tbody>
                            <?php foreach ($filteredTasks as $task): ?>
                                <tr>
                                    <td><?php echo html_entity_decode(escape($task['task'])); ?></td>
                                    <td><?php echo formatDateToBR(escape($task['date'])); ?></td>
                                    <td><?php echo formatTimeToBR(escape($task['time'])); ?></td>
                                    <td>
                                        <?php if ($task['completed_at']): ?>
                                            <?php echo date('d/m/y H:i', strtotime($task['completed_at'])); ?> <!-- Exibe a data e hora de conclusão -->
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Nenhuma tarefa concluída encontrada.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once '../src/views/templates/footer.php'; ?>