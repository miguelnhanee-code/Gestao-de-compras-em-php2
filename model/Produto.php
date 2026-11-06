<?php
class Produto
{
    private $idproduto;
    private $nomeproduto;
    private $precoUnitario;
    private $dataValidade;
    private $dataConfecao;
    private $stock;
    private $idFornecedor;
    private $unidadeMedida;
    public function __construct($idproduto, $nomeproduto, $precoUnitario, $dataValidade,
     $dataConfecao, $stock = null, $idFornecedor = null, $unidadeMedida = null)
    {
        $this->idproduto = $idproduto;
        $this->nomeproduto = $nomeproduto;
        $this->precoUnitario = $precoUnitario;
        $this->dataValidade = $dataValidade;
        $this->dataConfecao = $dataConfecao;
        $this->stock = $stock;
        $this->idFornecedor = $idFornecedor;
        $this->unidadeMedida = $unidadeMedida;
    }
    public function getIdproduto() { return $this->idproduto; }
    public function setIdproduto($idproduto)  {  $this->idproduto = $idproduto; }
    public function getProduto() { return $this->nomeproduto;  }
    public function setProduto($nomeproduto) {  $this->nomeproduto = $nomeproduto; }
    public function getNomeproduto(){   return $this->nomeproduto;}
    public function setNomeproduto($nomeproduto)  { $this->nomeproduto = $nomeproduto;  }
    public function getPrecoUnitario() { return $this->precoUnitario;  }
    public function setPrecoUnitario($precoUnitario)  { $this->precoUnitario = $precoUnitario;  }
    public function getDataValidade() {  return $this->dataValidade;  }
    public function setDataValidade($dataValidade) {  $this->dataValidade = $dataValidade; }
    public function getDataConfecao() {  return $this->dataConfecao;  }
    public function setDataConfecao($dataConfecao) {   $this->dataConfecao = $dataConfecao;}
    public function getStock() {   return $this->stock; }
    public function setStock($stock) {     $this->stock = $stock; }
    public function getIdFornecedor() {     return $this->idFornecedor; }
    public function setIdFornecedor($idFornecedor) {     $this->idFornecedor = $idFornecedor; }
    public function getUnidadeMedida() {     return $this->unidadeMedida; }
    public function setUnidadeMedida($unidadeMedida)  {  $this->unidadeMedida = $unidadeMedida; } 
}
