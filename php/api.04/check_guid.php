<?php

// ѕроверка наличи€ корневого каталога дл€ guid
$guid_arr = explode("/", $guid);

//
if (count($guid_arr) != 2) {
  header("HTTP/1.1 415 Unsupported Media Type");
  print('{"error": "Bad guid ['.$guid.']"}');
  exit();
}

//
$guid_root = $guid_arr[0];
$guid_root = $content_root.$guid_root;

//
if (!file_exists($guid_root)) {
  header("HTTP/1.1 415 Unsupported Media Type");
  print('{"error": "Guid root not found, guid ['.$guid.']"}');
  exit();
}

?>