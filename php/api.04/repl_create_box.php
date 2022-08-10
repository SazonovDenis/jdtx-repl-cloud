<?php
// =================================
// jdtx.repl.main.api.UtMailerHttpManager#createMailBox
// =================================

error_reporting(0);
include "param_version.php";
include "content_root.php";
include "param_guid_box.php";
include "check_guid.php";



// Проверка, что ящика нет
if (file_exists($path_box)) {
  header("HTTP/1.1 520 Unknown Error");
  print('{"error": "Box already exists, guid ['.$guid.'], box ['.$box.']"}');
  return;
}



// Создание ящика
if (!mkdir($path_box, 0777, true)) {
  header("HTTP/1.1 520 Unknown Error");
  print('{"error": "Error create guid ['.$guid.'], box ['.$box.']"}');
  return;
}



// Результат ok
print('{"result": "ok"}');


?>

