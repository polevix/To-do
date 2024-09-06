// Carrega variáveis de ambiente de um arquivo .env
require('dotenv').config();

// Importa módulos necessários
const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');  // Middleware para CORS
const db = require('./db');  // Conexão com o banco de dados
const authRoutes = require('./routes/auth');
const taskRoutes = require('./routes/tasks');
const { authenticateToken } = require('./middleware/authenticateToken');

// Cria uma instância do Express
const app = express();

// Configuração do CORS
app.use(cors({
    origin: 'http://127.0.0.1',  // Permite requisições do front-end
    methods: ['GET', 'POST'],     // Métodos HTTP permitidos
    allowedHeaders: ['Content-Type', 'Authorization']  // Cabeçalhos permitidos
}));

// Middlewares
app.use(bodyParser.json());  // Analisar corpos de requisições em JSON

// Rotas
app.use('/api/auth', authRoutes);  // Rotas de autenticação
app.use('/api/tasks', taskRoutes);  // Rotas de tarefas

// Middleware para capturar e logar erros não tratados
app.use((err, req, res, next) => {
    console.error('Unhandled error:', err.stack);  // Log do erro completo
    res.status(500).json({ message: 'Internal Server Error' });  // Retorna erro 500
});

// Rota de teste para verificar o funcionamento da API
app.get('/api/test', (req, res) => {
    res.json({ message: 'API is working!' });
});

// Inicia o servidor
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`Server is running on port ${PORT}`);
});
