<?php

class Cliente
{
    private $idCliente;
    private $nomeCliente;
    private $apelidoCliente;
    private $idCaixa;
    private $nomeCaixa;

    public function __construct($idCliente, $nomeCliente, $apelidoCliente, $idCaixa, $nomeCaixa = null)
    {
        $this->idCliente = $idCliente;
        $this->nomeCliente = $nomeCliente;
        $this->apelidoCliente = $apelidoCliente;
        $this->idCaixa = $idCaixa;
        $this->nomeCaixa = $nomeCaixa;
    }

    public function getIdCliente() {return $this->idCliente;}
    public function setIdCliente($idCliente) {$this->idCliente = $idCliente; return $this; }
    public function getNomeCliente() { return $this->nomeCliente; }
    public function setNomeCliente($nomeCliente) { $this->nomeCliente = $nomeCliente; return $this; }
    public function getApelidoCliente()  {return $this->apelidoCliente; }
    public function setApelidoCliente($apelidoCliente) { $this->apelidoCliente = $apelidoCliente; return $this;}
    public function getIdCaixa() { return $this->idCaixa; }
    public function setIdCaixa($idCaixa) { $this->idCaixa = $idCaixa; return $this; }
    public function getNomeCaixa() {return $this->nomeCaixa;}
    public function setNomeCaixa($nomeCaixa){  $this->nomeCaixa = $nomeCaixa;  return $this; }
}
