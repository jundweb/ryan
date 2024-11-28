<?php
include ('../../includes/bd.php');

// Verifica se o ID foi enviado pela URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtém a data e hora atual no formato 'YYYY-MM-DD HH:MM:SS'
    $data_retirada = date('Y-m-d H:i:s');

    // Atualiza a data de retirada no banco de dados
    $query = "UPDATE solicitacoes 
              SET data_retirada = '$data_retirada' 
              WHERE id_solicitacao = $id";

    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Confirmação de entrega realizada com sucesso!');</script>";
        echo "<script>window.location.href='tintas_solicitadas.php';</script>";
    } else {
        echo "Erro ao confirmar a entrega: " . $conn->error;
    }
} else {
    echo "ID da solicitação não fornecido.";
}

// Fecha a conexão com o banco de dados
$conn->close();
?>