<?php
include_once(__DIR__ . "/../controller/bairroController.php");
include_once(__DIR__ . "/../controller/controllerFornecedor.php");

include_once __DIR__ . '/../controller/controllerLogin.php';

if (!$sessao->esta_logado()) {
    header("Location: Login.php");
    exit;
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

$perfil = $_SESSION['perfil'];
exigirPermissao('criar');

// listas completas
$continentes = listarContinentesFornecedor();
$paises      = listarPaisesFornecedor();
$provincias  = listarProvinciasFornecedor();
$distritos   = listarDistritosFornecedor();
$postos      = listarPostosFornecedor();
$localidades = listarLocalidades();

// valores escolhidos
$idContinente          = $_POST['idContinente'] ?? '';
$idPais                = $_POST['idPais'] ?? '';
$idProvincia           = $_POST['idProvincia'] ?? '';
$idDistrito            = $_POST['idDistrito'] ?? '';
$idPostoAdministrativo = $_POST['idPostoAdministrativo'] ?? '';
$idLocalidade          = $_POST['idLocalidade'] ?? '';

// limpa a escolha se ela nao pertencer ao nivel acima (evita valores antigos na cascata)
function pertence($lista, $chave, $id, $chavePai, $idPai)
{
    foreach ($lista as $item) {
        if ($item[$chave] == $id && $item[$chavePai] == $idPai) {
            return $id;
        }
    }
    return '';
}

$idPais                = pertence($paises, 'idPais', $idPais, 'idContinente', $idContinente);
$idProvincia           = pertence($provincias, 'idProvincia', $idProvincia, 'idPais', $idPais);
$idDistrito            = pertence($distritos, 'idDistrito', $idDistrito, 'idProvincia', $idProvincia);
$idPostoAdministrativo = pertence($postos, 'idPostoAdministrativo', $idPostoAdministrativo, 'idDistrito', $idDistrito);
$idLocalidade          = pertence($localidades, 'idLocalidade', $idLocalidade, 'idPostoAdministrativo', $idPostoAdministrativo);

if (isset($_POST['gravar'])) {
    if (!empty(trim($_POST['nomeBairro'] ?? '')) && !empty($idLocalidade)) {
        $bairro = new Bairro(null, trim($_POST['nomeBairro']), $idLocalidade, null, null, null, null, null, null, null, null, null, null, null);
        criacaoBairro($bairro);
    }
}

?>
<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>CADASTRAR BAIRRO</title>
    <link rel='stylesheet' href='../css/cadastrarDistrito.css'>
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
                <a class="on" href="<?= $paginaBairro ?>"><span class="icone"></span>BAIRRO</a>
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
                <div class="aba abaAtiva">CADASTRO DE BAIRRO</div>
                <?php if (temPermissao('listar')) { ?><a href="listarBairro.php" class="aba">LISTA DE BAIRROS</a><?php } ?>
            </div>
        </div>

        <div class="dalheee">
            <div class="card">
                <form method="post">

                    <div class="topo">
                        <h1 class="titulo"><strong>Novo bairro</strong></h1>
                    </div>

                    <div class="campo campoFlex">
                        <label class="label"><strong>Continente</strong></label>
                        <select name="idContinente" class="input">
                            <option value="">Selecione</option>
                            <?php foreach ($continentes as $x) { ?>
                                <?php if (true) { ?>
                                    <option value="<?= $x['idContinente'] ?>"
                                        <?= $x['idContinente'] == $idContinente ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($x['nomeContinente']) ?>
                                    </option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                        <button type="submit" name="filtrar" class="botaoVer" formnovalidate>Filtrar países</button>
                    </div>

                    <div class="campo campoFlex">
                        <label class="label"><strong>País</strong></label>
                        <select name="idPais" class="input">
                            <option value="">Selecione</option>
                            <?php foreach ($paises as $x) { ?>
                                <?php if ($x['idContinente'] == $idContinente) { ?>
                                    <option value="<?= $x['idPais'] ?>"
                                        <?= $x['idPais'] == $idPais ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($x['nomePais']) ?>
                                    </option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                        <button type="submit" name="filtrar" class="botaoVer" formnovalidate>Filtrar províncias</button>
                    </div>

                    <div class="campo campoFlex">
                        <label class="label"><strong>Província</strong></label>
                        <select name="idProvincia" class="input">
                            <option value="">Selecione</option>
                            <?php foreach ($provincias as $x) { ?>
                                <?php if ($x['idPais'] == $idPais) { ?>
                                    <option value="<?= $x['idProvincia'] ?>"
                                        <?= $x['idProvincia'] == $idProvincia ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($x['nomeProvincia']) ?>
                                    </option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                        <button type="submit" name="filtrar" class="botaoVer" formnovalidate>Filtrar distritos</button>
                    </div>

                    <div class="campo campoFlex">
                        <label class="label"><strong>Distrito</strong></label>
                        <select name="idDistrito" class="input">
                            <option value="">Selecione</option>
                            <?php foreach ($distritos as $x) { ?>
                                <?php if ($x['idProvincia'] == $idProvincia) { ?>
                                    <option value="<?= $x['idDistrito'] ?>"
                                        <?= $x['idDistrito'] == $idDistrito ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($x['nomeDistrito']) ?>
                                    </option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                        <button type="submit" name="filtrar" class="botaoVer" formnovalidate>Filtrar postos</button>
                    </div>

                    <div class="campo campoFlex">
                        <label class="label"><strong>Posto administrativo</strong></label>
                        <select name="idPostoAdministrativo" class="input">
                            <option value="">Selecione</option>
                            <?php foreach ($postos as $x) { ?>
                                <?php if ($x['idDistrito'] == $idDistrito) { ?>
                                    <option value="<?= $x['idPostoAdministrativo'] ?>"
                                        <?= $x['idPostoAdministrativo'] == $idPostoAdministrativo ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($x['nomePostoAdministrativo']) ?>
                                    </option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                        <button type="submit" name="filtrar" class="botaoVer" formnovalidate>Filtrar localidades</button>
                    </div>

                    <div class="campo">
                        <label class="label"><strong>Localidade</strong></label>
                        <select name="idLocalidade" class="input" required>
                            <option value="">Selecione</option>
                            <?php foreach ($localidades as $x) { ?>
                                <?php if ($x['idPostoAdministrativo'] == $idPostoAdministrativo) { ?>
                                    <option value="<?= $x['idLocalidade'] ?>"
                                        <?= $x['idLocalidade'] == $idLocalidade ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($x['nomeLocalidade']) ?>
                                    </option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="campo">
                        <div class="campoTipo">
                            <label class="label"><strong>Nome do bairro</strong></label>
                        </div>
                        <input type="text" name="nomeBairro" class="input" value="<?= htmlspecialchars($_POST['nomeBairro'] ?? '') ?>" required>
                    </div>

                    <div class="acao">
                        <button type="submit" name="gravar" class="botao">Cadastrar bairro</button>
                    </div>

                </form>
            </div>
        </div>
    </div>


</body>

</html>