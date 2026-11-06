<?php

class Compra
{
    private $idcompra;
    private $quantidade;
    private $dataCompra;
    private $idproduto;
    private $idcliente;
    private $nomeProduto;
    private $precoProduto;
    private $nomeCliente;
    private $nomeCaixa;
    private $unidadeMedida;

    public function __construct($idcompra, $quantidade,
     $dataCompra, $idproduto, $idcliente,
     $nomeProduto, $precoProduto, $nomeCliente, $nomeCaixa,
      $unidadeMedida)
    {
        $this->idcompra = $idcompra;
        $this->quantidade = $quantidade;
        $this->dataCompra = $dataCompra;
        $this->idproduto = $idproduto;
        $this->idcliente = $idcliente;
        $this->nomeProduto = $nomeProduto;
        $this->precoProduto = $precoProduto;
        $this->nomeCliente = $nomeCliente;
        $this->nomeCaixa = $nomeCaixa;
        $this->unidadeMedida = $unidadeMedida;
    }

    public function getIdcompra(){return $this->idcompra; }
    public function setIdcompra($idcompra) {  $this->idcompra = $idcompra;  return $this; }
    public function getQuantidade() {return $this->quantidade;  }
    public function setQuantidade($quantidade) {  $this->quantidade = $quantidade; return $this; }
    public function getDataCompra()  {  return $this->dataCompra; }
    public function setDataCompra($dataCompra) { $this->dataCompra = $dataCompra; return $this;}
    public function getIdproduto() {return $this->idproduto;  }
    public function setIdproduto($idproduto) { $this->idproduto = $idproduto; return $this;  }
    public function getIdcliente() { return $this->idcliente;   }
    public function setIdcliente($idcliente)  { $this->idcliente = $idcliente; return $this;  }
    public function getNomeProduto()  { return $this->nomeProduto;}
    public function setNomeProduto($nomeProduto)  {  $this->nomeProduto = $nomeProduto;  return $this;  }
    public function getPrecoProduto() {  return $this->precoProduto;}
    public function setPrecoProduto($precoProduto) { $this->precoProduto = $precoProduto; return $this; }
    public function getNomeCliente() {  return $this->nomeCliente;  }
    public function setNomeCliente($nomeCliente)  { $this->nomeCliente = $nomeCliente; return $this; }
    public function getNomeCaixa() {  return $this->nomeCaixa;}
    public function setNomeCaixa($nomeCaixa) {  $this->nomeCaixa = $nomeCaixa;  return $this; }
    public function getUnidadeMedida() {   return $this->unidadeMedida;   }
    public function setUnidadeMedida($unidadeMedida) {  $this->unidadeMedida = $unidadeMedida;  return $this; }
}