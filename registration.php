<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once('vendor/autoload.php');
require_once('AbstractPage.php');
require_once('UserDbTools.php');

$mail = new PHPMailer(true);

$userDbTool = new UserDbTools();


if (isset($_POST['Regisztráció'])) {
    
    $name = $_POST['Név'];
    $email = $_POST['Email'];
    $password = $_POST['Jelszó'];
    $confirm_password = $_POST['Jelszó2'];
    
    if($password != $confirm_password){
        echo "A jelszavak nem egyeznek!";
    }
    else{
    $result = $userDbTool->createUsers($name, $email, $password);
    $token = $userDbTool->getUserByEmail($email);
    
    try {
     
        $mail->isSMTP();                                            
        $mail->Host       = 'localhost';
        $mail->SMTPAuth = false;                    
        $mail->Port       = 1025;                  
    
        //Recipients
        $mail->setFrom('from@example.com', 'Mailer');
        $mail->addAddress($email, $name);     
        $mail->addCC('cc@example.com');
        $mail->addBCC('bcc@example.com');
    
        //Attachments
              
        

        //Content
        $mail->isHTML(true);                                  
        $mail->Subject = 'Validalas';
        $mail->Body    = 'Itt a link a megerősítéshez: <a href="http://localhost:8083/Raktar_p/validation.php?token='.$token.'">Megerősítés</a>';
        $mail->AltBody = 'Valami';
    
        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }    
    header ('Location: login.php');
    exit;
    }
   
}

AbstractPage::registration();








