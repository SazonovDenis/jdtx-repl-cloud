<?php

//
$guid = $_POST["guid"];
$box = $_POST["box"];

// Проверка параметров guid, box
if ($guid == "") {
  header("HTTP/1.1 415 Unsupported Media Type");
  print('{"error": "POST Parameter [guid] is not set"}');
  exit();
}

//
if ($box == "") {
  header("HTTP/1.1 415 Unsupported Media Type");
  print('{"error": "POST Parameter [box] is not set"}');
  exit();
}

//
$guid = str_replace("-", "/", $guid);

//
$path_box = $content_root.$guid."/".$box;


?>