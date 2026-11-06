<?php
// session_start();
include_once(__DIR__ . "/../controller/controllerLogin.php");
include_once(__DIR__ . "/../controller/controllerLogin.php");

// if(!$sessao->esta_logado()){
//     header("Location : Dashboard.php");
//     exit;
// }

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// Ao abrir a tela de login (sem enviar o formulário), termina qualquer sessão ativa
if (!isset($_POST['entrar'])) {
    $sessao->logout();
    session_destroy();
}

if (isset($_POST['entrar'])) {
    if (!empty($_POST['email']) && !empty($_POST['senha'])) {

            if (usaSenhaPadrao($_POST['email'], $_POST['senha'])) { 
            $_SESSION['email_trocar_senha'] = $_POST['email'];
            header("Location: alterarSenha.php");
            exit;
        }
        login($_POST['email'], $_POST['senha']);
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='../css/LOGIN.css'>
    <title>Log in</title>
</head>

<body>
    <div class="mae">

        <div class="esquerda">
            <div class="logo">
                <img src="img/logo.png" alt="Logo" class="marca-logo">
                <span>SISTEMA DE<br>GESTÃO DE COMPRAS</span>
            </div>

            <div class="mensagem">
                <h1>Controle as suas compras num só lugar.</h1>
                <p>Produtos, fornecedores, clientes, caixa e envios, tudo integrado.</p>
            </div>

            <small></small>
        </div>

        <div class="direita">
            <form method="post">
                <h2>Entrar</h2>
                <p class="subtitulo">Introduza as suas credenciais para aceder ao sistema.</p>

                <label for="usuario">Email</label>
                <input type="email" name="email" placeholder="Email de utilizador" required>

                <label for="senha">Palavra-passe</label>
                <input type="password" name="senha" placeholder="••••••••" required>

                <button type="submit" name="entrar">Entrar</button>
                <!-- <a href="#">Esqueceu a palavra-passe?</a> -->
            </form>
        </div>

    </div>
</body>

</html>