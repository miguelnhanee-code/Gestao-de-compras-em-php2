<?php

include_once(__DIR__ . "/../model/localidadeModel.php");
include_once(__DIR__ . "/controllerLog.php");
$conexao = mysqli_connect("localhost", "root", "", "gestaocompra");

function listarLocalidade()
{
    global $conexao;
    $localidades = array();
    $sql = "SELECT l.idLocalidade,
                   l.nomeLocalidade,
                   l.idPostoAdministrativo,
                   pa.nomePostoAdministrativo
            FROM localidade l
            INNER JOIN posto_administrativo pa
                ON l.idPostoAdministrativo = pa.idPostoAdministrativo";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $localidade = new Localidade($rs['idLocalidade'], $rs['nomeLocalidade'], $rs['idPostoAdministrativo'], $rs['nomePostoAdministrativo']);
            array_push($localidades, $localidade);
        }
    }
    return $localidades;
}

function criacaoLocalidade($localidade)
{
    global $conexao;
    $sql = "select * from localidade where nomeLocalidade='{$localidade->getNomeLocalidade()}'
     and idPostoAdministrativo={$localidade->getIdPostoAdministrativo()}";
    $result = mysqli_query($conexao, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo "A localidade já existe neste posto administrativo";
    } else {
        $sql = "insert into localidade values (null,'{$localidade->getNomeLocalidade()}',{$localidade->getIdPostoAdministrativo()})";
        $result = mysqli_query($conexao, $sql);
        if ($result) {
            registarAccao("Cadastrou localidade");
            echo "Localidade inserida";
        } else
            echo "Ocorreu um erro ao gravar os dados";
    }
}

function remocaoLocalidade($idLocalidade)
{
    global $conexao;
    $sql = "delete from localidade where idLocalidade=" . $idLocalidade;
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        registarAccao("Removeu localidade");
    }
}

function edicaoLocalidade($localidade)
{
    global $conexao;

    $sql = "update localidade set nomeLocalidade='{$localidade->getNomeLocalidade()}', 
    idPostoAdministrativo={$localidade->getIdPostoAdministrativo()} where idLocalidade={$localidade->getIdLocalidade()}";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        registarAccao("Editou localidade");
    } else
        echo "Ocorreu um erro ao atualizar os dados";
}

function encontraLocalidade($id)
{
    global $conexao;
    $sql = "select * from localidade where idLocalidade=" . $id;
    $result = mysqli_query($conexao, $sql);
    $rs = mysqli_fetch_assoc($result);
    $localidade = new Localidade($rs['idLocalidade'], $rs['nomeLocalidade'], $rs['idPostoAdministrativo'], null);
    return $localidade;
}

function pesquisarLocalidade($pesquisa)
{
    global $conexao;
    $localidades = array();
    $sql = "SELECT l.idLocalidade,
                   l.nomeLocalidade,
                   l.idPostoAdministrativo,
                   pa.nomePostoAdministrativo
            FROM localidade l
            INNER JOIN posto_administrativo pa
                ON l.idPostoAdministrativo = pa.idPostoAdministrativo
            WHERE l.nomeLocalidade LIKE '%{$pesquisa}%'
               OR pa.nomePostoAdministrativo LIKE '%{$pesquisa}%'";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $localidade = new Localidade($rs['idLocalidade'], $rs['nomeLocalidade'], $rs['idPostoAdministrativo'], $rs['nomePostoAdministrativo']);
            array_push($localidades, $localidade);
        }
    }
    return $localidades;
}

// }