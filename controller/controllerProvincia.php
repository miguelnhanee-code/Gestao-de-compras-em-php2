<?php

include_once(__DIR__ . "/../model/provinciaModel.php");
include_once(__DIR__ . "/controllerLog.php");
$conexao = mysqli_connect("localhost", "root", "", "gestaocompra");

function listarProvincia()
{
    global $conexao;
    $provincias = array();
    $sql = "SELECT p.idProvincia, p.nomeProvincia, c.idPais, c.nomePais
        FROM provincia p INNER JOIN pais c ON p.idPais = c.idPais";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $provincia = new Provincia($rs['idProvincia'], $rs['nomeProvincia'], $rs['idPais'], $rs['nomePais']);
            array_push($provincias, $provincia);
        }
    }
    return $provincias;
}

function criacaoProvincia($provincia)
{
    global $conexao;
    $sql = "select * from provincia where nomeProvincia='{$provincia->getNomeProvincia()}' and idPais={$provincia->getIdPais()}";
    $result = mysqli_query($conexao, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo "A provincia ja existe";
    } else {
        $sql = "insert into provincia values (null,'{$provincia->getNomeProvincia()}',{$provincia->getIdPais()})";
        $result = mysqli_query($conexao, $sql);
        if ($result) {
            registarAccao("Cadastrou provincia");
            echo "Provincia inserida";
        } else
            echo "Ocorreu um erro ao gravar os dados";
    }
}

function remocaoProvincia($idProvincia)
{
    global $conexao;
    $sql = "delete from provincia where idProvincia=" . $idProvincia;
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        registarAccao("Removeu provincia");
    }
}

function edicaoProvincia($provincia)
{
    global $conexao;

    $sql = "update provincia set nomeProvincia='{$provincia->getNomeProvincia()}', idPais={$provincia->getIdPais()} where idProvincia={$provincia->getIdProvincia()}";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        registarAccao("Editou provincia");
    } else
        echo "Ocorreu um erro ao atualizar os dados";
}

function encontraProvincia($id)
{
    global $conexao;
    $sql = "select * from provincia where idProvincia=" . $id;
    $result = mysqli_query($conexao, $sql);
    $rs = mysqli_fetch_assoc($result);
    $provincia = new Provincia($rs['idProvincia'], $rs['nomeProvincia'], $rs['idPais'], '');
    return $provincia;
}

function pesquisarProvincia($pesquisa)
{
    global $conexao;
    $provincias = array();
    $sql = "SELECT p.idProvincia, p.nomeProvincia, c.idPais, c.nomePais
        FROM provincia p INNER JOIN pais c ON p.idPais = c.idPais
        WHERE p.nomeProvincia LIKE '%{$pesquisa}%'
        OR c.nomePais LIKE '%{$pesquisa}%'";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $provincia = new Provincia($rs['idProvincia'], $rs['nomeProvincia'], $rs['idPais'], $rs['nomePais']);
            array_push($provincias, $provincia);
        }
    }
    return $provincias;
}

function listarPaises()
{
    global $conexao;
    $paises = array();
    $sql = "select idPais, nomePais from pais";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $paises[] = $rs;
        }
    }
    return $paises;
}
