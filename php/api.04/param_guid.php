<?php

//
$guid = $_GET["guid"];

// Проверка параметра guid
if ($guid == "") {
  header("HTTP/1.1 415 Unsupported Media Type");
  print('{"error": "Parameter [guid] is not set"}');
  exit();
}

//
$guid = str_replace("-", "/", $guid);


?>