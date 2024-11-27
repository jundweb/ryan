<?php
session_start();
if (!isset($_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'usuario') {
    header('Location: ../../login.php');
    exit;
}

include '../../includes/bd.php';

// Consulta para pegar as tintas cadastradas pelo usuário
$query = "SELECT * FROM tintas WHERE id_usuario = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $_SESSION['user_id']); // Usando $_SESSION['user_id'] em vez de $_SESSION['usuario_id']
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Tintas - Dashboard</title>
    <!-- Link do Bootstrap 5 -->
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
                    <a href="listar_tinta.php" class="list-group-item list-group-item-action active">Doar Tinta</a>
                    <a href="../../logout.php" class="list-group-item list-group-item-action text-danger">Sair</a>
                </div>
            </div>
            <div class="col-md-9">
                <!-- Conteúdo principal do dashboard -->
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Tintas Cadastradas</h4>
                    </div>
                    <div class="card-body">
                        <p>Veja abaixo as tintas que você cadastrou. Você pode editar ou excluir tintas que ainda não foram liberadas.</p>
                        <div class="p-3 mx-auto d-block">
                            <a href="cadastrar_tinta.php" class="p-3 btn btn-success btn-lg mx-auto text-center d-block">CADASTRAR/DOAR TINTA</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Cor</th>
                                        <th>Volume</th>
                                        <th>Validade</th>
                                        <th>Marca</th>
                                        <th>Linha</th>
                                        <th>Aplicação</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result->fetch_assoc()) { 
                                        $data_brasil = $row['validade'];
                                        $data = date('d/m/Y', strtotime($data_brasil));
                                    ?>
                                    <tr>
                                        <td><?php echo $row['cor']; ?></td>
                                        <td><?php echo $row['volume']; ?></td>
                                        <td><?php echo $data; ?></td>
                                        <td><?php echo $row['marca']; ?></td>
                                        <td><?php echo $row['linha'];?></td>
                                        <td><?php echo $row['aplicacao']; ?></td>
                                        <td><?php echo $row['liberada'] == 1 ? 'Liberada' : 'Não Liberada'; ?></td>
                                        <td>
                                            <!-- Botões de ação -->
                                            <?php if ($row['liberada'] == 0) { ?>
                                                <a href="editar_tinta.php?id=<?php echo $row['cod_tinta']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                                <a href="excluir_tinta.php?id=<?php echo $row['cod_tinta']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir esta tinta?');">Excluir</a>
                                            <?php } else {?>
                                                <a style="pointer-events: none" class="btn btn-secondary btn-sm">Editar</a>
                                                <a style="pointer-events: none" class="btn btn-secondary btn-sm" onclick="return confirm('Tem certeza que deseja excluir esta tinta?');">Excluir</a>
                                            <?php }?>
                                            </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script do Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
