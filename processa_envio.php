<?php

  require './bibliotecas/PHPMailer/Exception.php';
  require './bibliotecas/PHPMailer/OAuth.php';
  require './bibliotecas/PHPMailer/PHPMailer.php';
  require './bibliotecas/PHPMailer/SMTP.php';
  require './bibliotecas/PHPMailer/POP3.php';

  //Import PHPMailer classes into the global namespace
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

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
  if (!$mensagem->mensagemValida()){
    echo 'Campo vazio mensagem não valida!';
    die();
  }
  $mail = new PHPMailer(true);
  try {
    //Server settings
    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'lucassantoscrfbezerra@gmail.com';                 // SMTP username
    $mail->Password = 'senhaEmail';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('lucassantoscrfbezerra@gmail.com', 'Remetente: LucasSantos');
    $mail->addAddress($mensagem->__get('para'), 'Destinatario');     // Add a recipient
    //caso de mais destinatário so copiar a linha abaixo quantas vezes forem necessario
    // $mail->addAddress('ellen@example.com');               // Name is optional
    //destinatario para resposta seja um terceiro  $mail->addReplyTo('info@example.com', 'Information');
    //destinatario de copia  $mail->addCC('cc@example.com');
    //destinatario de copia oculta  $mail->addBCC('bcc@example.com');

    //Attachments
   //anexos $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
   //anexos $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $mensagem->__get('assunto');
    $mail->Body    = $mensagem->__get('mensagem');
    $mail->AltBody = 'é necessario utilizar um cliente que suporte HTML para ter acesso total ao conteudo dessa mensagem';

    $mail->send();
    echo 'E-mail enviado com sucesso!';
  } catch (Exception $e) {
    echo 'Não foi possivel enviar esse e-mail, por favor tente novamente
    mais tarde!.';
    echo 'Detalhes do erro: ' . $mail->ErrorInfo;
  }

?>