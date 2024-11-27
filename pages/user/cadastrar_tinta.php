<?php
session_start();
if (!isset($_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'usuario') {
    header('Location: ../../login.php');
    exit;
}

include '../../includes/bd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cor = $_POST['cor'];
    $volume = $_POST['volume'];
    $validade = $_POST['validade'];
    $marca = $_POST['marca'];
    $linha = $_POST['linha'];
    $aplicacao = $_POST['aplicacao'];
    $acabamento = $_POST['acabamento'];

    $id_usuario = $_SESSION['user_id']; // Captura o ID do usuário logado

    // Inserção no banco de dados
    $query = "INSERT INTO tintas (cor, volume, validade, marca, linha, aplicacao, acabamento, id_usuario) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssssssi', $cor, $volume, $validade, $marca, $linha, $aplicacao, $acabamento, $id_usuario);

    if ($stmt->execute()) {
        $alerta = "Cadastro realizado com sucesso!";
        header('Location: listar_tinta.php?alert=' . urlencode($alerta)); // Redireciona para a lista de tintas com mensagem de sucesso
        exit;
    } else {
        $erro = "Erro ao cadastrar a tinta.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Tinta - Dashboard</title>
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
                        <h4>Cadastrar Tinta</h4>
                    </div>
                    <div class="card-body">
                        <!-- Exibindo alerta de sucesso ou erro -->
                        <?php if (isset($alerta)) : ?>
                            <div class="alert alert-success"><?= $alerta ?></div>
                        <?php endif; ?>
                        <?php if (isset($erro)) : ?>
                            <div class="alert alert-danger"><?= $erro ?></div>
                        <?php endif; ?>
                        
                        <!-- Formulário de cadastro de tinta -->
                        <form method="POST" action="cadastrar_tinta.php">
                            <div class="mb-3">
                                <label for="cor" class="form-label">Cor</label>
                                <input type="text" name="cor" id="cor" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="volume" class="form-label">Volume</label>
                                <input type="text" name="volume" id="volume" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="validade" class="form-label">Validade</label>
                                <input type="date" name="validade" id="validade" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="marca" class="form-label">Marca</label>
                                <input type="text" name="marca" id="marca" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="linha" class="form-label">Linha</label>
                                <input type="text" name="linha" id="linha" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="aplicacao" class="form-label">Aplicação</label>
                                <input type="text" name="aplicacao" id="aplicacao" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="acabamento" class="form-label">Acabamento</label>
                                <input type="text" name="acabamento" id="acabamento" class="form-control" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Cadastrar Tinta</button>
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
