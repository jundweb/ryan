<?php
include '../../includes/bd.php';

if (isset($_GET['cod_tinta'])) {
    $cod_tinta = $_GET['cod_tinta'];
    $stmt = $conn->prepare("SELECT cor, volume, marca, validade, linha, aplicacao, acabamento FROM tintas WHERE cod_tinta = ?");
    $stmt->bind_param("i", $cod_tinta);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        // Retorna os dados como JSON
        echo json_encode($row);
    } else {
        echo json_encode(["error" => "Tinta não encontrada"]);
    }
    $stmt->close();
} else {
    echo json_encode(["error" => "Código da tinta não fornecido"]);
}
?>
