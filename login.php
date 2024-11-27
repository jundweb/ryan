<?php
include 'includes/bd.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $tipo = $_POST['tipo']; // "usuario" ou "gestor"

    if ($tipo === 'usuario') {
        $query = "SELECT * FROM usuarios WHERE email = ? AND senha = ?";
    } else {
        $query = "SELECT * FROM gestores WHERE email = ? AND senha = ?";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $email, $senha);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id_usuario'] ?? $user['id_gestor'];
        $_SESSION['user_tipo'] = $tipo;
        $_SESSION['user_nome'] = $user['nome'];

        if ($tipo === 'usuario') {
            header('Location: pages/user/dashboard.php');
        } else {
            header('Location: pages/gestor/dashboard.php');
        }
        exit;
    } else {
        $erro = "E-mail ou senha inválidos.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Tintas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Login</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($erro)) : ?>
                            <div class="alert alert-danger"><?= $erro ?></div>
                        <?php endif; ?>
                        <form method="POST" action="login.php">
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="password" name="senha" id="senha" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo de Usuário</label>
                                <select name="tipo" id="tipo" class="form-select" required>
                                    <option value="usuario">Usuário</option>
                                    <option value="gestor">Gestor</option>
                                </select>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Entrar</button>
                            </div>
                        </form>
                        <div class="mt-3 text-center">
                            <p>Não tem uma conta? <a href="registro.php">Registre-se</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
