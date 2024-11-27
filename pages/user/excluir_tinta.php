<?php
session_start();
if (!isset($_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'usuario') {
    header('Location: ../../login.php');
    exit;
}

include '../../includes/bd.php';

// Verificando se o id da tinta foi passado via GET
if (isset($_GET['id'])) {
    $id_tinta = $_GET['id'];

    // Verificando se a tinta pertence ao usuário logado
    $query = "SELECT * FROM tintas WHERE cod_tinta = ? AND id_usuario = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $id_tinta, $_SESSION['user_id']);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Se a tinta existir para o usuário, podemos excluir
    if ($resultado->num_rows > 0) {
        // Excluindo a tinta
        $query_delete = "DELETE FROM tintas WHERE cod_tinta = ? AND id_usuario = ?";
        $stmt_delete = $conn->prepare($query_delete);
        $stmt_delete->bind_param('ii', $id_tinta, $_SESSION['user_id']);

        if ($stmt_delete->execute()) {
            $alerta = "Tinta excluída com sucesso!";
            header('Location: listar_tinta.php?alert=' . urlencode($alerta)); // Redireciona para a lista de tintas
            exit;
        } else {
            $erro = "Erro ao excluir a tinta. Tente novamente.";
        }
    } else {
        // Caso a tinta não pertença ao usuário ou não exista
        $erro = "Tinta não encontrada ou você não tem permissão para excluir.";
    }
} else {
    // Caso não seja fornecido um ID
    $erro = "ID da tinta não fornecido.";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Tinta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-3">
                <!-- Menu lateral -->
                <div class="list-group">
                    <a href="dashboard.php" class="list-group-item list-group-item-action">Início</a>
                    <a href="solicitar_tinta.php" class="list-group-item list-group-item-action">Solicitar Tinta</a>
                    <a href="listar_tinta.php" class="list-group-item list-group-item-action">Doar Tinta</a>
                    <a href="../../logout.php" class="list-group-item list-group-item-action text-danger">Sair</a>
                </div>
            </div>
            <div class="col-md-9">
                <!-- Conteúdo principal -->
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Excluir Tinta</h4>
                    </div>
                    <div class="card-body">
                        <!-- Exibindo alerta de erro, se houver -->
                        <?php if (isset($erro)) : ?>
                            <div class="alert alert-danger"><?= $erro ?></div>
                        <?php endif; ?>
                        
                        <!-- Exibindo alerta de sucesso, se houver -->
                        <?php if (isset($alerta)) : ?>
                            <div class="alert alert-success"><?= $alerta ?></div>
                        <?php endif; ?>
                        
                        <!-- Caso a exclusão não tenha sido realizada, pode exibir um erro -->
                        <?php if (!isset($alerta) && !isset($erro)) : ?>
                            <p>Aguarde enquanto processamos a exclusão...</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script do Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
