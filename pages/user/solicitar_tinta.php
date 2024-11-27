<?php
session_start();
if (!isset($_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'usuario') {
    header('Location: ../../login.php');
    exit;
}

include '../../includes/bd.php';

// ID do usuário logado
$user_id = $_SESSION['user_id'];

// Consulta para listar as solicitações feitas pelo usuário
$query = "
    SELECT s.id_solicitacao, t.cor, t.volume, t.marca, s.destino_pintura, 
           s.status_solicitacao, s.data_solicitacao
    FROM solicitacoes s
    JOIN tintas t ON s.id_tinta = t.cod_tinta
    WHERE s.id_usuario = ?
    ORDER BY s.data_solicitacao DESC
";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$solicitacoes = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Tinta</title>
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
                    <a href="solicitar_tinta.php" class="list-group-item list-group-item-action active">Solicitar Tinta</a>
                    <a href="listar_tinta.php" class="list-group-item list-group-item-action">Doar Tinta</a>
                    <a href="../../logout.php" class="list-group-item list-group-item-action text-danger">Sair</a>
                </div>
            </div>
            <div class="col-md-9">
                <!-- Conteúdo principal -->
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Minhas Solicitações de Tintas</h4>
                    </div>
                    <div class="card-body">
                        <!-- Botão para nova solicitação -->
                        <div class="mb-4 text-center">
                            <a href="nova_solicitacao.php" class="p-3 btn btn-success btn-lg mx-auto text-center d-block">Solicitar Nova Tinta</a>
                        </div>

                        <!-- Tabela de solicitações -->
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Cor</th>
                                        <th>Volume</th>
                                        <th>Marca</th>
                                        <th>Destino</th>
                                        <th>Status</th>
                                        <th>Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $solicitacoes->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo $row['cor']; ?></td>
                                            <td><?php echo $row['volume']; ?> L</td>
                                            <td><?php echo $row['marca']; ?></td>
                                            <td><?php echo $row['destino_pintura']; ?></td>
                                            <td>
                                                <?php 
                                                    if ($row['status_solicitacao'] === 'pendente') {
                                                        echo '<span class="badge bg-warning text-dark">Pendente</span>';
                                                    } elseif ($row['status_solicitacao'] === 'aprovada') {
                                                        echo '<span class="badge bg-success">Aprovada</span>';
                                                    } else {
                                                        echo '<span class="badge bg-danger">Rejeitada</span>';
                                                    }
                                                ?>
                                            </td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($row['data_solicitacao'])); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if ($solicitacoes->num_rows === 0) { ?>
                            <p class="text-center mt-4">Você ainda não fez nenhuma solicitação de tinta.</p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
