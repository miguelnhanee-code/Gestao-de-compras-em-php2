<?php
include_once(__DIR__ . "/../controller/postoAdministrativoController.php");
include_once __DIR__ . '/../controller/controllerLogin.php';

if (!$sessao->esta_logado()) {
    header("Location: Login.php");
    exit;
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

$perfil = $_SESSION['perfil'];

exigirPermissao('listar');

$postosAdministrativos = listarPostoAdministrativo();
$distritos = listarDistritos();

if (isset($_POST['remover']) && temPermissao('remover')) {
    remocaoPostoAdministrativo($_POST['id']);
    header('Location: listarPostoAdministrativo.php#');
    exit;
}

if (
    isset($_POST['guardar']) &&
    temPermissao('actualizar') &&
    !empty($_POST['id']) &&
    !empty($_POST['nomePostoAdministrativo']) &&
    !empty($_POST['idDistrito'])
) {
    $postoAdministrativo = encontraPostoAdministrativo($_POST['id']);

    if ($postoAdministrativo) {
        $postoAdministrativo->setNomePostoAdministrativo(
            $_POST['nomePostoAdministrativo']
        );

        $postoAdministrativo->setIdDistrito(
            $_POST['idDistrito']
        );

        edicaoPostoAdministrativo($postoAdministrativo);
    }

    header('Location: listarPostoAdministrativo.php#');
    exit;
}

$postosAdministrativos = listarPostoAdministrativo();
$pesquisa = $_POST['pesquisaCampo'] ?? '';

if (isset($_POST['pesquisarBt']) && temPermissao('pesquisar')) {
    if (!empty($pesquisa)) {
        $postosAdministrativos = pesquisarPostoAdministrativo($pesquisa);
    }
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LISTAR POSTOS ADMINISTRATIVOS</title>
    <link rel="stylesheet" href="../css/ListarProduto.css">
</head>

<body>

    <div class="navbar">

        <div class="marca">
            <img src="img/logo.png" alt="Logo" class="marca-logo">
            <div class="titulo-marca">
                SISTEMA DE<br>
                GESTAO DE COMPRAS
            </div>
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
                <a class="on" href="<?= $paginaPosto ?>"><span class="icone"></span>POSTO ADMINISTRATIVO</a>
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

                <?php if (temPermissao('criar')) { ?>
                    <a href="cadastrarPostoAdministrativo.php" class="aba">
                        CADASTRO DE POSTOS ADMINISTRATIVOS
                    </a>
                <?php } ?>

                <div class="aba abaAtiva">
                    LISTA DE POSTOS ADMINISTRATIVOS
                </div>

            </div>

        </div>

        <div class="dalheee">

            <div class="card">

                <div class="topo">
                    <h1 class="titulo">
                        <strong>Lista de Postos Administrativos</strong>
                    </h1>
                </div>

                <?php if (temPermissao('pesquisar')) { ?>
                    <form method="post">
                        <div class="linha">
                            <div class="campo campoBusca">
                                <input type="text" name="pesquisaCampo" class="input" placeholder="Pesquisar posto administrativo ou distrito" value="">
                            </div>
                            <div class="campo">
                                <button type="submit" name="pesquisarBt" class="botao"> Pesquisar </button>
                            </div>

                        </div>

                    </form>

                <?php } ?>

                <div class="tabela">

                    <table>

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome do Posto Administrativo</th>
                                <th>Distrito</th>
                                <th>Acao</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if (count($postosAdministrativos) == 0) { ?>

                                <tr>
                                    <td colspan="4">
                                        SEM NENHUM POSTO ADMINISTRATIVO REGISTADO
                                    </td>
                                </tr>

                            <?php } else { ?>

                                <?php foreach ($postosAdministrativos as $posto) { ?>
                                    <tr>
                                        <td>
                                            <?= $posto->getIdPostoAdministrativo() ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($posto->getNomePostoAdministrativo(), ENT_QUOTES, 'UTF-8') ?>
                                        </td>
                                        <td> <?= htmlspecialchars($posto->getNomeDistrito(),   ENT_QUOTES,   'UTF-8') ?>
                                        </td>

                                        <td>

                                            <div class="acoes">

                                                <?php
                                                if (temPermissao('actualizar') ||  temPermissao('remover')) {
                                                ?>
                                                    <?php if (temPermissao('actualizar')) { ?>
                                                        <a href="#editar-<?= $posto->getIdPostoAdministrativo() ?>" class="botaoAtualizar"> Editar </a>
                                                    <?php } ?>
                                                    <?php if (temPermissao('remover')) { ?>
                                                        <a href="#remover-<?= $posto->getIdPostoAdministrativo() ?>" class="botaoRemover"> REMOVER
                                                        </a>
                                                    <?php } ?>

                                                <?php } ?>

                                            </div>

                                        </td>

                                    </tr>

                                <?php } ?>

                            <?php } ?>

                        </tbody>

                    </table>

                    <?php foreach ($postosAdministrativos as $posto) { ?>

                        <!-- MODAL EDITAR -->

                        <div
                            class="modalFundo"
                            id="editar-<?= $posto->getIdPostoAdministrativo() ?>">

                            <div class="modalCaixa">

                                <div class="modalTopo">
                                    <h2 class="titulo">
                                        Editar Posto Administrativo
                                    </h2>

                                    <a href="#" class="modalFechar">x</a>
                                </div>

                                <form method="post" action="">
                                    <input type="hidden" name="id" value="<?= $posto->getIdPostoAdministrativo() ?>">
                                    <div class="campo">

                                        <div class="campoTipo">
                                            <label class="label">
                                                <strong>Nome do Posto Administrativo</strong>
                                            </label>
                                        </div>

                                        <input type="text" name="nomePostoAdministrativo" class="input" value="<?= ($posto->getNomePostoAdministrativo()) ?>" required>

                                    </div>
                                    <div class="campo">
                                        <label class="label">
                                            <strong>Distrito</strong>
                                        </label>
                                        <select name="idDistrito" class="input" required>
                                            <option value="">
                                                Selecione um distrito
                                            </option>
                                            <?php foreach ($distritos as $distrito) { ?>
                                                <option value="<?= $distrito['idDistrito'] ?>" <?= $distrito['idDistrito'] == $posto->getIdDistrito() ? 'selected'  : '' ?>> <?= htmlspecialchars($distrito['nomeDistrito'],  ENT_QUOTES,   'UTF-8') ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="acao">
                                        <button type="submit" name="guardar" class="botao"> Guardar</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- FIM MODAL EDITAR -->

                    <?php } ?>

                    <?php foreach ($postosAdministrativos as $posto) { ?>

                        <!-- MODAL REMOVER -->

                        <div
                            class="modalFundo"
                            id="remover-<?= $posto->getIdPostoAdministrativo() ?>">

                            <div class="modalCaixa">

                                <div class="modalTopo">
                                    <h2 class="titulo">
                                        Remover Posto Administrativo
                                    </h2>

                                    <a href="#" class="modalFechar">x</a>
                                </div>

                                <form method="post" action="">

                                    <input type="hidden" name="id" value="<?= $posto->getIdPostoAdministrativo() ?>">

                                    <div class="campo">

                                        <label class="label">
                                            <strong>Nome do Posto Administrativo</strong>
                                        </label>

                                        <input type="text" class="input" value="<?= $posto->getNomePostoAdministrativo() ?>" readonly>

                                    </div>



                                    <div class="campo">
                                        <label class="label">
                                            <strong>Distrito</strong>
                                        </label>
                                        <select name="idDistrito" class="input" required>
                                            <option value="">
                                                Selecione um distrito
                                            </option>
                                            <?php foreach ($distritos as $distrito) { ?>
                                                <option value="<?= $distrito['idDistrito'] ?>" <?= $distrito['idDistrito'] == $posto->getIdDistrito() ? 'selected'  : '' ?>> <?= htmlspecialchars($distrito['nomeDistrito'],  ENT_QUOTES,   'UTF-8') ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="acao">
                                        <button type="submit" name="remover" class="botao">REMOVER</button>
                                    </div>

                                </form>

                            </div>

                        </div>

                        <!-- FIM MODAL REMOVER -->

                    <?php } ?>

                </div>

            </div>

        </div>

    </div>

</body>

</html>