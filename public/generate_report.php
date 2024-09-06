<?php
// public/generate_report.php

// Executa o script Python para gerar o relatório
$command = escapeshellcmd('python ../src/scripts/generate_report.py');
$output = shell_exec($command);

// Caminho do arquivo CSV
$csvFilePath = __DIR__ . '/../public/temp/relatorio.csv'; // Caminho no sistema
$csvDownloadPath = '/Todo/public/temp/relatorio.csv'; // Caminho para o navegador

// Verifica se o arquivo foi criado
if (file_exists($csvFilePath)) {
    echo json_encode(['status' => 'success', 'file' => $csvDownloadPath]);
    
} else {
    echo json_encode(['status' => 'error', 'message' => 'Falha ao gerar o relatório.']);
}
