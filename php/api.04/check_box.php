<?php

//
$path_box = $content_root.$guid."/".$box;

// Проверка наличия каталога guid/box
if (!file_exists($path_box)) {
  header("HTTP/1.1 415 Unsupported Media Type");
  print('{"error": "Guid ['.$guid.'], box ['.$box.'] not found"}');
  exit();
}

?>