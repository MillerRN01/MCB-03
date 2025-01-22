<?php
require_once 'conexao_db.php';
session_start();

$error_message = ""; // Variável para armazenar mensagens de erro

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    try {
        // Preparar a consulta
        $stmt = $pdo->prepare("SELECT id, senha, email, foto, dante FROM login WHERE usuario = :usuario");
        $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->execute();

        // Verifica se o usuário existe
        if ($stmt->rowCount() === 1) {
            // Obtém os dados do usuário
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stored_password = $row['senha']; // A senha armazenada no banco

            // Verifica se a senha é válida
            if (password_verify($senha, $stored_password)) {
                // Login bem-sucedido, configurar as variáveis de sessão
                $_SESSION['dante'] = $row['dante'];  // Armazena o papel do usuário (por exemplo, 'admin', 'funcionario')
                $_SESSION['usuario'] = $usuario;     // Armazena o nome do usuário
                $_SESSION['email'] = $row['email'];  // Armazena o email do usuário
                $_SESSION['logado'] = true;          // Marca o usuário como logado
                $_SESSION['foto'] = $row['foto'];    // Armazena a foto do usuário
            
                // Redireciona para a página inicial
                header('Location: home.php');
                exit;
            } else {
                // Senha inválida
                $error_message = "Senha inválida!";
            }
        } else {
            // Usuário não encontrado
            $error_message = "Usuário não encontrado!";
        }
    } catch (PDOException $e) {
        $error_message = "Erro ao verificar login: " . $e->getMessage();
    }
}
?><!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="login.css">
    <style>
        body {
            background-color: #f8f9fa;
            /* Cor de fundo suave */
        }

        .login-container {
            max-width: 400px;
            /* Largura máxima do formulário */
            margin: auto;
            /* Centraliza o formulário */
            padding: 20px;
            /* Espaçamento interno */
            background-color: #ffffff;
            /* Cor de fundo do formulário */
            border-radius: 8px;
            /* Bordas arredondadas */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Sombra suave */
        }

        h2 {
            text-align: center;
            /* Centraliza o título */
            margin-bottom: 20px;
            /* Espaçamento abaixo do título */
        }

        .btn-primary {
            width: 100%;
            /* Botão ocupa toda a largura */
        }

        .register-link {
            text-align: center;
            /* Centraliza o link de registro */
            margin-top: 15px;
            /* Espaçamento acima do link */
        }

        .error-message {
            color: #721c24; /* Cor do texto */
            background-color: #f8d7da; /* Cor de fundo */
            border: 1px solid #f5c6cb; /* Borda */
            border-radius: 5px; /* Bordas arredondadas */
            padding: 10px; /* Espaçamento interno */
            margin-bottom: 15px; /* Espaçamento abaixo da mensagem */
            display: flex; /* Flexbox para alinhar ícone e texto */
            align-items: center; /* Alinha verticalmente */
        }

        .error-message i {
            margin-right: 10px; /* Espaçamento entre ícone e texto */
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="login-container">
            <h2>Bem Vindo</h2>
            <?php if (!empty($error_message)): ?>
                <div class="error-message">
                    <i class="bi bi-exclamation-triangle-fill"></i> <!-- Ícone de alerta -->
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            <form action="" method="post">
    <div class="mb-3">
        <label for="usuario" class="form-label">Usuário</label>
        <input type="text" class="form-control" id="usuario" name="usuario" required>
    </div>
    <div class="mb-3">
        <label for="senha" class="form-label">Senha</label>
        <div class="input-group">
            <input type="password" class="form-control" id="senha" name="senha" required>
            <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                <i class="bi bi-eye-slash" id="eyeIcon"></i>
            </button>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Entrar</button>
</form>

            <div class="register-link">
                <a href="tela_cadastro.php" class="text-decoration-none">Não tem uma conta? Cadastre-se aqui!</a>
            </div>
        </div>
    </div>
    
<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('senha');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', function () {
        // Alterna o tipo de entrada entre "password" e "text"
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Alterna o ícone entre "eye" e "eye-slash"
        eyeIcon.classList.toggle('bi-eye');
        eyeIcon.classList.toggle('bi-eye-slash');
    });
</script>
<script src="login.js"></script>
</body>

</html>