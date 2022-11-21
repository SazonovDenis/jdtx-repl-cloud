<?php

if (!is_dir($content_root)) {
  header("HTTP/1.1 415 Unsupported Media Type");
  print('{"result": "error", "error": "content_root in not exists: \''.$content_root.'\' (at \''.realpath(".").')\''.'"}');
  exit();
}

?>