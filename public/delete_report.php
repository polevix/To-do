<?php
// public/delete_report.php

$csvFilePath = __DIR__ . '/../public/temp/relatorio.csv'; // Caminho no sistema

if (file_exists($csvFilePath)) {
    unlink($csvFilePath); // Excluir o arquivo
    echo json_encode(['status' => 'success', 'message' => 'Arquivo excluído com sucesso.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Arquivo não encontrado.']);
}
