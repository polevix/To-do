// Importação de módulos necessários
const express = require('express');
const argon2 = require('argon2');
const jwt = require('jsonwebtoken');
const nodemailer = require('nodemailer');
const { authenticateToken } = require('../middleware/authenticateToken');
const { body, validationResult } = require('express-validator');
const db = require('../db');
const router = express.Router();

// Rota para registrar um novo usuário
router.post('/register', [
    body('name').notEmpty().withMessage('Nome é obrigatório').trim().escape(),
    body('email').isEmail().withMessage('Email inválido').normalizeEmail(),
    body('password').isLength({ min: 6 }).withMessage('Senha deve ter no mínimo 6 caracteres').trim().escape()
], async (req, res) => {
    console.log('Requisição recebida para /register com corpo:', req.body);

    const errors = validationResult(req);
    if (!errors.isEmpty()) {
        console.log('Erros de validação:', errors.array());
        return res.status(400).json({ errors: errors.array() });
    }

    const { name, email, password } = req.body;

    try {
        // Verificar se o email já está registrado
        const [existingUser] = await db.query('SELECT * FROM users WHERE email = ?', [email]);

        if (existingUser.length > 0) {
            return res.status(400).json({ message: 'Email já está em uso' });
        }

        // Criar o hash da senha
        const hashedPassword = await argon2.hash(password);

        // Inserir o novo usuário no banco de dados
        const [result] = await db.query('INSERT INTO users (name, email, password) VALUES (?, ?, ?)', [name, email, hashedPassword]);

        if (result.affectedRows === 1) {
            console.log('Usuário registrado com sucesso:', { name, email });
            return res.status(201).json({ message: 'Usuário registrado com sucesso' });
        } else {
            console.log('Falha ao inserir usuário no banco de dados');
            return res.status(500).json({ message: 'Falha ao registrar o usuário' });
        }
    } catch (error) {
        console.error('Erro durante o registro do usuário:', error);
        return res.status(500).json({ error: error.message });
    }
});

// Rota para login de usuários
router.post('/login', [
    body('email').isEmail().withMessage('Email inválido').normalizeEmail(),
    body('password').exists().withMessage('Senha é obrigatória')
], async (req, res) => {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
        return res.status(400).json({ errors: errors.array() });
    }

    const { email, password } = req.body;

    try {
        const [user] = await db.query('SELECT * FROM users WHERE email = ?', [email]);

        if (user.length === 0) {
            return res.status(400).json({ message: 'Email ou senha inválidos' });
        }

        const isMatch = await argon2.verify(user[0].password, password);

        if (!isMatch) {
            return res.status(400).json({ message: 'Email ou senha inválidos' });
        }

        // Gerar o token JWT
        const token = jwt.sign({ id: user[0].id }, process.env.JWT_SECRET, { expiresIn: '1h' });

        return res.status(200).json({ token });
    } catch (error) {
        return res.status(500).json({ error: error.message });
    }
});

// Rota para envio de email de redefinição de senha
router.post('/forgot-password', [
    body('email').isEmail().withMessage('Email inválido').normalizeEmail()
], async (req, res) => {
    const { email } = req.body;

    try {
        const [user] = await db.query('SELECT * FROM users WHERE email = ?', [email]);

        if (user.length === 0) {
            return res.status(400).json({ message: 'Email não encontrado' });
        }

        const token = jwt.sign({ id: user[0].id }, process.env.JWT_SECRET, { expiresIn: '15m' });

        const transporter = nodemailer.createTransport({
            host: process.env.EMAIL_HOST,
            port: process.env.EMAIL_PORT,
            auth: {
                user: process.env.EMAIL_USER,
                pass: process.env.EMAIL_PASSWORD
            }
        });

        const mailOptions = {
            from: process.env.EMAIL_USER,
            to: email,
            subject: 'Redefinição de Senha',
            text: `Você solicitou a redefinição de senha. Clique no link para redefinir sua senha: http://localhost:3000/reset-password?token=${token}`
        };

        await transporter.sendMail(mailOptions);

        return res.status(200).json({ message: 'Link de redefinição de senha enviado para o email' });
    } catch (error) {
        return res.status(500).json({ error: error.message });
    }
});

// Rota para redefinição de senha
router.post('/reset-password', [
    body('token').exists().withMessage('Token é obrigatório'),
    body('newPassword').isLength({ min: 6 }).withMessage('A nova senha deve ter no mínimo 6 caracteres').trim().escape()
], async (req, res) => {
    const { token, newPassword } = req.body;

    try {
        const decoded = jwt.verify(token, process.env.JWT_SECRET);

        const hashedPassword = await argon2.hash(newPassword);

        await db.query('UPDATE users SET password = ? WHERE id = ?', [hashedPassword, decoded.id]);

        return res.status(200).json({ message: 'Senha redefinida com sucesso' });
    } catch (error) {
        return res.status(500).json({ error: error.message });
    }
});

// Rota protegida para o dashboard
router.get('/dashboard', authenticateToken, (req, res) => {
    return res.json({ message: 'Bem-vindo ao Dashboard!', user: req.user });
});

module.exports = router;
