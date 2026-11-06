<?php
include_once __DIR__ . '/../controller/controllerProduto.php';
include_once __DIR__ . '/../controller/controllerFornecedor.php';
include_once __DIR__ . '/../model/Produto.php';

include_once __DIR__ . '/../controller/controllerLogin.php';

if (!$sessao->esta_logado()) {
    header("Location: Login.php");
    exit;
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

$perfil = $_SESSION['perfil'];
exigirPermissao('listar');

$produtos = listarProduto();
$fornecedores = listarFornecedor();

if (isset($_POST['remover']) && temPermissao('remover')) {
    remocaoProduto($_POST['id']);
    header('Location: ListarProduto.php');
    exit;
}

if (
    isset($_POST['guardar']) &&
    temPermissao('actualizar') &&
    !empty($_POST['id']) &&
    !empty($_POST['produto']) &&
    !empty($_POST['precoUnitario']) &&
    !empty($_POST['dataConfeccao']) &&
    !empty($_POST['dataValidade'])
) {
    $produto = encontraProduto($_POST['id']);

    if ($produto) {
        $produto->setProduto($_POST['produto']);
        $produto->setPrecoUnitario($_POST['precoUnitario']);
        $produto->setDataConfecao($_POST['dataConfeccao']);
        $produto->setDataValidade($_POST['dataValidade']);
        $produto->setStock($_POST['stock']);
        $produto->setUnidadeMedida($_POST['unidadeMedida']);
        $produto->setIdFornecedor($_POST['idFornecedor']);

        edicaoProduto($produto);
    }

    header('Location: ListarProduto.php#');
    exit;
}

$produtos = listarProduto();
$pesquisa = $_POST['pesquisaCampo'] ?? '';

if (isset($_POST['pesquisarBt']) && temPermissao('pesquisar')) {
    if (!empty($pesquisa)) {
        $produtos = pesquisarProduto($pesquisa);
    }
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LISTAR PRODUTO</title>
    <link rel="stylesheet" href="../css/ListarProduto.css">
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
                <a class="on" href="<?= $paginaProduto ?>"><span class="icone"></span>PRODUTO</a>
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
                    <a href="CadastrarProduto.php" class="aba">CADASTRO DE PRODUTO</a>
                <?php } ?>

                <div class="aba abaAtiva">LISTA DE PRODUTO</div>
            </div>
        </div>

        <div class="dalheee">
            <div class="card">

                <div class="topo">
                    <h1 class="titulo"><strong>Lista de Produtos</strong></h1>
                </div>

                <?php if (temPermissao('pesquisar')) { ?>
                    <form method="post">
                        <div class="linha">
                            <div class="campo campoBusca">
                                <input
                                    type="text"
                                    name="pesquisaCampo"
                                    class="input"
                                    placeholder="Pesquisar produto"
                                    value="<?= htmlspecialchars($pesquisa, ENT_QUOTES, 'UTF-8') ?>">
                            </div>

                            <div class="campo">
                                <button type="submit" name="pesquisarBt" class="botao">
                                    Pesquisar
                                </button>
                            </div>
                        </div>
                    </form>
                <?php } ?>

                <div class="tabela">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome do Produto</th>
                                <th>Stock</th>
                                <th>unidade Medida</th>
                                <th>Data Confeccao</th>
                                <th>Data Validade</th>
                                <th>Preco unitario</th>
                                <th>Fornecedor</th>
                                <th>Acao</th>
                            </tr>
                        </thead>

                        <?php if (count($produtos) == 0) { ?>
                            <tr>
                                <td colspan="9">SEM NENHUM PRODUTO REGISTADO</td>
                            </tr>
                        <?php } else { ?>

                            <?php foreach ($produtos as $produto) { ?>
                                <tr>
                                    <td><?= $produto->getIdproduto() ?></td>
                                    <td><?= $produto->getProduto() ?></td>
                                    <td><?= $produto->getStock() ?></td>
                                    <td><?= $produto->getUnidadeMedida() ?></td>
                                    <td><?= $produto->getDataConfecao() ?></td>
                                    <td><?= $produto->getDataValidade() ?></td>
                                    <td><?= $produto->getPrecoUnitario() ?></td>
                                    <td><?= $produto->getIdFornecedor() ?></td>

                                    <td>
                                        <div class="acoes">
                                            <?php
                                            if (temPermissao('actualizar') || temPermissao('remover')) {
                                            ?>

                                                <?php if (temPermissao('actualizar')) { ?>
                                                    <a href="#editar-<?= $produto->getIdproduto() ?>" class="botaoAtualizar">
                                                        Editar
                                                    </a>
                                                <?php } ?>

                                                <?php if (temPermissao('remover')) { ?>
                                                    <a href="#remover-<?= $produto->getIdproduto() ?>" class="botaoRemover">
                                                        Remover
                                                    </a>
                                                <?php } ?>

                                            <?php } ?>

                                            <form method="post">
                                                <input type="hidden" name="id" value="<?= $produto->getIdproduto() ?>">
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>

                        <?php } ?>

                    </table>

                    <?php foreach ($produtos as $produto) { ?>
                        <!-- MODAL EDITAR -->
                        <div class="modalFundo" id="editar-<?= $produto->getIdproduto() ?>">
                            <div class="modalCaixa">
                                <div class="modalTopo">
                                    <h2 class="titulo">Editar Produto</h2>
                                    <a href="#" class="modalFechar">x</a>
                                </div>

                                <form method="post" action="">
                                    <input type="hidden" name="id" value="<?= $produto->getIdproduto() ?>">

                                    <div class="campo">
                                        <div class="campoTipo">
                                            <label class="label"><strong>Nome do produto</strong></label>
                                        </div>
                                        <input type="text" name="produto" class="input" value="<?= $produto->getProduto() ?>">
                                    </div>

                                    <div class="linha">
                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Unidade de Medida</strong></label>
                                            </div>
                                            <input type="text" name="unidadeMedida" class="input" value="<?= $produto->getUnidadeMedida() ?>" required>
                                        </div>

                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Stock</strong></label>
                                            </div>
                                            <input type="number" step="any" name="stock" class="input" value="<?= $produto->getStock() ?>" required>
                                        </div>
                                    </div>

                                    <div class="linha">
                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Data Confeccao</strong></label>
                                            </div>
                                            <input type="date" name="dataConfeccao" class="input" value="<?= $produto->getDataConfecao() ?>">
                                        </div>

                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Data Validade</strong></label>
                                            </div>
                                            <input type="date" name="dataValidade" class="input" value="<?= $produto->getDataValidade() ?>">
                                        </div>

                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Preco unitario</strong></label>
                                            </div>
                                            <input type="text" name="precoUnitario" class="input" value="<?= $produto->getPrecoUnitario() ?>">
                                        </div>
                                    </div>

                                    <div class="campo">
                                        <div class="campoTipo">
                                            <label class="label"><strong>Fornecedor</strong></label>
                                        </div>

                                        <select name="idFornecedor" class="input">
                                            <option value="">Seleccione o fornecedor</option>

                                            <?php foreach ($fornecedores as $fornecedor) { ?>
                                                <option
                                                    value="<?= $fornecedor->getIdFornecedor() ?>"
                                                    <?= $fornecedor->getNomeFornecedor() == $produto->getIdFornecedor() ? 'selected' : '' ?>>
                                                    <?= $fornecedor->getNomeFornecedor() ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="acao">
                                        <button type="submit" name="guardar" class="botao">Guardar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- FIM DA MODAL EDITAR -->
                    <?php } ?>

                    <?php foreach ($produtos as $produto) { ?>
                        <!-- MODAL REMOVER -->
                        <div class="modalFundo" id="remover-<?= $produto->getIdproduto() ?>">
                            <div class="modalCaixa">
                                <div class="modalTopo">
                                    <h2 class="titulo">Remover Produto</h2>
                                    <a href="#" class="modalFechar">x</a>
                                </div>

                                <form method="post" action="">
                                    <input type="hidden" name="id" value="<?= $produto->getIdproduto() ?>">

                                    <div class="campo">
                                        <div class="campoTipo">
                                            <label class="label"><strong>Nome do produto</strong></label>
                                        </div>
                                        <input type="text" name="produto" class="input" value="<?= $produto->getProduto() ?>">
                                    </div>

                                    <div class="linha">
                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Unidade de Medida</strong></label>
                                            </div>
                                            <input type="text" name="unidadeMedida" class="input" value="<?= $produto->getUnidadeMedida() ?>" required>
                                        </div>

                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Stock</strong></label>
                                            </div>
                                            <input type="number" step="any" name="stock" class="input" value="<?= $produto->getStock() ?>" required>
                                        </div>
                                    </div>

                                    <div class="linha">
                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Data Confeccao</strong></label>
                                            </div>
                                            <input type="date" name="dataConfeccao" class="input" value="<?= $produto->getDataConfecao() ?>">
                                        </div>

                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Data Validade</strong></label>
                                            </div>
                                            <input type="date" name="dataValidade" class="input" value="<?= $produto->getDataValidade() ?>">
                                        </div>

                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Preco unitario</strong></label>
                                            </div>
                                            <input type="text" name="precoUnitario" class="input" value="<?= $produto->getPrecoUnitario() ?>">
                                        </div>
                                    </div>

                                    <div class="campo">
                                        <div class="campoTipo">
                                            <label class="label"><strong>Fornecedor</strong></label>
                                        </div>

                                        <select name="idFornecedor" class="input">
                                            <option value="">Seleccione o fornecedor</option>

                                            <?php foreach ($fornecedores as $fornecedor) { ?>
                                                <option
                                                    value="<?= $fornecedor->getIdFornecedor() ?>"
                                                    <?= $fornecedor->getNomeFornecedor() == $produto->getIdFornecedor() ? 'selected' : '' ?>>
                                                    <?= $fornecedor->getNomeFornecedor() ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="acao">
                                        <button type="submit" name="remover" class="botao">Remover</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- FIM DA MODAL REMOVER -->
                    <?php } ?>

                </div>

            </div>
        </div>
    </div>

</body>

</html>