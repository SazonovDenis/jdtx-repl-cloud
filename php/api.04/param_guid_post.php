<?php

//
$guid = $_POST["guid"];

// �������� ��������� guid
if ($guid === null || $guid == "") {
  header("HTTP/1.1 415 Unsupported Media Type");
  print('{"error": "Parameter [guid] is not set"}');
  exit();
}

//
$guid = str_replace("-", "/", $guid);


?>