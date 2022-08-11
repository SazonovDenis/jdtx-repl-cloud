<?php
// =================================
// Прочитать произвольную data
// jdtx.repl.main.api.IJdxMailer#getData
// =================================

error_reporting(0);
include "content_root.php";
include "param_version.php";
include "param_guid.php";
include "check_content_root.php";
include "check_guid.php";

//
$box = $_GET["box"];
if ($box != "") {
  include "param_box.php";
  include "check_box.php";
  //
  $path_box = $content_root.$guid."/".$box;
} else {
  $path_box = $content_root.$guid;
}

//
$name = $_GET["name"];
if ($name == "") {
  header("HTTP/1.1 415 Unsupported Media Type");
  print('{"error": "Parameter [name] is not set"}');
  exit();
}

//
if (preg_match('/^[a-zA-Z0-9_][a-zA-Z0-9_\-\.]*$/', $name) != 1) {
  header("HTTP/1.1 415 Unsupported Media Type");
  print('{"error": "Parameter [name] is not valid"}');
  exit();
}


if ($name == "files") {
  // Запрошена информация о репликах в каталоге - отдаем по файлам *.info
  $file_mask = $path_box."/?????????.info";
  $filelist = glob($file_mask);

  //
  $no_max = 0;
  $no_min = 999999999;
  //
  foreach ($filelist as $file_name) {
    $s = substr($file_name, strlen($path_box)+1, 9);
    $no = intval($s);
    if ($no > $no_max) {
      $no_max = $no;
    }
    if ($no < $no_min) {
      $no_min = $no;
    }
  }
  //
  if ($no_max == 0) {
    $no_min = 0;
  }


  // Результат ok
  $result='{"result": "ok", "files": {'.'"min": '.$no_min.', "max": '.$no_max.'}}';

} else {
  // Откуда (из какого файла) прочитать данные
  $file_name = $path_box."/".$name;

  // Инфа из указанного файла 
  $data = file_get_contents($file_name);
  if ($data == null) {
    $data = "{}";
  }


  // Результат ok
  $result='{"result": "ok", "data": '.$data.'}';

}


header("Content-Length: ".strlen($result));
print($result);

?>
