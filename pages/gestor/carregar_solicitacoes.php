<?php
session_start();

// Incluindo a configuração do banco de dados
include '../../includes/bd.php'; 

// Verifica se o usuário está logado e é do tipo 'gestor'
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'gestor') {
    header('Location: ../../login.php');
    exit;
}

// Pegando o status da solicitação via GET (caso não tenha, define como 'pendente')
$status = isset($_GET['status']) ? $_GET['status'] : 'pendente'; 

// Prepara a consulta SQL
$query = "
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
        solicitacoes.status_solicitacao = ?
    ORDER BY 
        solicitacoes.data_solicitacao DESC
";

// Prepara a consulta
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $status);

// Executa a consulta
$stmt->execute();
$result = $stmt->get_result();

// Exibe a tabela com os dados
echo '<thead>
        <tr>
            <th>Marca</th>
            <th>Cor</th>
            <th>Volume</th>
            <th>Solicitante</th>
            <th>Ação</th>
        </tr>
      </thead>';
echo '<tbody>';

while ($solicitacao = $result->fetch_assoc()) {
    echo '<tr>';
    // Verifica se os valores não são nulos antes de exibir
    echo '<td>' . htmlspecialchars($solicitacao['tinta_marca'] ?? 'Não especificado') . '</td>';
    echo '<td>' . htmlspecialchars($solicitacao['tinta_cor'] ?? 'Não especificado') . '</td>';
    echo '<td>' . htmlspecialchars($solicitacao['tinta_volume'] ?? 'Não especificado') . '</td>';
    echo '<td>' . htmlspecialchars($solicitacao['usuario_nome'] ?? 'Não especificado') . '</td>';
    // Botão para abrir o modal
    echo '<td><button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalDetalhes" data-id="' . $solicitacao['id_solicitacao'] . '">Ver Detalhes</button></td>';
    echo '</tr>';
}
echo '</tbody>';

$stmt->close();
$conn->close();
?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    let idSolicitacao = null;

    // Quando o botão "Ver Detalhes" for clicado
    const buttons = document.querySelectorAll('button[data-bs-target="#modalDetalhes"]');
    buttons.forEach(button => {
        button.addEventListener('click', function () {
            idSolicitacao = this.getAttribute('data-id');

            // Requisição AJAX para pegar os dados da solicitação
            fetch('detalhes_solicitacao.php?id=' + idSolicitacao)
                .then(response => response.json())
                .then(data => {
                    // Preencher os campos do modal com os dados retornados
                    document.getElementById('marca').textContent = data.marca;
                    document.getElementById('cor').textContent = data.cor;
                    document.getElementById('volume').textContent = data.volume;
                    document.getElementById('nome_solicitante').textContent = data.nome_solicitante;
                    document.getElementById('destino_pintura').textContent = data.destino_pintura;
                })
                .catch(error => console.error('Erro ao buscar dados:', error));
        });
    });

    // Quando o botão "Liberar" for clicado
    document.getElementById('liberarBtn').addEventListener('click', function () {
        if (idSolicitacao) {
            // Requisição AJAX para atualizar o status da solicitação para 'liberada'
            fetch('liberar_solicitacao.php?id=' + idSolicitacao)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Solicitação liberada com sucesso!');
                        location.reload(); // Atualiza a página para refletir a mudança
                    } else {
                        alert('Erro ao liberar solicitação.');
                    }
                })
                .catch(error => console.error('Erro ao liberar solicitação:', error));
        }
    });
});

</script>