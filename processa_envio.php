<?php

require './bibliotecas/PHPMailer/Exception.php';
require './bibliotecas/PHPMailer/OAuth.php';
require './bibliotecas/PHPMailer/PHPMailer.php';
require './bibliotecas/PHPMailer/SMTP.php';
require './bibliotecas/PHPMailer/POP3.php';

//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mensagem
{
  private $para = null;
  private $assunto = null;
  private $mensagem = null;
  public $statusEnvio = array('codigo_status' => null, 'descricao' => '');

  public function __get($recurso)
  {
    return $this->$recurso;
  }

  public function __set($atributo, $valor)
  {
    return $this->$atributo = $valor;
  }

  public function mensagemValida()
  {
    if (empty($this->para) || empty($this->assunto) || empty($this->mensagem)) {
      return false;
    }
    return true;
  }
}

$mensagem = new Mensagem();
$mensagem->__set('para', $_POST['para']);
$mensagem->__set('assunto', $_POST['assunto']);
$mensagem->__set('mensagem', $_POST['mensagem']);
if (!$mensagem->mensagemValida()) {
  echo 'Campo vazio mensagem não valida!';
  header('Location: index.php');
  die();
}
$mail = new PHPMailer(true);
try {
  //Server settings
  $mail->SMTPDebug = false;                                 // Enable verbose debug output
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
  $mensagem->statusEnvio['codigo_status'] = 1;
  $mensagem->statusEnvio['descricao'] = 'E-mail enviado com sucesso!';
} catch (Exception $e) {
  $mensagem->statusEnvio['codigo_status'] = 2;
  $mensagem->statusEnvio['descricao'] = 'Não foi possivel enviar esse e-mail, por favor tente novamente
    mais tarde!. Detalhes do erro: ' . $mail->ErrorInfo;
}
echo 'Valor: ' . $mensagem->statusEnvio['codigo_status'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta charset="utf-8" />
  <title>App Mail Send</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
  <div class="container">

    <div class="py-3 text-center">
      <img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
      <h2>Send Mail</h2>
      <p class="lead">Seu app de envio de e-mails particular!</p>
    </div>

    <div class="row">
      <div class="col-md-12">
        <?php if ($mensagem->statusEnvio['codigo_status'] == 1) { ?>
          <div class="container">
            <h1 class="display-4 text-success"> Sucesso </h1>
            <p><?= $mensagem->statusEnvio['descricao'] ?></p>
            <a href="index.php" class="btn btn-success btn-lg mt-3 text-white">
              Voltar
            </a>
          </div>
        <?php } ?>

        <?php if ($mensagem->statusEnvio['codigo_status'] == 2) { ?>
          <div class="container">
            <h1 class="display-4 text-danger"> Ops! </h1>
            <p><?= $mensagem->statusEnvio['descricao'] ?></p>
            <a href="index.php" class="btn btn-success btn-lg mt-3 text-white">
              Voltar
            </a>
          </div>
        <?php } ?>

      </div>

    </div>
  </div>
</body>

</html>