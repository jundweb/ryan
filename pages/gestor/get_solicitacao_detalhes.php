<?php
include '../../includes/bd.php'; // Certifique-se de que o caminho esteja correto

// Verificando se o ID foi passado
if (isset($_GET['id_solicitacao'])) {
    $id_solicitacao = $_GET['id_solicitacao'];

    // Consulta para pegar os detalhes da solicitação
    $query = "
        SELECT 
            solicitacoes.id_solicitacao, 
            solicitacoes.destino_pintura, 
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
            solicitacoes.id_solicitacao = ?
    ";

    // Prepara a consulta
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_solicitacao);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $solicitacao = $result->fetch_assoc();
        echo json_encode($solicitacao);
    } else {
        echo json_encode(['error' => 'Solicitação não encontrada.']);
    }

    // Fecha a conexão
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'ID de solicitação não fornecido.']);
}
?>
