<?php
require_once('AbstractPage.php');
require_once('UserDbTools.php');

$userDbTool = new UserDbTools();




if (isset($_POST['Bejelentkezés'])) {
    
    $email = $_POST['Email'];
    $password = $_POST['Jelszó'];
    $user = $userDbTool->getUserByEmail($email);
   
}

AbstractPage::login();
?>
