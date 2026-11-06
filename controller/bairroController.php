<?php

include_once(__DIR__ . "/../model/bairroModel.php");
include_once(__DIR__ . "/controllerLog.php");
$conexao = mysqli_connect("localhost", "root", "", "gestaocompra");

function listarBairro()
{
    global $conexao;
    $bairros = array();
    $sql = "select b.idBairro, b.nomeBairro, l.idLocalidade, l.nomeLocalidade, po.idPostoAdministrativo, po.nomePostoAdministrativo, d.idDistrito, d.nomeDistrito,
    p.idProvincia, p.nomeProvincia, pa.idPais, pa.nomePais, c.idContinente, c.nomeContinente from bairro b inner join localidade l on b.idLocalidade = l.idLocalidade 
    inner join posto_administrativo po on l.idPostoAdministrativo = po.idPostoAdministrativo inner join distrito d on po.idDistrito = d.idDistrito
    inner join provincia p on d.idProvincia = p.idProvincia inner join pais pa on p.idPais = pa.idPais inner join continente c on pa.idContinente = c.idContinente";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $bairro = new Bairro( $rs['idBairro'], $rs['nomeBairro'], $rs['idLocalidade'], $rs['nomeLocalidade'], $rs['idPostoAdministrativo'],
            $rs['nomePostoAdministrativo'], $rs['idDistrito'], $rs['nomeDistrito'],$rs['idProvincia'], $rs['nomeProvincia'], $rs['idPais'], $rs['nomePais'],
            $rs['idContinente'], $rs['nomeContinente']);
            array_push($bairros, $bairro);
        }
    }
    return $bairros;
}

function criacaoBairro($bairro)
{
    global $conexao;
    $nome = mysqli_real_escape_string($conexao, $bairro->getNomeBairro());
    $idLocalidade = (int) $bairro->getIdLocalidade();

    $sql = "select * from bairro where nomeBairro='{$nome}' and idLocalidade={$idLocalidade}";
    $result = mysqli_query($conexao, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        echo "O bairro ja existe";
    } else {
        $sql = "insert into bairro values (null,'{$nome}',{$idLocalidade})";
        $result = mysqli_query($conexao, $sql);
        if ($result) {
            echo "Bairro inserido";
            registarAccao("Cadastrou bairro");
        } else {
            echo "Ocorreu um erro ao gravar os dados";
        }
    }
}

function remocaoBairro($idBairro)
{
    global $conexao;
    $idBairro = (int) $idBairro;
    $sql = "delete from bairro where idBairro=" . $idBairro;
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        registarAccao("Removeu bairro");
    }
    return (bool) $result;
}

function edicaoBairro($bairro)
{
    global $conexao;

    $nome = mysqli_real_escape_string($conexao, $bairro->getNomeBairro());
    $idLocalidade = (int) $bairro->getIdLocalidade();
    $idBairro = (int) $bairro->getIdBairro();

    $sql = "update bairro set nomeBairro='{$nome}', idLocalidade={$idLocalidade} where idBairro={$idBairro}";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        registarAccao("Editou bairro");
    } else {
        echo "Ocorreu um erro ao atualizar os dados";
    }
}

function encontraBairro($id)
{
    global $conexao;
    $sql = "select b.idBairro, b.nomeBairro, l.idLocalidade, l.nomeLocalidade, po.idPostoAdministrativo, po.nomePostoAdministrativo, d.idDistrito, d.nomeDistrito,
    p.idProvincia, p.nomeProvincia, pa.idPais, pa.nomePais, c.idContinente, c.nomeContinente from bairro b inner join localidade l on b.idLocalidade = l.idLocalidade
    inner join posto_administrativo po on l.idPostoAdministrativo = po.idPostoAdministrativo inner join distrito d on po.idDistrito = d.idDistrito
    inner join provincia p on d.idProvincia = p.idProvincia inner join pais pa on p.idPais = pa.idPais inner join continente c on pa.idContinente = c.idContinente
    where b.idBairro=" . (int) $id;
    $result = mysqli_query($conexao, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $rs = mysqli_fetch_assoc($result);
        return new Bairro($rs['idBairro'], $rs['nomeBairro'], $rs['idLocalidade'], $rs['nomeLocalidade'], $rs['idPostoAdministrativo'],  $rs['nomePostoAdministrativo'], 
        $rs['idDistrito'], $rs['nomeDistrito'], $rs['idProvincia'], $rs['nomeProvincia'], $rs['idPais'], $rs['nomePais'],$rs['idContinente'], $rs['nomeContinente']);
    }

    return null;
}

function pesquisarBairro($pesquisa)
{
    global $conexao;
    $bairros = array();
    $pesquisa = mysqli_real_escape_string($conexao, $pesquisa);
    $sql = "select b.idBairro, b.nomeBairro, l.idLocalidade, l.nomeLocalidade,po.idPostoAdministrativo, po.nomePostoAdministrativo, d.idDistrito, d.nomeDistrito,
    p.idProvincia, p.nomeProvincia, pa.idPais, pa.nomePais, c.idContinente, c.nomeContinente from bairro b inner join localidade l on b.idLocalidade = l.idLocalidade
    inner join posto_administrativo po on l.idPostoAdministrativo = po.idPostoAdministrativo inner join distrito d on po.idDistrito = d.idDistrito
    inner join provincia p on d.idProvincia = p.idProvincia inner join pais pa on p.idPais = pa.idPais
    inner join continente c on pa.idContinente = c.idContinente where b.nomeBairro like '%{$pesquisa}%' or l.nomeLocalidade like '%{$pesquisa}%'
    or po.nomePostoAdministrativo like '%{$pesquisa}%' or d.nomeDistrito like '%{$pesquisa}%' or p.nomeProvincia like '%{$pesquisa}%' or pa.nomePais like
     '%{$pesquisa}%'
    or c.nomeContinente like '%{$pesquisa}%'";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $bairro = new Bairro($rs['idBairro'], $rs['nomeBairro'], $rs['idLocalidade'], $rs['nomeLocalidade'],$rs['idPostoAdministrativo'],
             $rs['nomePostoAdministrativo'], $rs['idDistrito'], $rs['nomeDistrito'],
            $rs['idProvincia'], $rs['nomeProvincia'], $rs['idPais'], $rs['nomePais'],$rs['idContinente'], $rs['nomeContinente']);
            array_push($bairros, $bairro);
        }
    }
    return $bairros;
}


function listarContinentesBairro()
{
    global $conexao;
    $continentes = array();
    $sql = "select * from continente order by nomeContinente";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            array_push($continentes, $rs);
        }
    }
    return $continentes;
}

function listarPaisesPorContinenteBairro($idContinente)
{
    global $conexao;
    $paises = array();
    $sql = "select * from pais where idContinente=" . (int) $idContinente . " order by nomePais";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            array_push($paises, $rs);
        }
    }
    return $paises;
}

function listarProvinciasPorPaisBairro($idPais)
{
    global $conexao;
    $provincias = array();
    $sql = "select * from provincia where idPais=" . (int) $idPais . " order by nomeProvincia";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            array_push($provincias, $rs);
        }
    }
    return $provincias;
}

function listarDistritosPorProvincia($idProvincia)
{
    global $conexao;
    $distritos = array();
    $sql = "select * from distrito where idProvincia=" . (int) $idProvincia . " order by nomeDistrito";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            array_push($distritos, $rs);
        }
    }
    return $distritos;
}

function listarPostosPorDistrito($idDistrito)
{
    global $conexao;
    $postos = array();
    $sql = "select * from posto_administrativo where idDistrito=" . (int) $idDistrito . " order by nomePostoAdministrativo";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            array_push($postos, $rs);
        }
    }
    return $postos;
}

function listarLocalidadesPorPosto($idPostoAdministrativo)
{
    global $conexao;
    $localidades = array();
    $sql = "select * from localidade where idPostoAdministrativo=" . (int) $idPostoAdministrativo . " order by nomeLocalidade";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            array_push($localidades, $rs);
        }
    }
    return $localidades;
}

function listarLocalidades()
{
    global $conexao;
    $localidades = array();
    $sql = "select * from localidade order by nomeLocalidade";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            array_push($localidades, $rs);
        }
    }
    return $localidades;
}