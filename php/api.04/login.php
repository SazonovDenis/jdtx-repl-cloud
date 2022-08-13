<?php
// =================================
// jdtx.repl.main.api.IJdxMailer#login
// =================================
error_reporting(0);
include "../auth/auth.php";



//
$pass = $_GET["pass"];

//
$token = login($pass);
if (!$token) {
  header("HTTP/1.1 401 Authorisation failed");
  print('{"error": "Authorisation failed"}');
  return;
}


// Результат ok
print('{"result": "ok", "token": "'.$token.'"}');

?>
