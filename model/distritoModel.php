<?php
class Distrito
{
    private $idDistrito;
    private $nomeDistrito;
    private $idProvincia;
    private $nomeProvincia;
    private $idPais;
    private $nomePais;
    private $idContinente;
    private $nomeContinente;
    public function __construct($idDistrito, $nomeDistrito, $idProvincia, $nomeProvincia, $idPais, $nomePais, $idContinente, $nomeContinente)
    {
        $this->idDistrito = $idDistrito;
        $this->nomeDistrito = $nomeDistrito;
        $this->idProvincia = $idProvincia;
        $this->nomeProvincia = $nomeProvincia;
        $this->idPais = $idPais;
        $this->nomePais = $nomePais;
        $this->idContinente = $idContinente;
        $this->nomeContinente = $nomeContinente;
    }
    public function getIdDistrito(){return $this->idDistrito;}
    public function setIdDistrito($idDistrito)  { $this->idDistrito = $idDistrito; return $this; }
    public function getNomeDistrito()  { return $this->nomeDistrito; }
    public function setNomeDistrito($nomeDistrito) {$this->nomeDistrito = $nomeDistrito;return $this; }
    public function getIdProvincia() { return $this->idProvincia; }
    public function setIdProvincia($idProvincia) { $this->idProvincia = $idProvincia; return $this; }
    public function getNomeProvincia() { return $this->nomeProvincia; }
    public function setNomeProvincia($nomeProvincia)  {  $this->nomeProvincia = $nomeProvincia;  return $this; }
    public function getIdPais() {  return $this->idPais; }
    public function setIdPais($idPais) {$this->idPais = $idPais;return $this; }
    public function getNomePais()  {   return $this->nomePais; }
    public function setNomePais($nomePais)  { $this->nomePais = $nomePais; return $this;  }
    public function getIdContinente() { return $this->idContinente;  }
    public function setIdContinente($idContinente) { $this->idContinente = $idContinente; return $this; }
    public function getNomeContinente() { return $this->nomeContinente; }
    public function setNomeContinente($nomeContinente) {     $this->nomeContinente = $nomeContinente;     return $this; }
}