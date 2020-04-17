<?php
 

  class Mensagem{
    private $para = null;
    private $assunto = null;
    private $mensagem = null;

    public function __get( $recurso ){
      return $this->$recurso;
    }

    public function __set($atributo, $valor)
    {
      return $this->$atributo = $valor;
    }

    public function mensagemValida(){
      if (empty($this->para) || empty($this->assunto) || empty($this->mensagem)) {
        return false;
      }
      return true;
    }
  }

  $mensagem = new Mensagem();
  $mensagem->__set('para', $_POST['para']);
  $mensagem->__set('assunto',$_POST['assunto']);
  $mensagem->__set('mensagem', $_POST['mensagem']);
  if ($mensagem->mensagemValida()){
    echo 'Não esta vazio!';
  }else {
    echo 'campo vazio';
  }

?>