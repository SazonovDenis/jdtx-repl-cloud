<?php
// =================================
// Вход: проверка пароля и переадресация
// =================================

include "../auth/auth.php";



//
$pass = $_POST["pass"];

//
if (!login($pass)) {
  header('Location: index.php');
  exit;
} else {
  header('Location: web_status_all.html');
  exit;
}

?>
