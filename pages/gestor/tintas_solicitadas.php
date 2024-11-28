<?php
session_start();
include '../../includes/bd.php';

// Verifica se o gestor está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'gestor') {
    header('Location: ../../login.php');
    exit;
}

// Obtém o nome do gestor da sessão
$nome_gestor = $_SESSION['user_nome'];

// Consultas para obter as solicitações com status diferentes
$query_pendente = "
   SELECT 
       solicitacoes.id_solicitacao, 
       solicitacoes.destino_pintura, 
       solicitacoes.id_tinta, 
       solicitacoes.id_usuario, 
       tintas.cor AS tinta_cor, 
       tintas.marca AS tinta_marca, 
       tintas.volume AS tinta_volume, 
       usuarios.nome AS usuario_nome
   FROM 
       solicitacoes
   INNER JOIN 
       tintas ON solicitacoes.id_tinta = tintas.cod_tinta
   INNER JOIN 
       usuarios ON solicitacoes.id_usuario = usuarios.id_usuario
   WHERE 
       solicitacoes.status_solicitacao = 'pendente' 
   ORDER BY 
       solicitacoes.data_solicitacao DESC
";

$query_aprovada = "
   SELECT 
       solicitacoes.id_solicitacao, 
       solicitacoes.destino_pintura, 
       solicitacoes.id_tinta, 
       solicitacoes.id_usuario, 
       solicitacoes.data_retirada,
       tintas.cor AS tinta_cor, 
       tintas.marca AS tinta_marca, 
       tintas.volume AS tinta_volume, 
       usuarios.nome AS usuario_nome
   FROM 
       solicitacoes
   INNER JOIN 
       tintas ON solicitacoes.id_tinta = tintas.cod_tinta
   INNER JOIN 
       usuarios ON solicitacoes.id_usuario = usuarios.id_usuario
   WHERE 
       solicitacoes.status_solicitacao = 'aprovada' 
   ORDER BY 
       solicitacoes.data_solicitacao DESC
";

$query_rejeitada = "
   SELECT 
       solicitacoes.id_solicitacao, 
       solicitacoes.destino_pintura, 
       solicitacoes.id_tinta, 
       solicitacoes.id_usuario, 
       tintas.cor AS tinta_cor, 
       tintas.marca AS tinta_marca, 
       tintas.volume AS tinta_volume, 
       usuarios.nome AS usuario_nome
   FROM 
       solicitacoes
   INNER JOIN 
       tintas ON solicitacoes.id_tinta = tintas.cod_tinta
   INNER JOIN 
       usuarios ON solicitacoes.id_usuario = usuarios.id_usuario
   WHERE 
       solicitacoes.status_solicitacao = 'rejeitada' 
   ORDER BY 
       solicitacoes.data_solicitacao DESC
";

// Executa as consultas diretamente
$result_pendente = $conn->query($query_pendente);
$result_aprovada = $conn->query($query_aprovada);
$result_rejeitada = $conn->query($query_rejeitada);

// Armazena os resultados em arrays
$pendentes = [];
$aprovadas = [];
$rejeitadas = [];

while ($solicitacao = $result_pendente->fetch_assoc()) {
    $pendentes[] = $solicitacao;
}
while ($solicitacao = $result_aprovada->fetch_assoc()) {
    $aprovadas[] = $solicitacao;
}
while ($solicitacao = $result_rejeitada->fetch_assoc()) {
    $rejeitadas[] = $solicitacao;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tintas Solicitadas - Dashboard Gestor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../estilos.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-3">
                <div class="list-group">
                    <a href="dashboard.php" class="list-group-item list-group-item-action">Início</a>
                    <a href="usuarios.php" class="list-group-item list-group-item-action">Usuários</a>
                    <a href="tintas_doadas.php" class="list-group-item list-group-item-action">Tintas Doadas</a>
                    <a href="tintas_solicitadas.php" class="list-group-item list-group-item-action active">Tintas Solicitadas</a>
                    <a href="../logout.php" class="list-group-item list-group-item-action text-danger">Sair</a>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Gestão de Solicitações de Tintas - Bem-vindo, <?php echo htmlspecialchars($nome_gestor); ?>!</h4>
                    </div>
                    <div class="card-body">
                        <!-- Abas para filtrar as solicitações -->
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="pendente-tab" data-bs-toggle="tab" href="#pendente" role="tab" aria-controls="pendente" aria-selected="true">Pendentes</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="aprovada-tab" data-bs-toggle="tab" href="#aprovada" role="tab" aria-controls="aprovada" aria-selected="false">Aprovadas</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="rejeitada-tab" data-bs-toggle="tab" href="#rejeitada" role="tab" aria-controls="rejeitada" aria-selected="false">Rejeitadas</a>
                            </li>
                        </ul>
                        <div class="tab-content mt-3" id="myTabContent">
                            <!-- Abas de solicitações Pendentes -->
                            <div class="tab-pane fade show active" id="pendente" role="tabpanel" aria-labelledby="pendente-tab">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Marca</th>
                                            <th>Cor</th>
                                            <th>Volume</th>
                                            <th>Solicitante</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (!empty($pendentes)) {
                                        foreach ($pendentes as $solicitacao) {
                                            echo '<tr>';
                                            echo '<td>' . htmlspecialchars($solicitacao['tinta_marca']) . '</td>';
                                            echo '<td>' . htmlspecialchars($solicitacao['tinta_cor']) . '</td>';
                                            echo '<td>' . htmlspecialchars($solicitacao['tinta_volume']) . '</td>';
                                            echo '<td>' . htmlspecialchars($solicitacao['usuario_nome']) . '</td>';
                                            echo '<td><button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalDetalhes' . $solicitacao['id_solicitacao'] . '">Ver Detalhes</button></td>';
                                            echo '</tr>';

                                            // Modal para detalhes da solicitação
                                            echo '<div class="modal fade" id="modalDetalhes' . $solicitacao['id_solicitacao'] . '" tabindex="-1" aria-labelledby="modalDetalhesLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalDetalhesLabel">Detalhes da Solicitação</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><strong>Destino de Pintura:</strong> ' . htmlspecialchars($solicitacao['destino_pintura']) . '</p>
                                                        <p><strong>Marca:</strong> ' . htmlspecialchars($solicitacao['tinta_marca']) . '</p>
                                                        <p><strong>Cor:</strong> ' . htmlspecialchars($solicitacao['tinta_cor']) . '</p>
                                                        <p><strong>Volume:</strong> ' . htmlspecialchars($solicitacao['tinta_volume']) . '</p>
                                                        <p><strong>Solicitante:</strong> ' . htmlspecialchars($solicitacao['usuario_nome']) . '</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                                        <a href="liberar_solicitacao.php?id=' . $solicitacao['id_solicitacao'] . '" class="btn btn-success">Liberar</a>                                                        
                                                    </div>
                                                    </div>
                                                </div>
                                                </div>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="5" class="text-center">Não há solicitações pendentes.</td></tr>';
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Abas de solicitações Aprovadas -->
                             <div class="tab-pane fade" id="aprovada" role="tabpanel" aria-labelledby="aprovada-tab">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Marca</th>
                                            <th>Cor</th>
                                            <th>Volume</th>
                                            <th>Solicitante</th>
                                            <th>Data de Entrega</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (!empty($aprovadas)) {
                                        foreach ($aprovadas as $solicitacao) {
                                            echo '<tr>';
                                            echo '<td>' . htmlspecialchars($solicitacao['tinta_marca']) . '</td>';
                                            echo '<td>' . htmlspecialchars($solicitacao['tinta_cor']) . '</td>';
                                            echo '<td>' . htmlspecialchars($solicitacao['tinta_volume']) . '</td>';
                                            echo '<td>' . htmlspecialchars($solicitacao['usuario_nome']) . '</td>';

                                            // Verificando se data_retirada está vazia
                                            if (empty($solicitacao['data_retirada']) || $solicitacao['data_retirada'] == '0000-00-00') {
                                                echo '<td>A confirmar entrega</td>';
                                            } else {
                                                // Verifica se a data é válida antes de usar strtotime
                                                $data_retirada = strtotime($solicitacao['data_retirada']);
                                                if ($data_retirada === false) {
                                                    echo '<td>Data inválida</td>';
                                                } else {
                                                    echo '<td>' . date('d/m/Y', $data_retirada) . '</td>';
                                                }
                                            }
                                            echo '<td><button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalDetalhes' . $solicitacao['id_solicitacao'] . '">Ver Detalhes</button></td>';
                                            echo '</tr>';

                                            // Modal para detalhes da solicitação
                                            echo '<div class="modal fade" id="modalDetalhes' . $solicitacao['id_solicitacao'] . '" tabindex="-1" aria-labelledby="modalDetalhesLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalDetalhesLabel">Detalhes da Solicitação</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><strong>Destino de Pintura:</strong> ' . htmlspecialchars($solicitacao['destino_pintura']) . '</p>
                                                        <p><strong>Marca:</strong> ' . htmlspecialchars($solicitacao['tinta_marca']) . '</p>
                                                        <p><strong>Cor:</strong> ' . htmlspecialchars($solicitacao['tinta_cor']) . '</p>
                                                        <p><strong>Volume:</strong> ' . htmlspecialchars($solicitacao['tinta_volume']) . '</p>
                                                        <p><strong>Solicitante:</strong> ' . htmlspecialchars($solicitacao['usuario_nome']) . '</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                                        <a href="confirmar_entrega.php?id=' . $solicitacao['id_solicitacao'] . '" class="btn btn-primary">Confirmar Entrega</a>
                                                    </div>
                                                    </div>
                                                </div>
                                                </div>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="6" class="text-center">Não há solicitações aprovadas.</td></tr>';
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>



                            <!-- Abas de solicitações Rejeitadas -->
                            <div class="tab-pane fade" id="rejeitada" role="tabpanel" aria-labelledby="rejeitada-tab">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Marca</th>
                                            <th>Cor</th>
                                            <th>Volume</th>
                                            <th>Solicitante</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (!empty($rejeitadas)) {
                                        foreach ($rejeitadas as $solicitacao) {
                                            echo '<tr>';
                                            echo '<td>' . htmlspecialchars($solicitacao['tinta_marca']) . '</td>';
                                            echo '<td>' . htmlspecialchars($solicitacao['tinta_cor']) . '</td>';
                                            echo '<td>' . htmlspecialchars($solicitacao['tinta_volume']) . '</td>';
                                            echo '<td>' . htmlspecialchars($solicitacao['usuario_nome']) . '</td>';
                                            echo '<td><button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalDetalhes' . $solicitacao['id_solicitacao'] . '">Ver Detalhes</button></td>';
                                            echo '</tr>';

                                            // Modal para detalhes da solicitação
                                            echo '<div class="modal fade" id="modalDetalhes' . $solicitacao['id_solicitacao'] . '" tabindex="-1" aria-labelledby="modalDetalhesLabel" aria-hidden="true">
                                                  <div class="modal-dialog">
                                                    <div class="modal-content">
                                                      <div class="modal-header">
                                                        <h5 class="modal-title" id="modalDetalhesLabel">Detalhes da Solicitação</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                      </div>
                                                      <div class="modal-body">
                                                        <p><strong>Destino de Pintura:</strong> ' . htmlspecialchars($solicitacao['destino_pintura']) . '</p>
                                                        <p><strong>Marca:</strong> ' . htmlspecialchars($solicitacao['tinta_marca']) . '</p>
                                                        <p><strong>Cor:</strong> ' . htmlspecialchars($solicitacao['tinta_cor']) . '</p>
                                                        <p><strong>Volume:</strong> ' . htmlspecialchars($solicitacao['tinta_volume']) . '</p>
                                                        <p><strong>Solicitante:</strong> ' . htmlspecialchars($solicitacao['usuario_nome']) . '</p>
                                                      </div>
                                                      <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="5" class="text-center">Não há solicitações rejeitadas.</td></tr>';
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
