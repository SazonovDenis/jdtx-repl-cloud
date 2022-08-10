<?php 

$no = $_GET["no"];

//
if ($no == "") {
  header("HTTP/1.1 415 Unsupported Media Type");
  print('{"error": "Parameter [no] is not set"}');
  exit();
}

?>