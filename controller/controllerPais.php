<?php

include_once(__DIR__ . "/../model/paisModel.php");
include_once(__DIR__ . "/controllerLog.php");
$conexao = mysqli_connect("localhost", "root", "", "gestaocompra");

function listarPais()
{
    global $conexao;
    $paises = array();
    $sql = "SELECT p.idPais, p.nomePais, c.idContinente, c.nomeContinente
        FROM pais p INNER JOIN continente c ON p.idContinente = c.idContinente
        ORDER BY p.nomePais";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $pais = new Pais($rs['idPais'], $rs['nomePais'], $rs['idContinente'], $rs['nomeContinente']);
            array_push($paises, $pais);
        }
    }
    return $paises;
}

function criacaoPais($pais)
{
    global $conexao;
    $sql = "select * from pais where nomePais='{$pais->getNomePais()}'";
    $result = mysqli_query($conexao, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo "O pais ja existe";
    } else {
        $sql = "insert into pais values (null,'{$pais->getNomePais()}',{$pais->getIdContinente()})";
        $result = mysqli_query($conexao, $sql);
        if ($result) {
            registarAccao("Cadastrou pais");
            echo "Pais inserido";
        } else
            echo "Ocorreu um erro ao gravar os dados";
    }
}

function remocaoPais($idPais)
{
    global $conexao;
    $sql = "delete from pais where idPais=" . $idPais;
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        registarAccao("Removeu pais");
    }
}

function edicaoPais($pais)
{
    global $conexao;

    $sql = "update pais set nomePais='{$pais->getNomePais()}', idContinente={$pais->getIdContinente()} where idPais={$pais->getIdPais()}";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        registarAccao("Editou pais");
    } else
        echo "Ocorreu um erro ao atualizar os dados";
}

function encontraPais($id)
{
    global $conexao;
    $sql = "select * from pais where idPais=" . $id;
    $result = mysqli_query($conexao, $sql);
    $rs = mysqli_fetch_assoc($result);
    $pais = new Pais($rs['idPais'], $rs['nomePais'], $rs['idContinente'], '');
    return $pais;
}

function pesquisarPais($pesquisa)
{
    global $conexao;
    $paises = array();
    $sql = "SELECT p.idPais, p.nomePais, c.idContinente, c.nomeContinente
        FROM pais p INNER JOIN continente c ON p.idContinente = c.idContinente
        WHERE p.nomePais LIKE '%{$pesquisa}%'
        OR c.nomeContinente LIKE '%{$pesquisa}%'";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $pais = new Pais($rs['idPais'], $rs['nomePais'], $rs['idContinente'], $rs['nomeContinente']);
            array_push($paises, $pais);
        }
    }
    return $paises;
}

