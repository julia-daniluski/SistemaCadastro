<?php
session_start();
include('conexao.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $usuario = $_POST['usuario'];
    $senha = md5($_POST['senha']);

    // Protegendo contra SQL Injection usando Prepared Statements
    $sql = "SELECT * FROM usuarios WHERE usuario = ? AND senha = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usuario, $senha);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['usuario'] = $usuario;
        header('Location: index.php');
        exit();
    } else {
        $error = "Usuário ou senha inválidos.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container" style="width: 400px;">
    <h2>Login</h2>
    <form method="post" action="">
        <label for="usuario">Usuário: </label>
        <input type="text" name="usuario" required>
        <label for="senha">Senha: </label>
        <input type="password" name="senha" required>
        <button type="submit" style="margin-bottom: 30px;">Entrar</button>
        <?php if (isset($error)) echo "<p class='message error'>$error</p>"; ?>
    </form>
</div>
</body>
</html>
