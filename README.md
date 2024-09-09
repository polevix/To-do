# Todo Application - Gerenciador de Tarefas

## Descrição do Projeto

Este projeto é uma aplicação web completa para gerenciamento de tarefas, onde os usuários podem criar, visualizar, editar, excluir e concluir tarefas. A aplicação também oferece a funcionalidade de gerar relatórios das tarefas concluídas em formato CSV. O projeto utiliza uma arquitetura de backend em Node.js e frontend com PHP, além de fazer uso de um banco de dados MySQL.

## Funcionalidades

- **Autenticação de Usuário**: Registro e login com autenticação via JWT.
- **Gerenciamento de Tarefas**: Criação, edição, exclusão e conclusão de tarefas com data e hora associadas.
- **Visualização de Tarefas Concluídas**: Exibe tarefas concluídas com a data e hora em que foram finalizadas.
- **Geração de Relatório CSV**: Geração de relatórios das tarefas concluídas que podem ser baixados pelo usuário.
- **Redefinição de Senha**: Envio de e-mail para redefinir senha de usuários.

## Tecnologias Utilizadas

### Frontend

- **PHP**: Utilizado no frontend para lidar com a renderização das páginas e gerenciamento de algumas operações do usuário.
- **HTML/CSS**: Estrutura e estilo das páginas, com uso de bibliotecas como Google Fonts.
- **JavaScript (AJAX)**: Utilizado para interação dinâmica entre o frontend e o backend, além de requisições assíncronas.
- **Ionicons**: Biblioteca de ícones utilizada nas interfaces visuais da aplicação.

### Backend

- **Node.js**: Utilizado para gerenciar a lógica do servidor e lidar com operações mais pesadas.
- **Express.js**: Framework utilizado para a criação das APIs e rotas.
- **MySQL**: Banco de dados relacional utilizado para armazenar informações dos usuários e das tarefas.
- **JWT (JSON Web Tokens)**: Autenticação de usuários para proteger rotas e informações sensíveis.
- **Argon2**: Algoritmo utilizado para hashing de senhas no cadastro e login de usuários.
- **Nodemailer**: Utilizado para enviar e-mails de redefinição de senha.

### Outras Tecnologias

- **Python**: Utilizado para gerar o relatório em formato CSV.
- **XAMPP**: Utilizado para simular um servidor PHP e MySQL local para o desenvolvimento da aplicação.
- **Git**: Controle de versão e gerenciamento de código.
- **GitHub**: Repositório remoto para o versionamento do projeto.

## Arquitetura do Projeto

```plaintext
TODO
│
├── backend
│   ├── .env
│   ├── db.js
│   ├── package-lock.json
│   ├── package.json
│   ├── server.js
│   ├── routes
│   │   ├── auth.js
│   │   └── tasks.js
│   └── middleware
│       └── authenticateToken.js
│
├── public
│   ├── create_task.php
│   ├── dashboard.php
│   ├── forgot_password.php
│   ├── generate_report.php
│   ├── generate_report.py
│   ├── login.php
│   ├── logout.php
│   ├── register.php
│   ├── view_complete_tasks.php
│   └── view_tasks.php
│
├── assets
│   ├── styles.css
│   └── imagens
│
├── src
│   ├── controllers
│   │   ├── AuthController.php
│   │   ├── ForgotPasswordController.php
│   │   ├── ListController.php
│   │   └── RegisterController.php
│   ├── models
│   │   ├── Database.php
│   │   └── User.php
│   └── scripts
│       └── generate_report.py
│   └── views
│       └── templates
│           ├── footer.php
│           └── header.php

## Instalação

### Requisitos

- **Node.js**: v14 ou superior
- **PHP**: v7.4 ou superior
- **MySQL**: v5.7 ou superior
- **Python**: v3.7 ou superior
- **XAMPP**: Para simulação de ambiente local com Apache e MySQL
- **Git**: Para controle de versão

### Passos para Instalação

1. **Clone o repositório**:
   ```bash
   git clone https://github.com/seu-usuario/seu-repositorio.git


### Instale as dependências do backend

No diretório `backend`, execute o seguinte comando:


```bash
npm install

### Criação do Banco de Dados MySQL

Crie o banco de dados `to_do` no MySQL e execute as queries de criação das tabelas:

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

```sql
CREATE TABLE lists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    task VARCHAR(255),
    time TIME,
    date DATE,
    completed TINYINT(1) DEFAULT 0,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);


### Configuração de Variáveis de Ambiente

No diretório `backend`, crie um arquivo `.env` e configure suas variáveis de ambiente, como exemplo:

```bash
JWT_SECRET=seuSegredoJWT
EMAIL_USER=seuEmail@example.com
EMAIL_PASSWORD=suaSenha
EMAIL_HOST=smtp.exemplo.com
EMAIL_PORT=587

### Iniciar o servidor Node.js

Execute o comando:

```bash
npm start

### Configurar e rodar o XAMPP

1. Certifique-se de que o **Apache** e o **MySQL** estão rodando.
2. Coloque o projeto na pasta `htdocs` do XAMPP.
3. Acesse o projeto pelo navegador.

### Acesse a aplicação

- **Frontend (PHP)**: [http://localhost/Todo/public/dashboard.php](http://localhost/Todo/public/dashboard.php)
- **Backend (Node.js API)**: [http://localhost:3000/api](http://localhost:3000/api)

### Uso

1. Crie uma conta clicando em **"Registrar"**.
2. Faça **login** para acessar o painel de controle.
3. Gerencie suas tarefas:
   - Criar, editar, excluir, e concluir tarefas.
4. Baixe o relatório das tarefas concluídas em **CSV**.
5. Utilize a funcionalidade de **redefinição de senha** caso tenha esquecido sua senha.
