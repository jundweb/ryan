<?php
include ('../../includes/bd.php');
// Verifica se o ID foi enviado pela URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtém a data atual no formato 'YYYY-MM-DD'
    $data_aprovacao = date('Y-m-d');

    // Atualiza o status da solicitação e a data de aprovação no banco de dados
    $query = "UPDATE solicitacoes 
              SET status_solicitacao = 'aprovada', data_aprovacao = '$data_aprovacao' 
              WHERE id_solicitacao = $id";

    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Solicitação liberada com sucesso!');</script>";
        echo "<script>window.location.href='tintas_solicitadas.php';</script>";
    } else {
        echo "Erro ao liberar a solicitação: " . $conn->error;
    }
} else {
    echo "ID da solicitação não fornecido.";
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
