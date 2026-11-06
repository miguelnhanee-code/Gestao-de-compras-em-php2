<?php

class Fornecedor
{
    private $idFornecedor;
    private $nomeFornecedor;
    private $telefone;
    private $email;
    private $idProvincia;
    private $nomeProvincia;
    private $idDistrito;
    private $nomeDistrito;
    private $idPostoAdministrativo;
    private $nomePostoAdministrativo;
    private $idBairro;
    private $nomeBairro;
    private $quarteirao;
    private $nrEmpresa;
    private $nomePais;
    private $nomeContinente;

    public function __construct($idFornecedor, $nomeFornecedor, 
    $telefone, $email, $idProvincia, $nomeProvincia,
     $idDistrito, $nomeDistrito, $idPostoAdministrativo,
      $nomePostoAdministrativo, $idBairro, $nomeBairro,
       $quarteirao, $nrEmpresa, $nomePais = null, $nomeContinente = null)
    {
        $this->idFornecedor = $idFornecedor;
        $this->nomeFornecedor = $nomeFornecedor;
        $this->telefone = $telefone;
        $this->email = $email;
        $this->idProvincia = $idProvincia;
        $this->nomeProvincia = $nomeProvincia;
        $this->idDistrito = $idDistrito;
        $this->nomeDistrito = $nomeDistrito;
        $this->idPostoAdministrativo = $idPostoAdministrativo;
        $this->nomePostoAdministrativo = $nomePostoAdministrativo;
        $this->idBairro = $idBairro;
        $this->nomeBairro = $nomeBairro;
        $this->quarteirao = $quarteirao;
        $this->nrEmpresa = $nrEmpresa;
        $this->nomePais = $nomePais;
        $this->nomeContinente = $nomeContinente;
    }
    public function getIdFornecedor() {return $this->idFornecedor; }
    public function setIdFornecedor($idFornecedor){    $this->idFornecedor = $idFornecedor;    return $this;}
    public function getNomeFornecedor() {     return $this->nomeFornecedor; }
    public function setNomeFornecedor($nomeFornecedor) {  $this->nomeFornecedor = $nomeFornecedor;  return $this; }
    public function getTelefone()  { return $this->telefone;  }
    public function setTelefone($telefone) {   $this->telefone = $telefone;   return $this;  }
    public function getEmail() {  return $this->email;  }
    public function setEmail($email) {    $this->email = $email;   return $this;  }
    public function getIdProvincia() {   return $this->idProvincia; }
    public function setIdProvincia($idProvincia)  {   $this->idProvincia = $idProvincia;   return $this;}
    public function getNomeProvincia()  {      return $this->nomeProvincia;  }
    public function setNomeProvincia($nomeProvincia)  {      $this->nomeProvincia = $nomeProvincia;      return $this;  }
    public function getIdDistrito()   {       return $this->idDistrito;   }
    public function setIdDistrito($idDistrito) {     $this->idDistrito = $idDistrito;     return $this; }
    public function getNomeDistrito() {     return $this->nomeDistrito; }
    public function setNomeDistrito($nomeDistrito) {     $this->nomeDistrito = $nomeDistrito;     return $this; }
    public function getIdPostoAdministrativo(){    return $this->idPostoAdministrativo;}
    public function setIdPostoAdministrativo($idPostoAdministrativo) { $this->idPostoAdministrativo = $idPostoAdministrativo;  return $this;  }
    public function getNomePostoAdministrativo()  {      return $this->nomePostoAdministrativo;  }
    public function setNomePostoAdministrativo($nomePostoAdministrativo) {   $this->nomePostoAdministrativo = $nomePostoAdministrativo; return $this;   }
    public function getIdBairro() {     return $this->idBairro; }
    public function setIdBairro($idBairro) {     $this->idBairro = $idBairro;     return $this; }
    public function getNomeBairro() {     return $this->nomeBairro; }
    public function setNomeBairro($nomeBairro) {     $this->nomeBairro = $nomeBairro;  return $this; }
    public function getQuarteirao()   {       return $this->quarteirao;   }
    public function setQuarteirao($quarteirao)  {      $this->quarteirao = $quarteirao;   return $this;  }
    public function getNrEmpresa()  {      return $this->nrEmpresa;  }
    public function setNrEmpresa($nrEmpresa)  {      $this->nrEmpresa = $nrEmpresa;      return $this;  }
    public function getNomePais() {     return $this->nomePais; }
    public function setNomePais($nomePais)  {      $this->nomePais = $nomePais;      return $this;  }
    public function getNomeContinente()   {       return $this->nomeContinente;   }
    public function setNomeContinente($nomeContinente)  {      $this->nomeContinente = $nomeContinente;      return $this;  }
}
