<?php
include_once(__DIR__ . "/../controller/controllerProvincia.php");
include_once __DIR__ . '/../controller/controllerLogin.php';
include_once __DIR__ . '/../controller/controllerContinente.php';
include_once(__DIR__ . "/../controller/controllerPais.php");

if (!$sessao->esta_logado()) {
    header("Location: Login.php");
    exit;
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

$perfil = $_SESSION['perfil'] ?? '';
exigirPermissao('criar');

// listas completas
$continentes = listarContinentes();
$paises      = listarPais();

// valores escolhidos
$idContinente = $_POST['idContinente'] ?? '';
$idPais       = $_POST['idPais'] ?? '';

if (isset($_POST['gravar'])) {
    if (
        !empty(trim($_POST['nomeProvincia'])) &&
        !empty($idPais)
    ) {
        $provincia = new Provincia(null, trim($_POST['nomeProvincia']), $idPais, null);
        criacaoProvincia($provincia);
    } else {
        echo "Preencha todos os campos, incluindo a localização até ao país.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CADASTRAR PROVÍNCIA</title>
    <link rel="stylesheet" href="../css/cadastrarDistrito.css">
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
                <a class="on" href="<?= $paginaProvincia ?>"><span class="icone"></span>PROVÍNCIA</a>
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
                <a href="cadastarUtilizadores.php"><span class="icone"></span>UTILIZADORES</a>
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
                <div class="aba abaAtiva">CADASTRO DE PROVÍNCIA</div>
                <?php if (temPermissao('listar')) { ?>
                    <a href="listarProvincias.php" class="aba">LISTA DE PROVÍNCIAS</a>
                <?php } ?>
            </div>
        </div>

        <div class="dalheee">
            <div class="card">
                <form method="post">

                    <!-- Botão por omissão (Enter): grava em vez de filtrar -->
                    <button type="submit" name="gravar" style="display:none" tabindex="-1" aria-hidden="true"></button>

                    <div class="topo">
                        <h1 class="titulo"><strong>Nova província</strong></h1>
                    </div>


                    <div class="campo campoFlex">
                        <label class="label"><strong>Continente</strong></label>
                        <select name="idContinente" class="input">
                            <option value="">Selecione</option>
                            <?php foreach ($continentes as $continente) { ?>
                                <option value="<?= $continente->getIdContinente() ?>"
                                    <?= $continente->getIdContinente() == $idContinente ? 'selected' : '' ?>>
                                    <?= $continente->getNomeContinente() ?>
                                </option>
                            <?php } ?>
                        </select>
                        <button type="submit" name="filtrar" class="botao-atualizar" formnovalidate>Filtrar países</button>
                    </div>

                    <div class="campo">
                        <label class="label"><strong>País</strong></label>
                        <select name="idPais" class="input" required>
                            <option value="">Selecione</option>
                            <?php foreach ($paises as $pais) { ?>
                                <?php if ($pais->getIdContinente() == $idContinente) { ?>
                                    <option value="<?= $pais->getIdPais() ?>"
                                        <?= $pais->getIdPais() == $idPais ? 'selected' : '' ?>>
                                        <?= $pais->getNomePais() ?>
                                    </option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>


                    <div class="campo">
                        <div class="campoTipo">
                            <label class="label"><strong>Nome da província</strong></label>
                        </div>
                        <input type="text" name="nomeProvincia" class="input" value="<?= $_POST['nomeProvincia'] ?? '' ?>" required>
                    </div>

                    <div class="acao">
                        <button type="submit" name="gravar" class="botao">Cadastrar província</button>
                    </div>

                </form>
            </div>
        </div>

    </div>

</body>

</html>