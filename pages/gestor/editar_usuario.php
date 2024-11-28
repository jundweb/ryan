<?php
session_start();
include '../../includes/bd.php';

// Verifica se o gestor está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'gestor') {
    header('Location: ../../login.php');
    exit;
}

// Verifica se o ID do usuário foi passado na URL
if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    // Recupera os dados do usuário do banco de dados
    $result = $conn->query("SELECT * FROM usuarios WHERE id_usuario = $userId");
    
    // Se o usuário não existir, redireciona para a página de usuários
    if ($result->num_rows == 0) {
        header('Location: usuarios.php');
        exit;
    }

    // Obtém os dados do usuário
    $user = $result->fetch_assoc();
} else {
    // Se não passar o ID, redireciona para a página de usuários
    header('Location: usuarios.php');
    exit;
}

// Processa o formulário de edição
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $data_nascimento = $_POST['data_nascimento'];
    $telefone = $_POST['telefone'];
    $cpf = $_POST['cpf'];
    $endereco = $_POST['endereco'];
    $cidade = $_POST['cidade'];
    $cep = $_POST['cep'];

    // Atualiza os dados do usuário no banco de dados
    $updateQuery = "UPDATE usuarios SET 
                        nome = '$nome', 
                        email = '$email', 
                        data_nascimento = '$data_nascimento',
                        telefone = '$telefone', 
                        cpf = '$cpf', 
                        endereco = '$endereco', 
                        cidade = '$cidade', 
                        cep = '$cep' 
                    WHERE id_usuario = $userId";

    if ($conn->query($updateQuery)) {
        header('Location: usuarios.php');
        exit;
    } else {
        $errorMessage = "Erro ao atualizar usuário: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário - Sistema de Tintas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../estilos.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row">
            <!-- Menu lateral -->
            <div class="col-md-3">
                <div class="list-group">
                    <a href="dashboard.php" class="list-group-item list-group-item-action">Início</a>
                    <a href="usuarios.php" class="list-group-item list-group-item-action">Usuários</a>
                    <a href="tintas_doadas.php" class="list-group-item list-group-item-action">Tintas Doadas</a>
                    <a href="tintas_solicitadas.php" class="list-group-item list-group-item-action">Tintas Solicitadas</a>
                    <a href="../logout.php" class="list-group-item list-group-item-action text-danger">Sair</a>
                </div>
            </div>
            <!-- Conteúdo principal do dashboard -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Editar Usuário</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($errorMessage)): ?>
                            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
                        <?php endif; ?>
                        <form action="editar_usuario.php?id=<?php echo $user['id_usuario']; ?>" method="POST">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($user['nome']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                                <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" value="<?php echo $user['data_nascimento']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text" class="form-control" id="telefone" name="telefone" value="<?php echo htmlspecialchars($user['telefone']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="cpf" class="form-label">CPF</label>
                                <input type="text" class="form-control" id="cpf" name="cpf" value="<?php echo htmlspecialchars($user['cpf']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="endereco" class="form-label">Endereço</label>
                                <input type="text" class="form-control" id="endereco" name="endereco" value="<?php echo htmlspecialchars($user['endereco']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="cidade" class="form-label">Cidade</label>
                                <input type="text" class="form-control" id="cidade" name="cidade" value="<?php echo htmlspecialchars($user['cidade']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="cep" class="form-label">CEP</label>
                                <input type="text" class="form-control" id="cep" name="cep" value="<?php echo htmlspecialchars($user['cep']); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <a href="usuarios.php" class="btn btn-secondary">Cancelar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
