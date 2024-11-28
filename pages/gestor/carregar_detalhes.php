<?php
// Iniciar a sessão, se necessário
session_start();

// Incluir a conexão com o banco de dados
include('conexao.php');  // Altere para o caminho correto do seu arquivo de conexão

// Verificar se o ID da solicitação foi passado via GET
if (isset($_GET['id_solicitacao'])) {
    $id_solicitacao = intval($_GET['id_solicitacao']);

    // Consulta SQL para buscar os detalhes da solicitação
    $sql = "SELECT 
                s.id_solicitacao, 
                s.id_usuario, 
                s.id_tinta, 
                s.destino_pintura, 
                s.status_solicitacao, 
                s.data_solicitacao, 
                s.data_aprovacao, 
                s.data_retirada,
                u.nome AS nome_usuario, 
                t.nome AS nome_tinta
            FROM solicitacoes s
            JOIN usuarios u ON s.id_usuario = u.id_usuario
            JOIN tintas t ON s.id_tinta = t.id_tinta
            WHERE s.id_solicitacao = ?";
    
    // Preparar a consulta
    if ($stmt = $conn->prepare($sql)) {
        // Vincular o parâmetro
        $stmt->bind_param("i", $id_solicitacao);
        
        // Executar a consulta
        $stmt->execute();
        
        // Obter os resultados
        $result = $stmt->get_result();

        // Verificar se há algum resultado
        if ($result->num_rows > 0) {
            // Recuperar a linha da solicitação
            $detalhes = $result->fetch_assoc();

            // Exibir os detalhes (você pode formatar conforme necessário)
            echo "<h2>Detalhes da Solicitação</h2>";
            echo "<p><strong>ID da Solicitação:</strong> " . $detalhes['id_solicitacao'] . "</p>";
            echo "<p><strong>Usuário:</strong> " . $detalhes['nome_usuario'] . "</p>";
            echo "<p><strong>Tinta:</strong> " . $detalhes['nome_tinta'] . "</p>";
            echo "<p><strong>Destino da Pintura:</strong> " . $detalhes['destino_pintura'] . "</p>";
            echo "<p><strong>Status da Solicitação:</strong> " . $detalhes['status_solicitacao'] . "</p>";
            echo "<p><strong>Data da Solicitação:</strong> " . $detalhes['data_solicitacao'] . "</p>";

            // Se aprovado, exibir a data de aprovação
            if ($detalhes['status_solicitacao'] == 'aprovada') {
                echo "<p><strong>Data da Aprovação:</strong> " . $detalhes['data_aprovacao'] . "</p>";
            }

            // Se a retirada foi feita, exibir a data de retirada
            if ($detalhes['data_retirada'] !== null) {
                echo "<p><strong>Data da Retirada:</strong> " . $detalhes['data_retirada'] . "</p>";
            }
        } else {
            echo "<p>Solicitação não encontrada.</p>";
        }

        // Fechar a declaração preparada
        $stmt->close();
    } else {
        echo "<p>Erro na consulta ao banco de dados.</p>";
    }
} else {
    echo "<p>ID da solicitação não fornecido.</p>";
}

// Fechar a conexão
$conn->close();
?>
