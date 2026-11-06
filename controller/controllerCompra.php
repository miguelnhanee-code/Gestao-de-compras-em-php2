<?php

include_once(__DIR__ . "/../model/Compra.php");
include_once(__DIR__ . "/controllerLog.php");
$conexao = mysqli_connect("localhost", "root", "", "gestaocompra");

function listarCompra()
{
    global $conexao;
    $compras = array();
    $sql = "select compra.*, produto.produto as nomeProduto,
     produto.unidadeMedida as unidadeMedida, produto.precoUnitario as precoProduto,cliente.nomeCliente as nomeCliente, 
     caixa.nomeCaixa as nomeCaixa from compra join produto on compra.idProduto = produto.idProduto
     left join cliente on compra.idCliente = cliente.idCliente left join caixa on cliente.idCaixa = caixa.idCaixa;";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $compra = new Compra($rs['idCompra'], $rs['quantidade'], $rs['dataDaCompra'], $rs['idProduto'], $rs['idCliente'], $rs['nomeProduto'], 
            $rs['precoProduto'], $rs['nomeCliente'], $rs['nomeCaixa'], $rs['unidadeMedida']);
            array_push($compras, $compra);
        }
    }
    return $compras;
}
function criacaoCompra($compra)
{
    global $conexao;
    $sql = "select stock from produto where idProduto={$compra->getIdproduto()}";
    $result = mysqli_query($conexao, $sql);
    $rs = mysqli_fetch_assoc($result);
    if ($rs['stock'] < $compra->getQuantidade()) {
        return "Nao ha Stock suficiente para realizar a compra";
    } else {
        $sql = "insert into compra values (null, {$compra->getIdproduto()}, {$compra->getIdcliente()},
        {$compra->getQuantidade()}, '{$compra->getDataCompra()}')";
        $result = mysqli_query($conexao, $sql);
        if ($result) {
            $sql = "update produto set stock=stock-{$compra->getQuantidade()} where idProduto={$compra->getIdproduto()}";
            mysqli_query($conexao, $sql);
            registarAccao("Cadastrou compra");
            return "Compra cadastrada com sucesso!";
        } else {
            return "Ocorreu um erro ao gravar os dados";
        }
    }
}
function remocaoCompra($idCompra)
{
    global $conexao;
    $sql = "delete from compra where idCompra=" . $idCompra;
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        registarAccao("Removeu compra");
    }
}

function edicaoCompra($compra)
{
    global $conexao;

    $sql = "update compra set idProduto={$compra->getIdproduto()}, idCliente={$compra->getIdcliente()},
    quantidade={$compra->getQuantidade()}, dataDaCompra='{$compra->getDataCompra()}' where idCompra={$compra->getIdcompra()}";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        registarAccao("Editou compra");
    }
    if ($result) {
        //  header('location:../view/ListarCompra.php');
    } else
        return "Ocorreu um erro ao atualizar os dados";
}

function encontraCompra($id)
{
    global $conexao;
    $sql = "select * from compra where idCompra=" . $id;
    $result = mysqli_query($conexao, $sql);
    $rs = mysqli_fetch_assoc($result);
    if (!$rs) {
        return null;
    }
    $compra = new Compra($id, $rs['quantidade'], $rs['dataDaCompra'], $rs['idProduto'], $rs['idCliente'], $rs['nomeProduto'] ?? null,
     $rs['precoProduto'] ?? null, $rs['nomeCliente'] ?? null, $rs['nomeCaixa'] ?? null, $rs['unidadeMedida'] ?? null);
    return $compra;
}

function pesquisarCompra($pesquisa)
{
    global $conexao;
    $compras = array();
    $sql = "select compra.*, produto.produto as nomeProduto, produto.unidadeMedida as unidadeMedida,
         produto.precoUnitario as precoProduto,cliente.nomeCliente as nomeCliente, caixa.nomeCaixa as nomeCaixa from compra
    join produto on compra.idProduto = produto.idProduto
    left join cliente on compra.idCliente = cliente.idCliente
    left join caixa on cliente.idCaixa = caixa.idCaixa
    where compra.idCompra like '%{$pesquisa}%'
    or produto.produto like '%{$pesquisa}%'
    or cliente.nomeCliente like '%{$pesquisa}%'
    or caixa.nomeCaixa like '%{$pesquisa}%'
    or produto.precoUnitario like '%{$pesquisa}%'
    or compra.quantidade like '%{$pesquisa}%'
    or produto.unidadeMedida like '%{$pesquisa}%'
    or compra.dataDaCompra like '%{$pesquisa}%'
    or (compra.quantidade * produto.precoUnitario) like '%{$pesquisa}%'";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $compra = new Compra($rs['idCompra'], $rs['quantidade'], $rs['dataDaCompra'], $rs['idProduto'], $rs['idCliente'],
             $rs['nomeProduto'], $rs['precoProduto'],
             $rs['nomeCliente'], $rs['nomeCaixa'], $rs['unidadeMedida']);
            array_push($compras, $compra);
        }
    }
    return $compras;
}
