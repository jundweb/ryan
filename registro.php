<?php
include 'includes/bd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $senha_confirm = $_POST['senha_confirm'];
    $cpf = $_POST['cpf'];
    $data_nascimento = $_POST['data_nascimento'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $cidade = $_POST['cidade'];
    $cep = $_POST['cep'];

    if ($senha !== $senha_confirm) {
        $erro = "As senhas não coincidem.";
    } else {
        $query = "INSERT INTO usuarios (nome, email, senha, cpf, data_nascimento, telefone, endereco, cidade, cep)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssssssss', $nome, $email, $senha, $cpf, $data_nascimento, $telefone, $endereco, $cidade, $cep);
        
        if ($stmt->execute()) {
            echo "<script>
                    alert('Você se cadastrou com sucesso!');
                    window.location.href = 'login.php'; // Redireciona após o alert
                  </script>";
            exit;
        } else {
            $erro = "Erro ao registrar. Tente novamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema de Tintas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Registro de Usuário</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($erro)) : ?>
                            <div class="alert alert-danger"><?= $erro ?></div>
                        <?php endif; ?>
                        <form method="POST" action="registro.php">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" name="nome" id="nome" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="password" name="senha" id="senha" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="senha_confirm" class="form-label">Confirmar Senha</label>
                                <input type="password" name="senha_confirm" id="senha_confirm" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="cpf" class="form-label">CPF</label>
                                <input type="text" name="cpf" id="cpf" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                                <input type="date" name="data_nascimento" id="data_nascimento" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text" name="telefone" id="telefone" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="endereco" class="form-label">Endereço</label>
                                <input type="text" name="endereco" id="endereco" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="cidade" class="form-label">Cidade</label>
                                <input type="text" name="cidade" id="cidade" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="cep" class="form-label">CEP</label>
                                <input type="text" name="cep" id="cep" class="form-control" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Registrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
