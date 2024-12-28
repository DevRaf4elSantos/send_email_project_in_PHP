<?php       
    require "./bibliotecas/src/Exception.php";
    require "./bibliotecas/src/OAuth.php";
    require "./bibliotecas/src/PHPMailer.php";
    require "./bibliotecas/src/POP3.php";
    require "./bibliotecas/src/SMTP.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    class Mensagem{
        private $para = null;
        private $assunto = null;
        private $mensagem = null;
        public $status = array('codigo_status' => null, 'descricao_status' => ''); 
        
        function __get($attr)
        {
            return $this->$attr;
        }

        function __set($attr, $newValor){
            return $this->$attr = $newValor;
        }

        public function validaMensagem(){
            if(empty( $this->para)  || empty ($this->assunto) || empty( $this->mensagem )){
                return false;
            }
            if(filter_var($this->para, FILTER_VALIDATE_EMAIL) == false){
                echo "<br> O Email digitado é inválido <br>";
                return false;
            }
            return true;
        }
    }

    $mensagem = new Mensagem;

    $mensagem->para = $_POST['para'];
    $mensagem->assunto = $_POST['assunto'];
    $mensagem->mensagem = $_POST['mensagem'];

    if(!$mensagem->validaMensagem()){
        echo "mensagem inválida";
        header('location: index.php?login=error');
    }
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = false;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'webcompleto2@gmail.com';                     //SMTP username
        $mail->Password   = '!@#4321';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('webcompleto2@gmail.com', 'Remetente Web');
        $mail->addAddress($mensagem->__get('para'));     //Add a recipient

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $mensagem->__get('assunto');
        $mail->Body    = $mensagem->__get('mensagem');
        $mail->AltBody = 'Para visualizar o email de maneira completa você deve usar o cliente que suporta html';

        $mail->send();
        $mensagem->status['codigo_status'] = 1;
        $mensagem->status['descricao_status'] = "Mensagem enviada com sucesso!";
    } catch (Exception $e) {
        $mensagem->status['codigo_status'] = 2;
        $mensagem->status['descricao_status'] = "Não foi possível fazer o envio da mensagem, Tente novamente mais tarde! Detalhes do erro: " . $mail->ErrorInfo;
    }
?>

    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>App_send_email</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>

        <div class="container">  

			<header class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</header>
        
        <div class="row">
            <div class="col-md-12">


                <?php
                    if($mensagem->status['codigo_status'] == 1){
                    
                ?>
                    <div class="container">  
                        <h1 class="display-4 text-success">Sucesso</h1>
                        <p><?php echo $mensagem->status['descricao_status']?></p>
                        <a class="btn btn-success text-white" href="index.php" >Voltar</a>
                    </div>
                <?php
                    }
                        ?>

                
                <?php
                    if($mensagem->status['codigo_status'] == 2){
                    
                ?>
                    <div class="container">  
                        <h1 class="display-4 text-danger">Ops! </h1>
                        <p> <?php echo $mensagem->status['descricao_status']?> </p>
                        <a class="btn btn-warning text-white" href="index.php" >Voltar</a>
                    </div>
                <?php
                    }
                ?>

            </div>
        </div>
    </body>
    </html>
