<?php

include_once(__DIR__ . "/../model/clienteModel.php");
include_once(__DIR__ . "/controllerLog.php");

$conexao = mysqli_connect("localhost", "root", "", "gestaocompra");

function listarCliente()
{
    global $conexao;
    $clientes = array();
    $sql = "SELECT c.idCliente, c.nomeCliente, c.apelidoCliente, p.idCaixa, p.nomeCaixa
        FROM cliente c inner JOIN caixa p ON c.idCaixa = p.idCaixa";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $cliente = new Cliente($rs['idCliente'], $rs['nomeCliente'], $rs['apelidoCliente'], $rs['idCaixa'], $rs['nomeCaixa']);
            array_push($clientes, $cliente);
        }
    }
    return $clientes;
}


function criacaoCliente($cliente)
{
    global $conexao;
    $sql = "SELECT * FROM cliente
            WHERE nomeCliente = '{$cliente->getNomeCliente()}'
            AND apelidoCliente = '{$cliente->getApelidoCliente()}'";
    $result = mysqli_query($conexao, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        echo "O cliente ja existe";
    } else {
        $sql = "insert into cliente (nomeCliente, apelidoCliente, idCaixa)
                values ('{$cliente->getNomeCliente()}', '{$cliente->getApelidoCliente()}', {$cliente->getIdCaixa()})";
        $result = mysqli_query($conexao, $sql);
        if ($result) {
            echo "Cliente inserido";
            registarAccao("Cliente Cadastrado");
        } else {
            echo "Ocorreu um erro ao gravar os dados: " . mysqli_error($conexao);
        }
    }
}


function remocaoCliente($id)
{
    global $conexao;
    $sql = "delete from cliente where idCliente=" . $id;
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        registarAccao("Cliente Removido");
    }
}


function edicaoCliente($cliente)
{
    global $conexao;
    $sql = "UPDATE cliente SET nomeCliente='{$cliente->getNomeCliente()}', apelidoCliente='{$cliente->getApelidoCliente()}',
            idCaixa={$cliente->getIdCaixa()} WHERE idCliente={$cliente->getIdCliente()}";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        registarAccao("Cliente Editado");
    } else {
        echo "Ocorreu um erro ao atualizar os dados: " . mysqli_error($conexao);
    }
}

function encontraCliente($id)
{
    global $conexao;
    $sql = "select * from cliente where idCliente=" . $id;
    $result = mysqli_query($conexao, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $rs = mysqli_fetch_assoc($result);
        return new Cliente($rs['idCliente'], $rs['nomeCliente'], $rs['apelidoCliente'], $rs['idCaixa']);
    }
    return null;
}

function pesquisarCliente($pesquisa)
{
    global $conexao;
    $clientes = array();
    $sql = "SELECT c.idCliente, c.nomeCliente, c.apelidoCliente, p.idCaixa, p.nomeCaixa
        FROM cliente c inner JOIN caixa p ON c.idCaixa = p.idCaixa
        WHERE c.idCliente like '%{$pesquisa}%' or c.nomeCliente like '%{$pesquisa}%' or c.apelidoCliente like '%{$pesquisa}%' or p.nomeCaixa like '%{$pesquisa}%'";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $cliente = new Cliente($rs['idCliente'], $rs['nomeCliente'], $rs['apelidoCliente'], $rs['idCaixa'], $rs['nomeCaixa']);
            array_push($clientes, $cliente);
        }
    }
    return $clientes;
}

function listarCaixas()
{
    global $conexao;
    $caixas = array();
    $sql = "SELECT idCaixa, nomeCaixa FROM caixa";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $caixas[] = $rs;
        }
    }
    return $caixas;
}
