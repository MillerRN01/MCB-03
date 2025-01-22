<?php
include_once 'conexao.php'; // Inclua sua conexão com o banco de dados
session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php"); // Redireciona para a página de index se não estiver logado
    exit();
}

// Consulta SQL para buscar os clientes
$sql = "SELECT * FROM clientes";
$result = $conn->query($sql);

// Verifica se há resultados
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="client-card">';
        echo '<h3>' . $row['nome'] . '</h3>';
        echo '<p><strong>Email:</strong> ' . $row['email'] . '</p>';
        echo '<p><strong>Status:</strong> ' . $row['status'] . '</p>';
        echo '<p><strong>Tipo:</strong> ' . $row['tipo'] . '</p>';
        echo '</div>';
    }
} else {
    echo '<p>Nenhum cliente encontrado.</p>';
}

// Fecha a conexão
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Busca de Clientes - MeuComerciodeBolso</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="busca_cliente.css">
</head>
<body>
    <div class="clients-container">
        <?php
        $sql = "SELECT * FROM clientes";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $index = 0;
            while ($row = $result->fetch_assoc()) {
                $statusClass = strtolower($row['status']) === 'ativo' ? 'status-active' : 'status-inactive';
                echo '<div class="client-card" style="--item-index: ' . $index . '">';
                echo '<h3><i class="bi bi-person-circle"></i> ' . htmlspecialchars($row['nome']) . '</h3>';
                echo '<div class="client-info">';
                echo '<div class="info-item">';
                echo '<span class="info-label"><i class="bi bi-envelope"></i> Email:</span>';
                echo '<span class="info-value">' . htmlspecialchars($row['email']) . '</span>';
                echo '</div>';
                echo '<div class="info-item">';
                echo '<span class="info-label"><i class="bi bi-circle"></i> Status:</span>';
                echo '<span class="status-badge ' . $statusClass . '">';
                echo '<i class="bi bi-' . (strtolower($row['status']) === 'ativo' ? 'check-circle' : 'x-circle') . '"></i>';
                echo htmlspecialchars($row['status']);
                echo '</span>';
                echo '</div>';
                echo '<div class="info-item">';
                echo '<span class="info-label"><i class="bi bi-tag"></i> Tipo:</span>';
                echo '<span class="type-badge">' . htmlspecialchars($row['tipo']) . '</span>';
                echo '</div>';
                echo '</div>'; // Fecha client-info
                echo '</div>'; // Fecha client-card
                $index++;
            }
        } else {
            echo '<div class="no-results">';
            echo '<i class="bi bi-search" style="font-size: 2rem; margin-bottom: 1rem;"></i>';
            echo '<p>Nenhum cliente encontrado.</p>';
            echo '</div>';
        }

        $conn->close();
        ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Adiciona efeito de hover nos cards
            const cards = document.querySelectorAll('.client-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</body>
</html>