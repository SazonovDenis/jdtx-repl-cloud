<?php
// =================================
// ���������� ������������ data
// jdtx.repl.main.api.mailer.IMailer#setData
// =================================

error_reporting(0);
include "content_root.php";
include "param_version.php";
include "param_guid.php";
include "check_content_root.php";
include "check_guid.php";

//
$data = $_GET["data"];

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



// ���� �������� ������
$file_name = $path_box."/".$name;



// �������� � ������ dt - ��������� ����
$data_json = json_decode($data, true);
$dt = date("c");
$data_json["dt"] = $dt;

// �������� � ������ � ������
$data_json["appVersion"] = $appVersion;
$data_json["protocolVersion"] = $protocolVersion;

//
$data = json_encode($data_json);



// ���������� ������
unlink($file_name);
if (!file_put_contents($file_name, $data)) {
  header("HTTP/1.1 520 Unknown Error");
  print('{"error": "Upload file error: file_put_contents, file: ['.$file_name.']"}');
  return;
}



//
print('{"result": "ok"}');


?>
