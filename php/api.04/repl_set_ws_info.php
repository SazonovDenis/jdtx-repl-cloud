<?php
// =================================
// jdtx.repl.main.api.IJdxMailer#setWsInfo
// =================================

error_reporting(0);
include "param_version.php";
include "content_root.php";
include "param_guid.php";
include "check_guid.php";



// Данные из запроса
$ws_info = array();
//
$ws_info["out_auditAgeActual"] = $_GET["out_auditAgeActual"];
$ws_info["out_queAvailable"] = $_GET["out_queAvailable"];
$ws_info["out_sendDone"] = $_GET["out_sendDone"];
$ws_info["in_mailAvailable"] = $_GET["in_mailAvailable"];
$ws_info["in_queInNoAvailable"] = $_GET["in_queInNoAvailable"];
$ws_info["in_queInNoDone"] = $_GET["in_queInNoDone"];
//
$ws_info["databaseInfo"] = $_GET["databaseInfo"];
$ws_info["isMute"] = $_GET["isMute"];



// Куда записать данные
$file_name = $content_root.$guid."/ws.info";


// json в строку и в файл
$ws_info_str = json_encode($ws_info);
unlink($file_name);
if (!file_put_contents($file_name, $ws_info_str)) {
  header("HTTP/1.1 520 Unknown Error");
  print('{"error": "Upload file error: file_put_contents, file: ['.$file_name.']"}');
  return;
}


//
print('{"result": "ok"}');


?>

