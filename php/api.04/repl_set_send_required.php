<?php
// =================================
// jdtx.repl.main.api.IJdxMailer#setSendRequired
// =================================

error_reporting(0);
include "param_version.php";
include "content_root.php";
include "param_guid_box.php";
include "check_guid.php";
include "check_box.php";



// Данные из запроса
$required_info = array();
//
$required_info["requiredFrom"] = $_GET["requiredFrom"];
$required_info["requiredTo"] = $_GET["requiredTo"];
$required_info["recreate"] = $_GET["recreate"];
$required_info["executor"] = $_GET["executor"];


// Куда записать данные
$file_name = $path_box."/required.info";


// json в строку и в файл
$required_info_str = json_encode($required_info);
unlink($file_name);
if (!file_put_contents($file_name, $required_info_str)) {
  header("HTTP/1.1 520 Unknown Error");
  print('{"error": "Upload file error: file_put_contents, file: ['.$file_name.']"}');
  return;
}


//
print('{"result": "ok"}');


?>

