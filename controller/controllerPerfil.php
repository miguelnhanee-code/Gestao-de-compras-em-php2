<?php

include_once(__DIR__ . "/../model/modelPerfil.php");
include_once(__DIR__ . "/controllerLog.php");
$conexao = mysqli_connect("localhost", "root", "", "gestaocompra");

function listarPerfil()
{
    global $conexao;
    $perfis = array();
    $sql = "select * from perfil";
    $result = mysqli_query($conexao, $sql);
 
    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $perfil = new Perfil($rs['idPerfil'], $rs['nomePerfil']);
            array_push($perfis, $perfil);
        }
    }
    return $perfis;
}
 
function criacaoPerfil($perfil)
{
    global $conexao;
    $sql = "select * from perfil where nomePerfil='{$perfil->getNomePerfil()}'";
    $result = mysqli_query($conexao, $sql);
    if (mysqli_num_rows($result) > 0) {
        return "O perfil ja existe";
    } else {
        $sql = "insert into perfil (nomePerfil) values ('{$perfil->getNomePerfil()}')";
        $result = mysqli_query($conexao, $sql);
        if ($result) {
            registarAccao("Cadastrou perfil");
            return "Perfil criado";
        } else
            return "Ocorreu um erro ao gravar os dados";
    }
}
 
function removerPerfil($idPerfil)
{
    global $conexao;
    $sql = "delete from perfil where idPerfil=" . $idPerfil;
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        registarAccao("Removeu perfil");
        return "Perfil removido";
    } else
        return "Ocorreu um erro ao remover o perfil";
}
 
function editarPerfil($perfil)
{
    global $conexao;
    $sql = "select * from perfil where nomePerfil='{$perfil->getNomePerfil()}' and idPerfil<>{$perfil->getIdPerfil()}";
    $result = mysqli_query($conexao, $sql);
    if (mysqli_num_rows($result) > 0) {
        return "Ja existe outro perfil com esse nome";
    } else {
        $antigo = encontraPerfilPorId($perfil->getIdPerfil());
        $sql = "update perfil set nomePerfil='{$perfil->getNomePerfil()}' where idPerfil={$perfil->getIdPerfil()}";
        $result = mysqli_query($conexao, $sql);
        if ($result) {
            mysqli_query($conexao, "update utilizadores set perfil='{$perfil->getNomePerfil()}' where perfil='{$antigo->getNomePerfil()}'");
            registarAccao("Editou perfil");
            return "Perfil actualizado";
        } else
            return "Ocorreu um erro ao editar o perfil";
    }
}
 
function encontraPerfil($nomePerfil)
{
    global $conexao;
    $sql = "select * from perfil where nomePerfil='" . $nomePerfil . "'";
    $result = mysqli_query($conexao, $sql);
    $rs = mysqli_fetch_assoc($result);
    if (!$rs) {
        return null;
    }
    return new Perfil($rs['idPerfil'], $rs['nomePerfil']);
}
 
function encontraPerfilPorId($idPerfil)
{
    global $conexao;
    $sql = "select * from perfil where idPerfil=" . $idPerfil;
    $result = mysqli_query($conexao, $sql);
    $rs = mysqli_fetch_assoc($result);
    if (!$rs) {
        return null;
    }
    return new Perfil($rs['idPerfil'], $rs['nomePerfil']);
}
 
function listarPermissoes()
{
    global $conexao;
    $permissoes = array();
    $sql = "select * from permissao order by field(chave,'listar','pesquisar','criar','remover','actualizar','gerir_log','gerir_utilizador') = 0,
     field(chave,'listar','pesquisar','criar','remover','actualizar','gerir_log','gerir_utilizador'), codigoPermissao";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            array_push($permissoes, $rs);
        }
    }
    return $permissoes;
}

function permissoesDoPerfil($idPerfil)
{
    global $conexao;
    $codigos = array();
    $sql = "select codigoPermissao from perfil_permissao where idPerfil=" . (int) $idPerfil;
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            array_push($codigos, $rs['codigoPermissao']);
        }
    }
    return $codigos;
}

function gravarPermissoesPerfil($idPerfil, $codigos)
{
    global $conexao;
    $idPerfil = (int) $idPerfil;
    mysqli_query($conexao, "delete from perfil_permissao where idPerfil=" . $idPerfil);
    foreach ($codigos as $codigo) {
        mysqli_query($conexao, "insert into perfil_permissao (idPerfil, codigoPermissao) values ({$idPerfil}, " . (int) $codigo . ")");
    }
    registarAccao("Actualizou permissoes do Perfil");
}
