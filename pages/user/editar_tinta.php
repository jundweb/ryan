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
    
    // Buscando os dados da tinta no banco de dados
    $query = "SELECT * FROM tintas WHERE cod_tinta = ? AND id_usuario = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $id_tinta, $_SESSION['user_id']); // Usando o ID do usuário logado para garantir que ele só possa editar suas próprias tintas
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
        // Dados encontrados, preenchendo o formulário
        $tinta = $resultado->fetch_assoc();
    } else {
        // Caso não encontre a tinta
        header('Location: listar_tinta.php'); // Redireciona de volta para a lista de tintas
        exit;
    }
} else {
    // Se o ID não for passado, redireciona
    header('Location: listar_tinta.php');
    exit;
}

// Processando a edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os dados do formulário
    $cor = $_POST['cor'];
    $volume = $_POST['volume'];
    $validade = $_POST['validade'];
    $marca = $_POST['marca'];
    $linha = $_POST['linha'];
    $aplicacao = $_POST['aplicacao'];
    $acabamento = $_POST['acabamento'];

    // Atualizando a tinta no banco de dados
    $query = "UPDATE tintas SET cor = ?, volume = ?, validade = ?, marca = ?, linha = ?, aplicacao = ?, acabamento = ? WHERE cod_tinta = ? AND id_usuario = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssssssssi', $cor, $volume, $validade, $marca, $linha, $aplicacao, $acabamento, $id_tinta, $_SESSION['user_id']);

    if ($stmt->execute()) {
        $alerta = "Tinta editada com sucesso!";
        header('Location: listar_tinta.php?alert=' . urlencode($alerta)); // Redireciona para a lista de tintas com mensagem de sucesso
        exit;
    } else {
        $erro = "Erro ao editar a tinta.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tinta - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../estilos.css" rel="stylesheet">
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
                <!-- Conteúdo principal do dashboard -->
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Editar Tinta</h4>
                    </div>
                    <div class="card-body">
                        <!-- Exibindo alerta de sucesso ou erro -->
                        <?php if (isset($alerta)) : ?>
                            <div class="alert alert-success"><?= $alerta ?></div>
                        <?php endif; ?>
                        <?php if (isset($erro)) : ?>
                            <div class="alert alert-danger"><?= $erro ?></div>
                        <?php endif; ?>
                        
                        <!-- Formulário de edição de tinta -->
                        <form method="POST" action="editar_tinta.php?id=<?= $id_tinta ?>">
                            <div class="mb-3">
                                <label for="cor" class="form-label">Cor</label>
                                <input type="text" name="cor" id="cor" class="form-control" value="<?= htmlspecialchars($tinta['cor']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="volume" class="form-label">Volume</label>
                                <input type="text" name="volume" id="volume" class="form-control" value="<?= htmlspecialchars($tinta['volume']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="validade" class="form-label">Validade</label>
                                <input type="date" name="validade" id="validade" class="form-control" value="<?= $tinta['validade'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="marca" class="form-label">Marca</label>
                                <input type="text" name="marca" id="marca" class="form-control" value="<?= htmlspecialchars($tinta['marca']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="linha" class="form-label">Linha</label>
                                <input type="text" name="linha" id="linha" class="form-control" value="<?= htmlspecialchars($tinta['linha']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="aplicacao" class="form-label">Aplicação</label>
                                <input type="text" name="aplicacao" id="aplicacao" class="form-control" value="<?= htmlspecialchars($tinta['aplicacao']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="acabamento" class="form-label">Acabamento</label>
                                <input type="text" name="acabamento" id="acabamento" class="form-control" value="<?= htmlspecialchars($tinta['acabamento']) ?>" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script do Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
