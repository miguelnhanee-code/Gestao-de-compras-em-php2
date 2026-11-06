<?php
class utilizadores
{
    private $idUtilizador;
    private $nome;
    private $apelido;
    private $email;
    private $contacto;
    private $username;
    private $senha;
    private $perfil;
    private $bi;
    private $estadoCivil;
    private $sexo;

    public function __construct($idUtilizador, $nome, $apelido, $email, 
    $contacto, $username, $senha, $perfil, $bi, $estadoCivil, $sexo)
    {
        $this->idUtilizador = $idUtilizador;
        $this->nome = $nome;
        $this->apelido = $apelido;
        $this->email = $email;
        $this->contacto = $contacto;
        $this->username = $username;
        $this->senha = $senha;
        $this->perfil = $perfil;
        $this->bi = $bi;
        $this->estadoCivil = $estadoCivil;
        $this->sexo = $sexo;
    }
    public function getIdUtilizador() { return $this->idUtilizador; }
    public function setIdUtilizador($idUtilizador)  { $this->idUtilizador = $idUtilizador; return $this;  }
    public function getNome() {     return $this->nome; }
    public function setNome($nome){    $this->nome = $nome; return $this; }
    public function getApelido() {return $this->apelido; }
    public function setApelido($apelido){  $this->apelido = $apelido;  return $this; }
    public function getEmail() { return $this->email;  }
    public function setEmail($email)  { $this->email = $email; return $this; }
    public function getContacto() { return $this->contacto; }
    public function setContacto($contacto) {  $this->contacto = $contacto;  return $this; }
    public function getUsername() {     return $this->username; }
    public function setUsername($username) {     $this->username = $username;     return $this; }
    public function getSenha() {     return $this->senha; }
    public function setSenha($senha)  {      $this->senha = $senha;      return $this;  }
    public function getPerfil()  {      return $this->perfil; }
    public function setPerfil($perfil) {  $this->perfil = $perfil;  return $this; }
    public function getBi() {     return $this->bi; }
    public function setBi($bi)  {     $this->bi = $bi;     return $this;  }
    public function getEstadoCivil() {     return $this->estadoCivil; }
    public function setEstadoCivil($estadoCivil) {     $this->estadoCivil = $estadoCivil;     return $this; }
    public function getSexo() {  return $this->sexo;   }
    public function setSexo($sexo) {     $this->sexo = $sexo;     return $this; }
}
