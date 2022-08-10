<?php
// =================================
// jdtx.repl.main.api.IJdxMailer#getSendRequired
// =================================

error_reporting(0);
include "param_version.php";
include "content_root.php";
include "param_guid_box.php";
include "check_guid.php";
include "check_box.php";


// Инфа о запросах "рассылка по требованию"
$required_info = file_get_contents($path_box."/required.info");
//
if ($required_info == null) {
  $required_info = "{}";
}


// Результат ok
$result='{"result": "ok", "required": '.$required_info.'}';
header("Content-Length: ".strlen($result));
print($result);


?>
