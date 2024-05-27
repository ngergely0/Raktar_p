<?php
require_once('UserDbTools.php');
require_once('AbstractPage.php');

$userDbTool = new UserDbTools();

AbstractPage::validation();

if(isset($_GET['token'])) {
    $token = $_GET['token'];
    $registrationDate = new DateTime;  
    $usersDbTools->updateUsers( $registrationDate->format("Y-m-d H:i:s"), $token);
}


