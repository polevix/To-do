// Importação de módulos necessários
const express = require('express');
const { body, validationResult } = require('express-validator');
const { authenticateToken } = require('../middleware/authenticateToken');
const db = require('../db');
const router = express.Router();

// Rota para criação de tarefas
router.post(
    '/create',
    authenticateToken,  // Middleware para autenticação JWT
    [
        // Validações dos campos da tarefa
        body('task')
            .notEmpty().withMessage('A tarefa é obrigatória')
            .trim().escape(),  // Remove espaços extras e sanitiza
        body('time')
            .notEmpty().withMessage('O horário é obrigatório')
            .trim().escape(),  // Remove espaços extras e sanitiza
        body('date')
            .notEmpty().withMessage('A data é obrigatória')
            .isDate().withMessage('Data inválida')  // Verifica se é uma data válida
    ],
    async (req, res) => {
        // Validação de erros
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({ errors: errors.array() });
        }

        // Extração de dados do corpo da requisição
        const { task, time, date } = req.body;
        const userId = req.user.id;

        try {
            // Query para inserir tarefa no banco de dados
            const sql = 'INSERT INTO lists (user_id, task, time, date) VALUES (?, ?, ?, ?)';
            const [result] = await db.query(sql, [userId, task, time, date]);

            // Verificação de sucesso na inserção
            if (result.affectedRows === 1) {
                return res.status(201).json({ message: 'Tarefa criada com sucesso' });
            } else {
                return res.status(500).json({ message: 'Erro ao criar a tarefa' });
            }
        } catch (error) {
            // Captura e retorna erro do banco de dados
            return res.status(500).json({ error: error.message });
        }
    }
);

// Exporta o roteador
module.exports = router;
