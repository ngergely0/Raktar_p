<?php
require_once('AbstractPage.php');
require_once('UserDbTools.php');

$userDbTool = new UserDbTools();

if(isset($_GET['token'])) {
    $token = $_GET['token'];
    $registrationDate = new DateTime;  
    $userDbTool->updateUsers($registrationDate->format("Y-m-d H:i:s"), $token);
}


if (isset($_POST['Bejelentkezés'])) {
    
    $email = $_POST['Email'];
    $password = $_POST['Jelszó'];
    $savedpassword = $userDbTool->getUserPasswordByEmail($email);
    $privilege = $userDbTool->getUserPrivilegeByEmail($email);
    if($password == $savedpassword && $privilege == "Admin")
    {
        header('Location: index.php');
    }
    else
    {
        echo 'Error logging in';
    }
    if($password == $savedpassword && $privilege == "User")
    {
        header('Location: USerwebpage.php');
    }
    else
    {
        echo 'Error logging in';
    }

}

AbstractPage::login();
?>
