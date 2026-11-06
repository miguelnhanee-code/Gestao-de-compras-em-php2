<?php
include_once __DIR__ . '/../controller/controllerFornecedor.php';
include_once __DIR__ . '/../controller/bairroController.php';

include_once __DIR__ . '/../controller/controllerLogin.php';
if (!$sessao->esta_logado()) {
    header("Location: Login.php");
    exit;
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
$perfil = $_SESSION['perfil'];
exigirPermissao('listar');

$fornecedores = listarFornecedor();
$continentes = listarContinentesFornecedor();
$paises = listarPaisesFornecedor();
$provincias = listarProvinciasFornecedor();
$distritos = listarDistritosFornecedor();
$postos = listarPostosFornecedor();
$localidades = listarLocalidades();
$bairros = listarBairro();

if (isset($_POST['remover']) && temPermissao('remover')) {
    global $conexao;
    remocaoFornecedor($_POST['id']);
    header('Location: listarFornecedores.php');
}

if (
    isset($_POST['guardar']) && temPermissao('actualizar') && !empty($_POST['id']) &&
    !empty($_POST['nome']) && !empty($_POST['contacto']) && !empty($_POST['email'])
    && !empty($_POST['idProvincia']) && !empty($_POST['idDistrito']) && !empty($_POST['idPostoAdministrativo'])
    && !empty($_POST['idBairro']) && !empty($_POST['quarteirao']) && !empty($_POST['nrEmpresa'])
) {
    $fornecedor = encontraFornecedor($_POST['id']);

    if ($fornecedor) {
        $fornecedor->setNomeFornecedor($_POST['nome']);
        $fornecedor->setTelefone($_POST['contacto']);
        $fornecedor->setEmail($_POST['email']);
        $fornecedor->setIdProvincia($_POST['idProvincia']);
        $fornecedor->setIdDistrito($_POST['idDistrito']);
        $fornecedor->setIdPostoAdministrativo($_POST['idPostoAdministrativo']);
        $fornecedor->setIdBairro($_POST['idBairro']);
        $fornecedor->setQuarteirao($_POST['quarteirao']);
        $fornecedor->setNrEmpresa($_POST['nrEmpresa']);
        edicaoFornecedor($fornecedor);
    }
    header('Location: listarFornecedores.php#');
}

$fornecedores = listarFornecedor();
$pesquisa = $_POST['pesquisaCampo'] ?? '';
if (isset($_POST['pesquisarBt']) && temPermissao('pesquisar')) {
    if (!empty($pesquisa)) {
        $fornecedores = pesquisarFornecedor($pesquisa);
    }
}

?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>LISTAR FORNECEDOR</title>
    <link rel='stylesheet' href='../css/ListarCompra.css'>
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
                <a class="on" href="<?= $paginaFornecedor ?>"><span class="icone"></span>FORNECEDOR</a>
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
                <?php if (temPermissao('criar')) { ?><a href="cadastrarFornecedor.php" class="aba">CADASTRO DE FORNECEDOR</a><?php } ?>
                <div class="aba abaAtiva">LISTA DE FORNECEDORES</div>
            </div>
        </div>

        <div class="dalheee">
            <div class="card">

                <div class="topo">
                    <h1 class="titulo"><strong>Lista de Fornecedores</strong></h1>
                </div>

                <?php if (temPermissao('pesquisar')) { ?>
                    <form method="post">
                        <div class="linha">
                            <div class="campo campoBusca">
                                <input type="text" name="pesquisaCampo" class="input" placeholder="Pesquisar " value="<?= $pesquisa ?>">
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
                                <th>Nome do Fornecedor</th>
                                <th>Contacto</th>
                                <th>Email</th>
                                <th>Continente</th>
                                <th>País</th>
                                <th>Provincia</th>
                                <th>Distrito</th>
                                <th>Posto Administrativo</th>
                                <!-- endereco  -->
                                <th>Endereco</th>
                                <!-- <th>Quarteirão</th>
                                <th>Nº da Empresa</th> -->
                                <th>Acao</th>
                            </tr>
                        </thead>

                        <?php if (count($fornecedores) == 0) { ?>
                            <tr>
                                <td>SEM NENHUM FORNECEDOR REGISTADO</td>
                            </tr>
                        <?php } else { ?>
                            <?php foreach ($fornecedores as $fornecedor) { ?>

                                <tr>
                                    <td><?= $fornecedor->getIdFornecedor() ?></td>
                                    <td><?= $fornecedor->getNomeFornecedor() ?></td>
                                    <td><?= $fornecedor->getTelefone() ?></td>
                                    <td><?= $fornecedor->getEmail() ?></td>
                                    <td><?= $fornecedor->getNomeContinente() ?></td>
                                    <td><?= $fornecedor->getNomePais() ?></td>
                                    <td><?= $fornecedor->getNomeProvincia() ?></td>
                                    <td><?= $fornecedor->getNomeDistrito() ?></td>
                                    <td><?= $fornecedor->getNomePostoAdministrativo() ?></td>
                                    <td><?= "Bairro:" . $fornecedor->getNomeBairro() .  "  Q:" . $fornecedor->getQuarteirao() . " N:" . $fornecedor->getNrEmpresa() ?></td>
                                    <!-- <td><?= $fornecedor->getQuarteirao() ?></td>
                                    <td><?= $fornecedor->getNrEmpresa() ?></td> -->

                                    <td>
                                        <div class="acoes">


                                            <form method="post">

                                                <?php
                                                if (temPermissao('actualizar') || temPermissao('remover')) { ?>
                                                    <input type="hidden" name="id" value="<?= $fornecedor->getIdFornecedor() ?>">

                                                    <?php
                                                    if (temPermissao('actualizar')) {
                                                    ?>
                                                        <a href="#editar-<?= $fornecedor->getIdFornecedor() ?>" class="botaoAtualizar">
                                                            Editar
                                                        </a>
                                                    <?php } ?>

                                                    <?php
                                                    if (temPermissao('remover')) {
                                                    ?>
                                                        <a href="#remover-<?= $fornecedor->getIdFornecedor() ?>" class="botaoRemover">
                                                            Remover
                                                        </a>
                                                    <?php } ?>

                                                <?php } ?>

                                            </form>
                                        </div>
                                    </td>
                                </tr>
                        <?php }
                        } ?>

                    </table>

                    <?php foreach ($fornecedores as $fornecedor) {
                        $idFornecedor = $fornecedor->getIdFornecedor();

                        // Valores actuais (deduzidos da província do fornecedor)
                        $provinciaSelecionada = $fornecedor->getIdProvincia();
                        $distritoSelecionado  = $fornecedor->getIdDistrito();
                        $postoSelecionado     = $fornecedor->getIdPostoAdministrativo();
                        $paisSelecionado = '';
                        $continenteSelecionado = '';
                        foreach ($provincias as $provincia) {
                            if ((string)$provincia['idProvincia'] === (string)$provinciaSelecionada) {
                                $paisSelecionado = $provincia['idPais'];
                            }
                        }
                        foreach ($paises as $pais) {
                            if ((string)$pais['idPais'] === (string)$paisSelecionado) {
                                $continenteSelecionado = $pais['idContinente'];
                            }
                        }

                        // Localidade actual (deduzida do bairro do fornecedor)
                        $bairroSelecionado = $fornecedor->getIdBairro();
                        $localidadeSelecionada = '';
                        foreach ($bairros as $bairro) {
                            if ((string)$bairro->getIdBairro() === (string)$bairroSelecionado) {
                                $localidadeSelecionada = $bairro->getIdLocalidade();
                            }
                        }

                        // Se o utilizador carregou num botão "Filtrar" deste modal, usa o que foi seleccionado
                        if (isset($_POST['filtrarEditar'], $_POST['id']) && $_POST['id'] == $idFornecedor) {
                            $continenteSelecionado = $_POST['idContinente'] ?? '';
                            $paisSelecionado = $_POST['idPais'] ?? '';
                            $provinciaSelecionada = $_POST['idProvincia'] ?? '';
                            $distritoSelecionado = $_POST['idDistrito'] ?? '';
                            $postoSelecionado = $_POST['idPostoAdministrativo'] ?? '';
                            $localidadeSelecionada = $_POST['idLocalidade'] ?? '';
                            $bairroSelecionado = $_POST['idBairro'] ?? '';

                            $nomeEditar = $_POST['nome'] ?? '';
                            $contactoEditar = $_POST['contacto'] ?? '';
                            $emailEditar = $_POST['email'] ?? '';
                            $quarteiraoEditar = $_POST['quarteirao'] ?? '';
                            $nrEmpresaEditar = $_POST['nrEmpresa'] ?? '';
                        } else {
                            $nomeEditar = $fornecedor->getNomeFornecedor();
                            $contactoEditar = $fornecedor->getTelefone();
                            $emailEditar = $fornecedor->getEmail();
                            $quarteiraoEditar = $fornecedor->getQuarteirao();
                            $nrEmpresaEditar = $fornecedor->getNrEmpresa();
                        }
                    ?>
                        <!-- MODAL-->
                        <div class="modalFundo" id="editar-<?= $idFornecedor ?>">
                            <!-- <a href="#" class="modalOverlay" aria-label="Fechar"></a> -->
                            <div class="modalCaixa">
                                <div class="modalTopo">
                                    <h2 class="titulo">Editar Fornecedor</h2>
                                    <a href="#" class="modalFechar">x</a>
                                </div>

                                <form method="post" action="listarFornecedores.php#editar-<?= $idFornecedor ?>">
                                    <input type="hidden" name="id" value="<?= $idFornecedor ?>">

                                    <div class="campo">
                                        <div class="campoTipo">
                                            <label class="label"><strong>Nome do fornecedor</strong></label>
                                        </div>
                                        <input type="text" name="nome" class="input" value="<?= $nomeEditar ?>">
                                    </div>

                                    <div class="linha">
                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Contacto</strong></label>
                                            </div>
                                            <input type="number" name="contacto" class="input" value="<?= $contactoEditar ?>">
                                        </div>

                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Email</strong></label>
                                            </div>
                                            <input type="email" name="email" class="input" value="<?= $emailEditar ?>">
                                        </div>
                                    </div>

                                    <!-- Continente > País > Província -->
                                    <div class="linha">
                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Continente</strong></label>
                                            </div>
                                            <select name="idContinente" class="input">
                                                <option value="">Selecione</option>
                                                <?php foreach ($continentes as $continente) { ?>
                                                    <option value="<?= $continente['idContinente'] ?>" <?= (string)$continente['idContinente'] === (string)$continenteSelecionado ? 'selected' : '' ?>><?= $continente['nomeContinente'] ?></option>
                                                <?php } ?>
                                            </select>
                                            <button type="submit" name="filtrarEditar" class="botaoAtualizar" formnovalidate>Filtrar países</button>
                                        </div>

                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>País</strong></label>
                                            </div>
                                            <select name="idPais" class="input">
                                                <option value="">Selecione</option>
                                                <?php foreach ($paises as $pais) { ?>
                                                    <?php if ((string)$pais['idContinente'] === (string)$continenteSelecionado) { ?>
                                                        <option value="<?= $pais['idPais'] ?>" <?= (string)$pais['idPais'] === (string)$paisSelecionado ? 'selected' : '' ?>><?= $pais['nomePais'] ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                            <button type="submit" name="filtrarEditar" class="botaoAtualizar" formnovalidate>Filtrar províncias</button>
                                        </div>

                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Província</strong></label>
                                            </div>
                                            <select name="idProvincia" class="input">
                                                <option value="">Selecione</option>
                                                <?php foreach ($provincias as $provincia) { ?>
                                                    <?php if ((string)$provincia['idPais'] === (string)$paisSelecionado) { ?>
                                                        <option value="<?= $provincia['idProvincia'] ?>" <?= (string)$provincia['idProvincia'] === (string)$provinciaSelecionada ? 'selected' : '' ?>><?= $provincia['nomeProvincia'] ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                            <button type="submit" name="filtrarEditar" class="botaoAtualizar" formnovalidate>Filtrar distritos</button>
                                        </div>
                                    </div>

                                    <!-- Distrito > Posto Administrativo > Localidade -->
                                    <div class="linha">
                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Distrito</strong></label>
                                            </div>
                                            <select name="idDistrito" class="input">
                                                <option value="">Selecione</option>
                                                <?php foreach ($distritos as $distrito) { ?>
                                                    <?php if ((string)$distrito['idProvincia'] === (string)$provinciaSelecionada) { ?>
                                                        <option value="<?= $distrito['idDistrito'] ?>" <?= (string)$distrito['idDistrito'] === (string)$distritoSelecionado ? 'selected' : '' ?>><?= $distrito['nomeDistrito'] ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                            <button type="submit" name="filtrarEditar" class="botaoAtualizar" formnovalidate>Filtrar postos</button>
                                        </div>

                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Posto Administrativo</strong></label>
                                            </div>
                                            <select name="idPostoAdministrativo" class="input">
                                                <option value="">Selecione</option>
                                                <?php foreach ($postos as $posto) { ?>
                                                    <?php if ((string)$posto['idDistrito'] === (string)$distritoSelecionado) { ?>
                                                        <option value="<?= $posto['idPostoAdministrativo'] ?>" <?= (string)$posto['idPostoAdministrativo'] === (string)$postoSelecionado ? 'selected' : '' ?>><?= $posto['nomePostoAdministrativo'] ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                            <button type="submit" name="filtrarEditar" class="botaoAtualizar" formnovalidate>Filtrar localidades</button>
                                        </div>

                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Localidade</strong></label>
                                            </div>
                                            <select name="idLocalidade" class="input">
                                                <option value="">Selecione</option>
                                                <?php foreach ($localidades as $localidade) { ?>
                                                    <?php if ((string)$localidade['idPostoAdministrativo'] === (string)$postoSelecionado) { ?>
                                                        <option value="<?= $localidade['idLocalidade'] ?>" <?= (string)$localidade['idLocalidade'] === (string)$localidadeSelecionada ? 'selected' : '' ?>><?= $localidade['nomeLocalidade'] ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                            <button type="submit" name="filtrarEditar" class="botaoAtualizar" formnovalidate>Filtrar bairros</button>
                                        </div>
                                    </div>

                                    <!-- Bairro > Quarteirão > Nº da Empresa -->
                                    <div class="linha">
                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Bairro</strong></label>
                                            </div>
                                            <select name="idBairro" class="input">
                                                <option value="">Selecione</option>
                                                <?php foreach ($bairros as $bairro) { ?>
                                                    <?php if ((string)$bairro->getIdLocalidade() === (string)$localidadeSelecionada) { ?>
                                                        <option value="<?= $bairro->getIdBairro() ?>" <?= (string)$bairro->getIdBairro() === (string)$bairroSelecionado ? 'selected' : '' ?>><?= $bairro->getNomeBairro() ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Quarteirão</strong></label>
                                            </div>
                                            <input type="text" name="quarteirao" class="input" value="<?= $quarteiraoEditar ?>">
                                        </div>

                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Nº da Empresa</strong></label>
                                            </div>
                                            <input type="text" name="nrEmpresa" class="input" value="<?= $nrEmpresaEditar ?>">
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

                    <?php foreach ($fornecedores as $fornecedor) { ?>
                        <!-- MODAL-->
                        <div class="modalFundo" id="remover-<?= $fornecedor->getIdFornecedor() ?>">
                            <!-- <a href="#" class="modalOverlay" aria-label="Fechar"></a> -->
                            <div class="modalCaixa">
                                <div class="modalTopo">
                                    <h2 class="titulo">Remover Fornecedor</h2>
                                    <a href="#" class="modalFechar">x</a>
                                </div>

                                <form method="post" action="">
                                    <input type="hidden" name="id" value="<?= $fornecedor->getIdFornecedor() ?>">

                                    <div class="campo">
                                        <div class="campoTipo">
                                            <label class="label"><strong>Nome do fornecedor</strong></label>
                                        </div>
                                        <input type="text" name="nome" class="input" value="<?= $fornecedor->getNomeFornecedor() ?>">
                                    </div>

                                    <div class="linha">
                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Contacto</strong></label>
                                            </div>
                                            <input type="number" name="contacto" class="input" value="<?= $fornecedor->getTelefone() ?>">
                                        </div>

                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Email</strong></label>
                                            </div>
                                            <input type="email" name="email" class="input" value="<?= $fornecedor->getEmail() ?>">
                                        </div>
                                    </div>

                                    <div class="linha">
                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Provincia</strong></label>
                                            </div>
                                            <select name="idProvincia" class="input">
                                                <?php foreach ($provincias as $provincia) { ?>
                                                    <option value="<?= $provincia['idProvincia'] ?>"
                                                        <?= $provincia['idProvincia'] == $fornecedor->getIdProvincia() ? 'selected' : '' ?>>
                                                        <?= $provincia['nomeProvincia'] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Distrito</strong></label>
                                            </div>
                                            <select name="idDistrito" class="input">
                                                <?php foreach ($distritos as $distrito) { ?>
                                                    <option value="<?= $distrito['idDistrito'] ?>"
                                                        <?= $distrito['idDistrito'] == $fornecedor->getIdDistrito() ? 'selected' : '' ?>>
                                                        <?= $distrito['nomeDistrito'] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="linha">
                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Posto Administrativo</strong></label>
                                            </div>
                                            <select name="idPostoAdministrativo" class="input">
                                                <?php foreach ($postos as $posto) { ?>
                                                    <option value="<?= $posto['idPostoAdministrativo'] ?>"
                                                        <?= $posto['idPostoAdministrativo'] == $fornecedor->getIdPostoAdministrativo() ? 'selected' : '' ?>>
                                                        <?= $posto['nomePostoAdministrativo'] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Bairro</strong></label>
                                            </div>
                                            <select name="idBairro" class="input">
                                                <?php foreach ($bairros as $bairro) { ?>
                                                    <option value="<?= $bairro->getIdBairro() ?>"
                                                        <?= $bairro->getIdBairro() == $fornecedor->getIdBairro() ? 'selected' : '' ?>>
                                                        <?= $bairro->getNomeBairro() ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="linha">
                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Quarteirão</strong></label>
                                            </div>
                                            <input type="text" name="quarteirao" class="input" value="<?= $fornecedor->getQuarteirao() ?>">
                                        </div>

                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Nº da Empresa</strong></label>
                                            </div>
                                            <input type="text" name="nrEmpresa" class="input" value="<?= $fornecedor->getNrEmpresa() ?>">
                                        </div>
                                    </div>

                                    <div class="acao">
                                        <button type="submit" name="remover" class="botao">Remover</button>
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