<?php

//
$protocolVersion_expected = "04";

//
$protocolVersion = $_GET["protocolVersion"];
$appVersion = $_GET["appVersion"];

//
if ($protocolVersion == "") {
  header("HTTP/1.1 415 Unsupported Media Type");
  print('{"error": "Parameter [protocolVersion] is not set"}');
  exit();
}

//
if ($appVersion == "") {
  header("HTTP/1.1 415 Unsupported Media Type");
  print('{"error": "Parameter [appVersion] is not set"}');
  exit();
}

// Проверка protocolVersion
if ($protocolVersion_expected != $protocolVersion) {
  header("HTTP/1.1 415 Unsupported Media Type");
  print('{"error": "Protocol version not valid, expected '.$protocolVersion_expected.'"}');
  exit();
}

?>