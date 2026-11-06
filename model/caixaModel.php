<?php

class caixa
{
    private $idCaixa;
    private $nomeCaixa;

    public function __construct($idCaixa, $nomeCaixa)
    {
        $this->idCaixa = $idCaixa;
        $this->nomeCaixa = $nomeCaixa;
    }

    public function getIdCaixa()
    {
        return $this->idCaixa;
    }
    public function setIdCaixa($idCaixa)
    {
        $this->idCaixa = $idCaixa;
        return $this;
    }
    public function getNomeCaixa()
    {
        return $this->nomeCaixa;
    }
    public function setNomeCaixa($nomeCaixa)
    {
        $this->nomeCaixa = $nomeCaixa;
        return $this;
    }
}
