<?php
session_start();
require_once("argoapi.php");
$path = $_SERVER['DOCUMENT_ROOT'] . "/index.php";
require($path);


//Recupero i dati del form
$password = "";
$username = "";

if(isset($_POST['username'])){
    $username = $_POST['username'];
}
if(isset($_POST['password'])){
    $password = $_POST['password'];
}
try {
    $argo = new argoUser("SG18161", $username, $password);
    if($argo->isLogged()) {
        $token = $argo->getToken("SG18161", $username, $password);
        if(!username_exists($username)) { //E se l'utente non esiste, creiamo il suo user
            wp_create_user($username, $token);
        }
            $creds = array();
            $creds['user_login'] = $username;
            $creds['user_password'] = $token;
            $creds['remember'] = true;
            $user = wp_signon( $creds, false);

            header("location: https://coffee-up.it/");
    } else {
        header("location: https://coffee-up.it/argo-login/error.html");
    }
}
catch (Exception $e) {
    header("location: https://coffee-up.it/argo-login/error.html");
}
?>