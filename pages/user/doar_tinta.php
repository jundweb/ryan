<?php
session_start();
if (!isset($_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'usuario') {
    header('Location: ../../login.php');
    exit;
}

include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do formulário de doação
    $cor = $_POST['cor'];
    $volume = $_POST['volume'];
    $validade = $_POST['validade'];
    $marca = $_POST['marca'];
    $linha = $_POST['linha'];
    $aplicacao = $_POST['aplicacao'];
    $acabamento = $_POST['acabamento'];

    // Prepara o insert na tabela de tintas
    $usuario_id = $_SESSION['usuario_id']; // Assumindo que o 'usuario_id' está na sessão

    // Insere a tinta na tabela tintas com 'liberada' como 0 (não liberada)
    $query = "INSERT INTO tintas (id_usuario, cor, volume, validade, marca, linha, aplicacao, acabamento, liberada) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssssssss', $usuario_id, $cor, $volume, $validade, $marca, $linha, $aplicacao, $acabamento);
    
    if ($stmt->execute()) {
        echo "<script>alert('Tinta cadastrada com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar a tinta. Tente novamente.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doar Tinta - Sistema de Tintas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-3">
                <div class="list-group">
                    <a href="dashboard.php" class="list-group-item list-group-item-action">Início</a>
                    <a href="solicitar_tinta.php" class="list-group-item list-group-item-action">Solicitar Tinta</a>
                    <a href="doar_tinta.php" class="list-group-item list-group-item-action">Doar Tinta</a>
                    <a href="../logout.php" class="list-group-item list-group-item-action text-danger">Sair</a>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h4>Doar Tinta</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="doar_tinta.php">
                            <div class="mb-3">
                                <label for="cor" class="form-label">Cor da Tinta</label>
                                <input type="text" name="cor" id="cor" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="volume" class="form-label">Volume (em Litros)</label>
                                <input type="number" name="volume" id="volume" class="form-control" required min="0.1" step="0.1">
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
                                <label for="linha" class="form-label">Linha (opcional)</label>
                                <input type="text" name="linha" id="linha" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="aplicacao" class="form-label">Aplicação (opcional)</label>
                                <input type="text" name="aplicacao" id="aplicacao" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="acabamento" class="form-label">Acabamento (opcional)</label>
                                <input type="text" name="acabamento" id="acabamento" class="form-control">
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
</body>
</html>
