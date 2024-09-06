<?php
// public/dashboard.php

// Proteção contra CSRF
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$title = "Dashboard";
require_once '../src/views/templates/header.php';

// Funções auxiliares para exibir mensagens e manipular dados
function displayMessage($message)
{
    echo "<p style='color: green;'>" . htmlspecialchars($message) . "</p>";
}

function displayError($error)
{
    echo "<p style='color: red;'>" . htmlspecialchars($error) . "</p>";
}

// Verifica se há mensagens ou erros para exibir
if (isset($_GET['message'])) {
    displayMessage($_GET['message']);
}

if (isset($_GET['error'])) {
    displayError($_GET['error']);
}

// Verifica se há uma mensagem de erro ou sucesso
if (isset($_SESSION['message']) || isset($_SESSION['error'])):
    $message = isset($_SESSION['message']) ? $_SESSION['message'] : $_SESSION['error'];
    $messageType = isset($_SESSION['message']) ? 'popup-success' : 'popup-error';

    // Limpa a sessão após exibir a mensagem
    unset($_SESSION['message']);
    unset($_SESSION['error']);
?>
<?php endif; ?>

<section>
    <div class="form-box">
        <div class="form-value">
            <h2>Bem-vindo(a)</h2>
            <div id="menu">
                <div class="menu-item">
                    <form method="GET" action="create_task.php">
                        <button type="submit" class="form-value-button">Criar Nova Tarefa</button>
                    </form>
                </div>
                <div class="menu-item">
                    <form method="GET" action="view_tasks.php">
                        <button type="submit" class="dashboard-button">Tarefas Pendentes</button>
                    </form>
                </div>
                <div class="menu-item">
                    <form method="GET" action="view_complete_tasks.php">
                        <button type="submit" class="dashboard-button">Tarefas Concluídas</button>
                    </form>
                </div>
                <div class="menu-item">
                    <button id="generateReportBtn" class="dashboard-button">Gerar Relatório CSV</button>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    window.onload = function() {
        let message = "<?php echo $message; ?>";
        let messageType = "<?php echo $messageType; ?>";

        // Cria o pop-up
        let popup = document.createElement("div");
        popup.classList.add("popup", messageType);
        popup.innerHTML = `
                <p>${message}</p>
                <button onclick="closePopup()">OK</button>
            `;

        document.body.appendChild(popup);
        popup.style.display = "block"; // Mostra o pop-up

        // Função para fechar o pop-up
        window.closePopup = function() {
            popup.style.display = "none"; // Oculta o pop-up
            document.body.removeChild(popup); // Remove o pop-up do DOM
        }
    };
</script>
<script>
    document.getElementById('generateReportBtn').addEventListener('click', function() {
        fetch('generate_report.php', {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const downloadLink = document.createElement('a');
                    downloadLink.href = data.file;
                    downloadLink.download = 'relatorio.csv';
                    document.body.appendChild(downloadLink);
                    downloadLink.click();
                    document.body.removeChild(downloadLink);

                    // Após o download, chamar a requisição para excluir o arquivo
                    fetch('delete_report.php', {
                            method: 'POST'
                        })
                        .then(response => response.json())
                        .then(result => {
                            if (result.status === 'success') {
                                console.log(result.message); // Arquivo excluído com sucesso
                            } else {
                                console.error(result.message); // Erro ao excluir o arquivo
                            }
                        });
                } else {
                    alert(data.message || 'Erro ao gerar o relatório.');
                }
            })
            .catch(error => {
                console.error('Erro ao gerar o relatório:', error);
            });
    });
</script>



<?php require_once '../src/views/templates/footer.php'; ?>