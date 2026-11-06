<?php

include_once(__DIR__ . "/../controller/utilizadorController.php");
include_once __DIR__ . '/../controller/controllerLogin.php';

if (!$sessao->esta_logado()) {
    header("Location: Login.php");
    exit;
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

$perfil = $_SESSION['perfil'];
exigirPermissao('gerir_utilizador');

$utilizadores = listarUtilizador();
$perfis = listarPerfis();

if (isset($_POST['cadastrar'])) {

    if (
        !empty($_POST['nome']) && !empty($_POST['apelido']) && !empty($_POST['email']) && !empty($_POST['contacto']) && !empty($_POST['username']) && !empty($_POST['senha']) && !empty($_POST['perfil'])
        && !empty($_POST['bi']) && !empty($_POST['estadoCivil']) && !empty($_POST['sexo'])
    ) {
        $utilizador = new utilizadores(null, $_POST['nome'], $_POST['apelido'], $_POST['email'], $_POST['contacto'], $_POST['username'], $_POST['senha'], $_POST['perfil'], $_POST['bi'], $_POST['estadoCivil'], $_POST['sexo']);
        criacaoUtilizador($utilizador);
    } else {
        echo "Preencha todos os campos";
    }
}

?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>CADASTRAR UTILIZADORES</title>
    <link rel='stylesheet' href='../css/CadstrarProduto.css'>
</head>

<body>

    <div class="navbar">
        <div class="marca">
            <img src="img/logo.png" alt="Logo" class="marca-logo">
            <div class="titulo-marca">SISTEMA DE<br />GESTAO DE COMPRAS</div>
        </div>


        <nav class="navlinks">
            <a href="Dashboard.php"><span class="icone"></span>Inicio</a>

            <?php
            if (temPermissao('criar') || temPermissao('listar')) {

                if (temPermissao('criar')) {
                    $paginaContinente = 'cadastrarContinente.php';
                    $paginaPais = 'cadastrarPais.php';
                    $paginaProvincia = 'cadastrarProvincia.php';
                    $paginaDistrito = 'cadastrarDistrito.php';
                    $paginaPosto = 'cadastrarPostoAdministrativo.php';
                    $paginaLocalidade = 'cadastrarLocalidade.php';
                    $paginaBairro = 'cadastrarBairro.php';
                    $paginaProduto = 'CadastrarProduto.php';
                    $paginaFornecedor = 'cadastrarFornecedor.php';
                    $paginaCompra = 'CadastrarCompra.php';
                    $paginaCaixa = 'cadastrarCaixa.php';
                    $paginaCliente = 'cadastarCliente.php';
                } else {
                    $paginaContinente = 'listarContinente.php';
                    $paginaPais = 'listarPais.php';
                    $paginaProvincia = 'listarProvincias.php';
                    $paginaDistrito = 'listarDistrito.php';
                    $paginaPosto = 'listarPostoAdministrativo.php';
                    $paginaLocalidade = 'listarLocalidade.php';
                    $paginaBairro = 'listarBairro.php';
                    $paginaProduto = 'ListarProduto.php';
                    $paginaFornecedor = 'listarFornecedores.php';
                    $paginaCompra = 'ListarCompra.php';
                    $paginaCaixa = 'listarcaixa.php';
                    $paginaCliente = 'listarClientes.php';
                }
            ?>
                <a href="<?= $paginaContinente ?>"><span class="icone"></span>CONTINENTE</a>
                <a href="<?= $paginaPais ?>"><span class="icone"></span>PAÍS</a>
                <a href="<?= $paginaProvincia ?>"><span class="icone"></span>PROVÍNCIA</a>
                <a href="<?= $paginaDistrito ?>"><span class="icone"></span>DISTRITO</a>
                <a href="<?= $paginaPosto ?>"><span class="icone"></span>POSTO ADMINISTRATIVO</a>
                <a href="<?= $paginaLocalidade ?>"><span class="icone"></span>LOCALIDADE</a>
                <a href="<?= $paginaBairro ?>"><span class="icone"></span>BAIRRO</a>
                <a href="<?= $paginaProduto ?>"><span class="icone"></span>PRODUTO</a>
                <a href="<?= $paginaFornecedor ?>"><span class="icone"></span>FORNECEDOR</a>
                <a href="<?= $paginaCompra ?>"><span class="icone"></span>COMPRA</a>
                <a href="<?= $paginaCaixa ?>"><span class="icone"></span>CAIXA</a>
                <a href="<?= $paginaCliente ?>"><span class="icone"></span>CLIENTE</a>

            <?php } ?>

            <?php if (temPermissao('gerir_utilizador')) { ?>
                <a class="on" href="cadastarUtilizadores.php"><span class="icone"></span>UTILIZADORES</a>
                <a href="Perfil.php"><span class="icone"></span>Configurar Perfil</a>
            <?php } ?>

            <?php if (temPermissao('gerir_log')) { ?>
                <a href="ListarLog.php"><span class="icone"></span>Log</a>
            <?php } ?>
        </nav>



        <div style="margin: 15px;">
            <p>Olá, <?= isset($_SESSION['nome']) ? $_SESSION['nome'] : ''; ?>!</p>
            <p>Perfil: <?= isset($_SESSION['perfil']) ? $_SESSION['perfil'] : ''; ?></p>
        </div>
        <a href="alterarSenha.php" class="sair">Alterar Senha</a>
        <a href="logout.php" class="sair">Log out</a>
    </div>

    <div class="mae">

        <div class="cabecalho">
            <div class="abas">
                <div class="aba abaAtiva">CADASTRO DE UTILIZADORES</div>
                <a href="listarUtilizadores.php" class="aba">LISTA DE UTILIZADORES</a>
            </div>
        </div>

        <div class="dalheee">
            <div class="card">
                <form method="post">

                    <div class="topo">
                        <h1 class="titulo"><strong>Novo utilizador</strong></h1>
                    </div>

                    <div class="campo">
                        <div class="campoTipo">
                            <label class="label"><strong>Nome do utilizador</strong></label>
                        </div>
                        <input type="text" name="nome" class="input" required>
                    </div>

                    <div class="campo">
                        <div class="campoTipo">
                            <label class="label"><strong>Apelido do utilizador</strong></label>
                        </div>
                        <input type="text" name="apelido" class="input" required>
                    </div>

                    <div class="campo">
                        <div class="campoTipo">
                            <label class="label"><strong>Numero BI do utilizador</strong></label>
                        </div>
                        <input type="text" name="bi" class="input" required>
                    </div>

                    <div class="campo">
                        <label class="label"> <strong>Estado Civil do utilizador</strong></label>
                        <select name="estadoCivil" class="input" required>
                            <option value="">Selecione um Estado civil</option>
                            <option value="Solteiro">Solteiro(a)</option>
                            <option value="Casado">Casado(a)</option>
                            <option value="Divorciado">Divorciado(a)</option>
                            <option value="Viuvo">Viuvo(a)</option>
                        </select>
                    </div>

                    <div class="campo">
                        <label class="label"> <strong>Genero do utilizador</strong></label>
                        <select name="sexo" class="input" required>
                            <option value="">Selecione um Genero</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Feminino">Feminino</option>
                        </select>
                    </div>

                    <div class="campo">
                        <div class="campo">
                            <div class="campoTipo">
                                <label class="label"><strong>Username</strong></label>
                            </div>
                            <input type="text" name="username" class="input" required>
                        </div>

                        <div class="campo">
                            <div class="campo ">
                                <div class="campoTipo">
                                    <label class="label"><strong>Email</strong></label>
                                </div>
                                <input type="email" name="email" class="input" required>
                            </div>

                            <div class="campo">
                                <div class="campo ">
                                    <div class="campoTipo">
                                        <label class="label"><strong>Contacto</strong></label>
                                    </div>
                                    <input type="number" name="contacto" class="input" required>
                                </div>

                                <div class="campo">
                                    <div class="campo">
                                        <div class="campoTipo">
                                            <label class="label"><strong>Senha</strong></label>
                                        </div>
                                        <input type="text" name="senha" class="input" value="12345678" readonly>
                                    </div>

                                    <div class="campo">
                                        <label class="label"> <strong>Perfil do utilizador</strong></label>
                                        <select name="perfil" class="input" required>
                                            <?php foreach ($perfis as $perfil) { ?>
                                                <option value="<?php echo $perfil['nomePerfil']; ?>">
                                                    <?php echo $perfil['nomePerfil']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>

                                    </div>
                                </div>

                                <div class="acao">

                                    <button type="submit" name="cadastrar" class="botao">Cadastrar utilizador</button>

                                </div>

                </form>
            </div>
        </div>

    </div>

</body>

</html>