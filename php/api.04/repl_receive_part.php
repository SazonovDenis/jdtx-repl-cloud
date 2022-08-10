<?php
// =================================
// jdtx.repl.main.api.IJdxMailer#receive
// =================================

error_reporting(0);
include "param_version.php";
include "content_root.php";
include "param_guid_box.php";
include "param_no.php";
include "check_guid.php";
include "check_box.php";
//
$part = $_GET["part"];



//
$file_no = str_pad($no, 9, "0", STR_PAD_LEFT);
$file_name = $path_box."/".$file_no;
//
$file_part = str_pad($part, 3, "0", STR_PAD_LEFT);
$file_name_part = $file_name.".".$file_part;

//
if (!file_exists($file_name_part)) {
  header("HTTP/1.1 400 Not found");
  print('{"error": "File part not found, guid ['.$guid.'], box ['.$box.'], no ['.$no.'], part ['.$part.']"}');
  return;
}

//
$content = file_get_contents($file_name_part);

//
header("Content-Type: application/zip");
header("Content-Length: ".filesize($file_name_part));
print($content);



// Отметим "last.read"
$last_file_name = $path_box."/last.read";

//
$no_now = $no;
$part_now = $part;

// Узнаем, какой номер был передан последним
$last_data_str = file_get_contents($last_file_name);
if ($last_data_str) {
  $last_data_json = json_decode($last_data_str, true);
  $no_last = intval($last_data_json["no"]);
  $part_last = intval($last_data_json["part"]);
} else {
  $no_last = 0;
  $part_last = 0;
}

// Отметим, если отправили реплику с номером номер большим, чем раньше
if (($no_now > $no_last) || (($no_now == $no_last) && ($part_now >= $part_last))) {
  // формируем json
  $last_data_json["dt"] = date("c");
  $last_data_json["no"] = $no_now;
  $last_data_json["part"] = $part_now;
  // json в строку
  $last_data_str = json_encode($last_data_json);
  // файл удаляем
  unlink($last_file_name);
  // json в файл
  if (!file_put_contents($last_file_name, $last_data_str)) {
    header("HTTP/1.1 520 Unknown Error");
    print('{"error": "Upload file error: file_put_contents, last.read"}');
    return;
  }
}



?>
