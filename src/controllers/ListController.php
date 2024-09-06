<?php
// src/controllers/ListController.php

require_once '../src/models/Database.php';

class ListController
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Cria uma nova tarefa para um usuário.
     *
     * @param int $userId O ID do usuário.
     * @param string $task A descrição da tarefa.
     * @param string $time O horário da tarefa.
     * @param string $date A data da tarefa.
     * @return bool True em caso de sucesso, False em caso de falha.
     */
    public function createList($userId, $task, $time, $date)
    {
        try {
            $sql = "INSERT INTO lists (user_id, task, time, date) VALUES (:user_id, :task, :time, :date)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':task', $task, PDO::PARAM_STR);
            $stmt->bindParam(':time', $time, PDO::PARAM_STR);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            // Log de erro pode ser adicionado aqui para depuração
            return false;
        }
    }

    /**
     * Obtém as tarefas passadas para um usuário.
     *
     * @param int $userId O ID do usuário.
     * @return array Um array de tarefas passadas.
     */
    public function getPastLists($userId)
    {
        try {
            $sql = "SELECT * FROM lists WHERE user_id = :user_id AND date <= CURDATE() ORDER BY date DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log de erro pode ser adicionado aqui para depuração
            return [];
        }
    }

    /**
     * Alterna o status de conclusão de uma tarefa.
     *
     * @param int $taskId O ID da tarefa.
     * @return bool True em caso de sucesso, False em caso de falha.
     */
    public function toggleTaskStatus($taskId)
    {
        try {
            $sql = "UPDATE lists SET completed = !completed WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $taskId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            // Log de erro pode ser adicionado aqui para depuração
            return false;
        }
    }

    /**
     * Obtém as tarefas incompletas para um usuário.
     *
     * @param int $userId O ID do usuário.
     * @return array Um array de tarefas incompletas.
     */
    public function getIncompleteTasks($userId)
    {
        try {
            $sql = "SELECT * FROM lists WHERE user_id = :user_id AND completed = 0 ORDER BY date ASC, time ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log de erro pode ser adicionado aqui para depuração
            return [];
        }
    }
    public function getTaskById($taskId)
    {
        try {
            $sql = "SELECT * FROM lists WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $taskId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateTask($taskId, $task, $time, $date)
    {
        try {
            $sql = "UPDATE lists SET task = :task, time = :time, date = :date WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':task', $task, PDO::PARAM_STR);
            $stmt->bindParam(':time', $time, PDO::PARAM_STR);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            $stmt->bindParam(':id', $taskId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    public function completeTask($taskId)
    {
        try {
            $sql = "UPDATE lists SET completed = 1, completed_at = NOW() WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $taskId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    public function getCompletedTasks($userId)
    {
        try {
            $sql = "SELECT * FROM lists WHERE user_id = :user_id AND completed = 1 ORDER BY completed_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}
