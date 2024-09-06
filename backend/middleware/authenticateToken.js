const jwt = require('jsonwebtoken');

// Middleware para autenticação de token JWT
function authenticateToken(req, res, next) {
    // Extrair o token do cabeçalho de autorização
    const authHeader = req.headers['authorization'];
    const token = authHeader && authHeader.split(' ')[1];

    // Se o token não for encontrado, retornar status 401 (não autorizado)
    if (!token) {
        return res.status(401).json({ message: 'Token de autenticação não fornecido' });
    }

    // Verificar a validade do token
    jwt.verify(token, process.env.JWT_SECRET, (err, user) => {
        // Se o token for inválido ou expirado, retornar status 403 (proibido)
        if (err) {
            return res.status(403).json({ message: 'Token inválido ou expirado' });
        }

        // Anexar as informações do usuário à requisição
        req.user = user;

        // Chamar o próximo middleware ou rota
        next();
    });
}

module.exports = { authenticateToken };
