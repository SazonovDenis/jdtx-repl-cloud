<?php

$content_root = "../../_data_root/";


if (!is_dir($content_root)) {
  print('{"result": "error", "error": "content_root in not exists: \''.$content_root.'\'"}');
  exit();
}

?>