<?php

//
$box = $_GET["box"];

// Проверка параметра box
if ($box == "") {
  header("HTTP/1.1 415 Unsupported Media Type");
  print('{"error": "Parameter [box] is not set"}');
  exit();
}


?>