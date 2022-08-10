<?php
// =================================
// Вход: проверка пароля и переадресация
// =================================

$pass = $_POST["pass"];
$pass_md5 = md5($pass);

//
$pass_token = file_get_contents("web_pass/pass_token.txt");
$pass_md5_requred = file_get_contents("web_pass/pass_md5.txt");
$pass_token_pass_md5 = md5($pass_token.$pass_md5);
$token_cooke = $pass_token.":".$pass_token_pass_md5;

//
if ($pass_md5 == $pass_md5_requred) {
  setcookie("token", $token_cooke);
  header('Location: web_status_all.html');
  exit;
} else {
  header('Location: index.php');
  //print($pass_md5);
  //print("=");
  //print($pass_md5_requred);
  exit;
}
?>
