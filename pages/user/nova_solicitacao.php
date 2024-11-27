<?php
session_start();
if (!isset($_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'usuario') {
    header('Location: ../../login.php');
    exit;
}

include '../../includes/bd.php';

// ID do usuário logado
$user_id = $_SESSION['user_id'];

// Consulta para pegar as tintas liberadas para solicitação
$query = "SELECT cod_tinta, cor, volume, marca FROM tintas WHERE liberada = 1";
$stmt = $conn->prepare($query);
$stmt->execute();
$tintas = $stmt->get_result();

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_tinta = $_POST['tinta'];
    $destino_pintura = $_POST['destino_pintura'];

    // Insere a solicitação no banco de dados
    $insert_query = "INSERT INTO solicitacoes (id_usuario, id_tinta, destino_pintura, status_solicitacao, data_solicitacao) 
                     VALUES (?, ?, ?, 'pendente', NOW())";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param('iis', $user_id, $id_tinta, $destino_pintura);

    if ($insert_stmt->execute()) {
        header('Location: solicitar_tinta.php');
        exit;
    } else {
        $error_message = 'Erro ao registrar a solicitação. Tente novamente.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Nova Tinta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../estilos.css" rel="stylesheet">

    <!-- Script para confirmação de envio -->
    <script>
        function confirmarSolicitacao() {
            // Confirmação antes de enviar o formulário
            var confirma = confirm("Você tem certeza que deseja solicitar esta tinta?");
            if (confirma) {
                document.getElementById("formSolicitacao").submit(); // Envia o formulário
            }
        }
    </script>
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
                        <h4>Solicitar Nova Tinta</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error_message)) { ?>
                            <div class="alert alert-danger">
                                <?php echo $error_message; ?>
                            </div>
                        <?php } ?>

                        <form method="POST" id="formSolicitacao">
                            <div class="mb-3">
                                <label for="tinta" class="form-label">Selecione a Tinta</label>
                                <select class="form-select" id="tinta" name="tinta" required>
                                    <option value="" disabled selected>Escolha uma tinta</option>
                                    <?php while ($row = $tintas->fetch_assoc()) { ?>
                                        <option value="<?php echo $row['cod_tinta']; ?>">
                                            <?php echo $row['cor'] . ' - ' . $row['marca'] . ' (' . $row['volume'] . 'L)'; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="destino_pintura" class="form-label">Destino da Pintura</label>
                                <textarea class="form-control" id="destino_pintura" name="destino_pintura" rows="3" required></textarea>
                            </div>
                            <div class="text-center">
                                <!-- Botão de envio com função JavaScript para confirmar -->
                                <button type="button" class="btn btn-success btn-lg" onclick="confirmarSolicitacao()">Solicitar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
