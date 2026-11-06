<?php
include_once __DIR__ . '/../controller/localidadeController.php';
include_once __DIR__ . '/../controller/postoAdministrativoController.php';
include_once __DIR__ . '/../controller/controllerFornecedor.php';

include_once __DIR__ . '/../controller/controllerLogin.php';
if (!$sessao->esta_logado()) {
    header("Location: Login.php");
    exit;
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
$perfil = $_SESSION['perfil'];
exigirPermissao('listar');

$localidades = listarLocalidade();
$continentes = listarContinentesFornecedor();
$paises = listarPaisesFornecedor();
$provincias = listarProvinciasFornecedor();
$distritos = listarDistritosFornecedor();
$postosAdministrativos = listarPostosFornecedor();

if (isset($_POST['remover']) && temPermissao('remover')) {
    remocaoLocalidade($_POST['id']);
    header('Location: listarLocalidade.php#');
    exit;
}

if (
    isset($_POST['guardar']) && temPermissao('actualizar') && !empty($_POST['id']) &&
    !empty($_POST['atualizarLocalidade']) && !empty($_POST['idPostoAdministrativo'])
) {
    $localidade = encontraLocalidade($_POST['id']);

    if ($localidade) {
        $localidade->setNomeLocalidade($_POST['atualizarLocalidade']);
        $localidade->setIdPostoAdministrativo($_POST['idPostoAdministrativo']);
        edicaoLocalidade($localidade);
    }
    header('Location: listarLocalidade.php#');
    exit;
}

$localidades = listarLocalidade();
$pesquisa = temPermissao('pesquisar') ? ($_POST['pesquisaCampo'] ?? '') : '';
if (isset($_POST['pesquisarBt']) && temPermissao('pesquisar')) {
    $localidades = pesquisarLocalidade($pesquisa);
}

?>

<!DOCTYPE html>
<html lang='pt'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>LISTAR LOCALIDADES</title>
    <link rel='stylesheet' href='../css/listarCaixa.css'>
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
                <a class="on" href="<?= $paginaLocalidade ?>"><span class="icone"></span>LOCALIDADE</a>
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
                <?php if (temPermissao('criar')) { ?><a href="cadastrarLocalidade.php" class="aba">CADASTRO DE LOCALIDADES</a><?php } ?>
                <div class="aba abaAtiva">LISTA DE LOCALIDADES</div>
            </div>
        </div>

        <div class="dalheee">
            <div class="card">

                <div class="topo">
                    <h1 class="titulo"><strong>Lista de localidades</strong></h1>
                </div>

                <?php if (temPermissao('pesquisar')) { ?>
                    <form method="post">
                        <div class="linha">
                            <div class="campo campoBusca">
                                <input type="text" name="pesquisaCampo" class="input" placeholder="Pesquisar localidade" value="<?= htmlspecialchars($pesquisa) ?>">
                            </div>
                            <div class="campo">
                                <button type="submit" name="pesquisarBt" class="botao">Pesquisar</button>
                            </div>
                        </div>
                    </form>
                <?php } ?>
                <div class="tabela">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome da localidade</th>
                                <th>Posto Administrativo</th>
                                <th>Acoes</th>
                            </tr>
                        </thead>

                        <?php if (count($localidades) == 0) { ?>
                            <tr>
                                <td colspan="4">SEM NENHUMA LOCALIDADE REGISTADA</td>
                            </tr>
                        <?php } else { ?>
                            <?php foreach ($localidades as $localidade) { ?>

                                <tr>
                                    <td><?= $localidade->getIdLocalidade() ?></td>
                                    <td><?= htmlspecialchars($localidade->getNomeLocalidade()) ?></td>
                                    <td><?= $localidade->getPostoAdministrativo() ?></td>

                                    <td>
                                        <div class="acoes">

                                            <?php
                                            if (temPermissao('actualizar') || temPermissao('remover')) { ?>

                                                <?php
                                                if (temPermissao('actualizar')) {
                                                ?>
                                                    <a href="#editar-<?= $localidade->getIdLocalidade() ?>" class="botaoAtualizar">
                                                        Editar
                                                    </a>
                                                <?php } ?>

                                                <?php
                                                if (temPermissao('remover')) {
                                                ?>
                                                    <a href="#remover-<?= $localidade->getIdLocalidade() ?>" class="botaoRemover">
                                                        REMOVER
                                                    </a>
                                                <?php } ?>

                                            <?php } ?>

                                        </div>
                                    </td>
                                </tr>
                        <?php }
                        } ?>

                    </table>

                    <?php foreach ($localidades as $localidade) { ?>
                        <?php
                        // valores escolhidos no modal (POST do próprio modal ou valores actuais da localidade)
                        $idContinente = $idPais = $idProvincia = $idDistrito = '';
                        $idPostoAdministrativoSelecionado = $localidade->getIdPostoAdministrativo();

                        if (isset($_POST['filtrar']) && ($_POST['id'] ?? '') == $localidade->getIdLocalidade()) {
                            $idContinente = $_POST['idContinente'] ?? '';
                            $idPais = $_POST['idPais'] ?? '';
                            $idProvincia = $_POST['idProvincia'] ?? '';
                            $idDistrito = $_POST['idDistrito'] ?? '';
                            $idPostoAdministrativoSelecionado = $_POST['idPostoAdministrativo'] ?? '';
                        } else {
                            foreach ($postosAdministrativos as $postoAdministrativo) {
                                if ($postoAdministrativo['idPostoAdministrativo'] == $idPostoAdministrativoSelecionado) {
                                    $idDistrito = $postoAdministrativo['idDistrito'];
                                }
                            }
                            foreach ($distritos as $distrito) {
                                if ($distrito['idDistrito'] == $idDistrito) {
                                    $idProvincia = $distrito['idProvincia'];
                                }
                            }
                            foreach ($provincias as $provincia) {
                                if ($provincia['idProvincia'] == $idProvincia) {
                                    $idPais = $provincia['idPais'];
                                }
                            }
                            foreach ($paises as $pais) {
                                if ($pais['idPais'] == $idPais) {
                                    $idContinente = $pais['idContinente'];
                                }
                            }
                        }
                        ?>
                        <!-- MODAL-->
                        <div class="modalFundo" id="editar-<?= $localidade->getIdLocalidade() ?>">
                            <div class="modalCaixa" style="max-width: 1000px;">
                                <div class="modalTopo">
                                    <h2 class="titulo">Editar localidade</h2>
                                    <a href="#" class="modalFechar">x</a>
                                </div>

                                <form method="post" action="#editar-<?= $localidade->getIdLocalidade() ?>">
                                    <input type="hidden" name="id" value="<?= $localidade->getIdLocalidade() ?>">

                                    <div class="linha">
                                        <div class="campo campoFlex">
                                            <label class="label"><strong>Continente</strong></label>
                                            <select name="idContinente" class="input">
                                                <option value="">Selecione</option>
                                                <?php foreach ($continentes as $continente) { ?>
                                                    <option value="<?= $continente['idContinente'] ?>"
                                                        <?= $continente['idContinente'] == $idContinente ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($continente['nomeContinente']) ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <button type="submit" name="filtrar" class="botao-atualizar" style="margin-top: 8px;"
                                             formnovalidate>Filtrar países</button>
                                        </div>

                                        <div class="campo campoFlex">
                                            <label class="label"><strong>País</strong></label>
                                            <select name="idPais" class="input">
                                                <option value="">Selecione</option>
                                                <?php foreach ($paises as $pais) { ?>
                                                    <?php if ($pais['idContinente'] == $idContinente) { ?>
                                                        <option value="<?= $pais['idPais'] ?>"
                                                            <?= $pais['idPais'] == $idPais ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($pais['nomePais']) ?>
                                                        </option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                            <button type="submit" name="filtrar" class="botao-atualizar" style="margin-top: 8px;" 
                                            formnovalidate>Filtrar províncias</button>
                                        </div>

                                        <div class="campo campoFlex">
                                            <label class="label"><strong>Província</strong></label>
                                            <select name="idProvincia" class="input">
                                                <option value="">Selecione</option>
                                                <?php foreach ($provincias as $provincia) { ?>
                                                    <?php if ($provincia['idPais'] == $idPais) { ?>
                                                        <option value="<?= $provincia['idProvincia'] ?>"
                                                            <?= $provincia['idProvincia'] == $idProvincia ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($provincia['nomeProvincia']) ?>
                                                        </option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                            <button type="submit" name="filtrar" class="botao-atualizar" style="margin-top: 8px;" 
                                            formnovalidate>Filtrar distritos</button>
                                        </div>
                                    </div>

                                    <div class="linha">
                                        <div class="campo campoFlex">
                                            <label class="label"><strong>Distrito</strong></label>
                                            <select name="idDistrito" class="input">
                                                <option value="">Selecione</option>
                                                <?php foreach ($distritos as $distrito) { ?>
                                                    <?php if ($distrito['idProvincia'] == $idProvincia) { ?>
                                                        <option value="<?= $distrito['idDistrito'] ?>"
                                                            <?= $distrito['idDistrito'] == $idDistrito ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($distrito['nomeDistrito']) ?>
                                                        </option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                            <button type="submit" name="filtrar" class="botao-atualizar" style="margin-top: 8px;"
                                             formnovalidate>Filtrar postos administrativos</button>
                                        </div>

                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Posto Administrativo</strong></label>
                                            </div>
                                            <select name="idPostoAdministrativo" class="input">
                                                <option value="">Selecione o posto administrativo</option>
                                                <?php foreach ($postosAdministrativos as $postoAdministrativo) { ?>
                                                    <?php if ($postoAdministrativo['idDistrito'] == $idDistrito) { ?>
                                                        <option value="<?= $postoAdministrativo['idPostoAdministrativo'] ?>"
                                                            <?= $postoAdministrativo['idPostoAdministrativo'] == $idPostoAdministrativoSelecionado ?
                                                             'selected' : '' ?>>
                                                            <?= htmlspecialchars($postoAdministrativo['nomePostoAdministrativo']) ?>
                                                        </option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Nome da localidade</strong></label>
                                            </div>
                                            <input type="text" name="atualizarLocalidade" class="input" value="
                                            <?= htmlspecialchars($localidade->getNomeLocalidade()) ?>">
                                        </div>
                                    </div>

                                    <div class="acao">
                                        <button type="submit" name="guardar" class="botao">Guardar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- fim da modal -->
                    <?php } ?>

                    <?php foreach ($localidades as $localidade) { ?>
                        <!-- MODAL-->
                        <div class="modalFundo" id="remover-<?= $localidade->getIdLocalidade() ?>">
                            <div class="modalCaixa">
                                <div class="modalTopo">
                                    <h2 class="titulo">REMOVER LOCALIDADE</h2>
                                    <a href="#" class="modalFechar">x</a>
                                </div>

                                <form method="post" action="">
                                    <input type="hidden" name="id" value="<?= $localidade->getIdLocalidade() ?>">

                                    <div class="campo">
                                        <div class="campoTipo">
                                            <label class="label"><strong>Nome da localidade</strong></label>
                                        </div>
                                        <input type="text" class="input" value="<?= htmlspecialchars
                                        ($localidade->getNomeLocalidade()) ?>" readonly>
                                    </div>

                                    <div class="acao">
                                        <button type="submit" name="remover" class="botao">REMOVER</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- fim da modal -->
                    <?php } ?>

                </div>

            </div>
        </div>

    </div>

</body>

</html>