<?php
session_start();
include '../../includes/bd.php';

// Verifica se o gestor está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'gestor') {
    header('Location: ../../login.php');
    exit;
}

// Excluir tinta
if (isset($_GET['delete_tinta'])) {
    $cod_tinta = $_GET['delete_tinta'];
    $stmt = $conn->prepare("DELETE FROM tintas WHERE cod_tinta = ?");
    $stmt->bind_param("i", $cod_tinta);
    $stmt->execute();
    $stmt->close();
    header("Location: tintas_doadas.php");
    exit;
}

// Liberar tinta
if (isset($_POST['liberar_tinta'])) {
    $cod_tinta = $_POST['cod_tinta'];
    $stmt = $conn->prepare("UPDATE tintas SET liberada = 1 WHERE cod_tinta = ?");
    $stmt->bind_param("i", $cod_tinta);
    $stmt->execute();
    $stmt->close();
    header("Location: tintas_doadas.php");
    exit;
}

// Carregar dados de tintas
$liberadasQuery = "SELECT t.cod_tinta, t.cor, t.volume, t.validade, t.marca, t.liberada, t.linha, t.aplicacao, t.acabamento, u.nome 
                   FROM tintas t 
                   JOIN usuarios u ON t.id_usuario = u.id_usuario 
                   WHERE t.liberada = 1";
$pendentesQuery = "SELECT t.cod_tinta, t.cor, t.volume, t.validade, t.marca, t.liberada, t.linha, t.aplicacao, t.acabamento, u.nome 
                   FROM tintas t 
                   JOIN usuarios u ON t.id_usuario = u.id_usuario 
                   WHERE t.liberada = 0";
$liberadas = $conn->query($liberadasQuery);
$pendentes = $conn->query($pendentesQuery);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tintas - Sistema de Tintas</title>
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
                    <a href="usuarios.php" class="list-group-item list-group-item-action">Usuários</a>
                    <a href="tintas_doadas.php" class="list-group-item list-group-item-action">Tintas Doadas</a>
                    <a href="tintas_solicitadas.php" class="list-group-item list-group-item-action">Tintas Solicitadas</a>
                    <a href="../logout.php" class="list-group-item list-group-item-action text-danger">Sair</a>
                </div>
            </div>
            <div class="col-md-9">
                <!-- Conteúdo principal do dashboard -->
                <div class="card">
                    <div class="card-header">
                        <h4>Tintas Doadas</h4>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="tintaTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="liberadas-tab" data-bs-toggle="tab" href="#liberadas" role="tab" aria-controls="liberadas" aria-selected="true">Liberadas</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pendentes-tab" data-bs-toggle="tab" href="#pendentes" role="tab" aria-controls="pendentes" aria-selected="false">Pendentes</a>
                            </li>
                        </ul>
                        <div class="tab-content mt-3" id="tintaTabContent">
                            <!-- Abas Liberadas -->
                            <div class="tab-pane fade show active" id="liberadas" role="tabpanel" aria-labelledby="liberadas-tab">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Cor</th>
                                            <th>Volume (L)</th>
                                            <th>Marca</th>
                                            <th>Validade</th>
                                            <th>Usuário</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $liberadas->fetch_assoc()) { 
                                            // Converte a data do formato Y-m-d para d/m/Y
                                            $data_brasil = $row['validade'];
                                            $data = date('d/m/Y', strtotime($data_brasil));
                                        ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['cor']); ?></td>
                                                <td><?php echo htmlspecialchars($row['volume']); ?></td>
                                                <td><?php echo htmlspecialchars($row['marca']); ?></td>
                                                <td><?php echo $data;?></td>
                                                <td><?php echo htmlspecialchars($row['nome']); ?></td>
                                                <td>
                                                    <a href="?delete_tinta=<?php echo $row['cod_tinta']; ?>" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta tinta?');">Excluir</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Abas Pendentes -->
                            <div class="tab-pane fade" id="pendentes" role="tabpanel" aria-labelledby="pendentes-tab">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Cor</th>
                                            <th>Volume (L)</th>
                                            <th>Marca</th>
                                            <th>Validade</th>
                                            <th>Usuário</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $pendentes->fetch_assoc()) { 
                                            // Converte a data do formato Y-m-d para d/m/Y
                                            $data_brasil = $row['validade'];
                                            $data = date('d/m/Y', strtotime($data_brasil));
                                        ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['cor']); ?></td>
                                                <td><?php echo htmlspecialchars($row['volume']); ?></td>
                                                <td><?php echo htmlspecialchars($row['marca']); ?></td>
                                                <td><?php echo $data; ?></td>
                                                <td><?php echo htmlspecialchars($row['nome']); ?></td>
                                                <td>
                                                    <a href="?delete_tinta=<?php echo $row['cod_tinta']; ?>" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta tinta?');">Excluir</a>
                                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#verTintaModal" data-id="<?php echo $row['cod_tinta']; ?>">Ver Tinta</button>
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

        <!-- Modal para Ver Tinta -->
        <div class="modal fade" id="verTintaModal" tabindex="-1" aria-labelledby="verTintaModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="verTintaModalLabel">Detalhes da Tinta</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Cor:</strong> <span id="modal-cor"></span></p>
                        <p><strong>Volume:</strong> <span id="modal-volume"></span> L</p>
                        <p><strong>Marca:</strong> <span id="modal-marca"></span></p>
                        <p><strong>Validade:</strong> <span id="modal-validade"></span></p>
                        <p><strong>Linha:</strong> <span id="modal-linha"></span></p>
                        <p><strong>Aplicação:</strong> <span id="modal-aplicacao"></span></p>
                        <p><strong>Acabamento:</strong> <span id="modal-acabamento"></span></p>
                    </div>
                    <div class="modal-footer">
                        <form method="POST" action="tintas_doadas.php">
                            <input type="hidden" name="cod_tinta" id="modal-cod-tinta">
                            <button type="submit" name="liberar_tinta" class="btn btn-success">Liberar Tinta</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Função para preencher os dados no modal
        var verTintaModal = document.getElementById('verTintaModal');
        verTintaModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var codTinta = button.getAttribute('data-id');

            // Requisição AJAX para buscar os dados da tinta no banco
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_tinta.php?cod_tinta=' + codTinta, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);

                    if (data.error) {
                        alert(data.error);
                    } else {
                        // Preencher os campos do modal com os dados
                        document.getElementById('modal-cor').textContent = data.cor;
                        document.getElementById('modal-volume').textContent = data.volume;
                        document.getElementById('modal-marca').textContent = data.marca;
                        document.getElementById('modal-validade').textContent = data.validade;
                        document.getElementById('modal-linha').textContent = data.linha;
                        document.getElementById('modal-aplicacao').textContent = data.aplicacao;
                        document.getElementById('modal-acabamento').textContent = data.acabamento;
                        document.getElementById('modal-cod-tinta').value = codTinta;
                    }
                } else {
                    alert('Erro ao carregar dados da tinta');
                }
            };
            xhr.send();
        });
    </script>

</body>
</html>
