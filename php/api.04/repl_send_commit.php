<?php
// =================================
// jdtx.repl.main.api.IJdxMailer
// =================================

error_reporting(0);
include "param_version.php";
include "content_root.php";
include "param_guid_box_post.php";
include "param_no_post.php";
include "check_guid.php";
include "check_box.php";
//
$info_str = $_POST["info"];



//
$file_no = str_pad($no, 9, "0", STR_PAD_LEFT);
$file_name = $path_box."/".$file_no;



// ���������� ������� ���������� � ����
unlink($file_name.".info");
if (!file_put_contents($file_name.".info", $info_str)) {
  header("HTTP/1.1 520 Unknown Error");
  print('{"error": "Upload file error: file_put_contents, .info"}');
  return;
}



// ���� ������ ��������� ������� � ������� ����� �������, ��� ������, ��
// - ������� ����� � ����� "last.write"
// - ������� ���������� ������� � ����� "last.dat.info", 

// ������, ����� ����� ��� ������� ���������
$last_write_file_name = $path_box."/last.write";
$last_write_data_str = file_get_contents($last_write_file_name);
if ($last_write_data_str) {
  $last_write_data_json = json_decode($last_write_data_str, true);
  $last_write_no = intval($last_write_data_json["no"]);
  $last_write_part = intval($last_write_data_json["part"]);
} else {
  // ����� commit �������������, ��� last.write ��� ����, ����������� ����������� �������� send
  header("HTTP/1.1 520 Unknown Error");
  print('{"error": "Upload file error: file_get_contents, last.write"}');
  return;
}

// C����� ��������� ������� � ������� ������� (��� ���� �� ������), ��� ������?
if ($no >= $last_write_no) {
  // --- ���� last.write
  // ���������� json
  $last_write_data_json["all"] = "true";
  $last_write_data_str = json_encode($last_write_data_json);
  
  // ������ ����
  unlink($last_write_file_name);
  if (!file_put_contents($last_write_file_name, $last_write_data_str)) {
    header("HTTP/1.1 520 Unknown Error");
    print('{"error": "Upload file error: file_put_contents, last.write"}');
    return;
  }

  
  // --- ���� last.dat.info
  // ����������� json �� ����� - �� ��� ������� ������� � $info_str
  
  // ������ ����
  $last_dat_file_name = $path_box."/last.dat.info";
  unlink($last_dat_file_name);
  if (!file_put_contents($last_dat_file_name, $info_str)) {
    header("HTTP/1.1 520 Unknown Error");
    print('{"error": "Upload file error: file_put_contents, last.dat.info"}');
    return;
  }
}



//
print('{"result": "ok"}');



?>