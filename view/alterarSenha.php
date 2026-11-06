<?php
include_once(__DIR__ . "/../controller/utilizadorController.php");

$erro = "";

if (isset($_POST['entrar'])) {
    $email = $_POST['email'] ?? '';
    $senhaAntiga = $_POST['senha_antiga'] ?? '';
    $novaSenha = $_POST['nova_senha'] ?? '';
    $confirmaSenha = $_POST['confirma_senha'] ?? '';

    if (!verificarEmail($email)) {
        $erro = "Email incorreto.";
    } elseif (!verificarSenhaAntiga($email, $senhaAntiga)) {
        $erro = "Senha antiga incorreta.";
    } elseif ($novaSenha !== $confirmaSenha) {
        $erro = "As senhas não são iguais.";
    } elseif ($novaSenha === '12345678') {
    $erro = "Escolha uma senha diferente da senha padrao.";
    } else {
        alterarSenha($email, $novaSenha);
        header("Location: Login.php");
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='../css/LOGIN.css'>
    <title>ALTERAR SENHA</title>
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
                <h2>Alterar senha</h2>
                <p class="subtitulo">Introduza uma nova senha para aceder ao sistema.</p>

                <?php if ($erro !== "") {
                    echo "<p>" . htmlspecialchars($erro) . "</p>";
                } ?>

                <label for="usuario">Email</label>
                <input type="email" name="email" placeholder="Email de utilizador" required>

                <label for="senha">Atiga Palavra-passe</label>
                <input type="text" name="senha_antiga" placeholder="Senha antiga" required>

                <label for="senha">NOVA Palavra-passe</label>
                <input type="password" name="nova_senha" placeholder="••••••••" required>

                <label for="senha">Confirma nova Palavra-passe</label>
                <input type="password" name="confirma_senha" placeholder="••••••••" required>

                <button type="submit" name="entrar">Entrar</button>
            </form>
        </div>

    </div>
</body>

</html>