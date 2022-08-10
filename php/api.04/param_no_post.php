<?php 

$no = $_POST["no"];

//
if ($no == "") {
  header("HTTP/1.1 415 Unsupported Media Type");
  print('{"error": "POST Parameter [no] is not set"}');
  exit();
}

?>