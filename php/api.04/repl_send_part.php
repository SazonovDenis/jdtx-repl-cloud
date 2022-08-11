<?php
// =================================
// jdtx.repl.main.api.IJdxMailer#send
// =================================

error_reporting(0);
include "content_root.php";
include "param_version.php";
include "param_guid_box_post.php";
include "param_no_post.php";
include "check_content_root.php";
include "check_guid.php";
include "check_box.php";
//
$part = $_POST["part"];
$partCrc = $_POST["partCrc"];



//
$file_no = str_pad($no, 9, "0", STR_PAD_LEFT);
$file_name = $path_box."/".$file_no;
//
$file_part = str_pad($part, 3, "0", STR_PAD_LEFT);
$file_name_part = $file_name.".".$file_part;
$file_name_part_tmp = $file_name.".".$file_part.".tmp";


//
$tmp_file_error = $_FILES['file']['error'];
$tmp_file_name = $_FILES['file']['name'];



// ���������� ������� �� �����, ���� �� ���������� ��������
unlink($file_name.".info");



// ���� �� ����������
unlink($file_name_part);
unlink($file_name_part_tmp);
if (!move_uploaded_file($_FILES['file']['tmp_name'], $file_name_part_tmp)) {
  header("HTTP/1.1 520 Unknown Error");
  print('{"error": "Upload file error: move_uploaded_file, code: '.$tmp_file_error.', file_name: '.$tmp_file_name.', no: '.$no.', part: '.$part.'"}');
  return;
}



// ����������� ����� ����� (��� �����)
$partCrc = strtoupper($partCrc);
//
$partCrcActual = md5_file($file_name_part_tmp);
$partCrcActual = strtoupper($partCrcActual);
//
if ($partCrcActual != $partCrc) {
  header("HTTP/1.1 520 Unknown Error");
  print('{"error": "Upload file error: CRC, partCrc: '.$partCrc.', partCrcActual: '.$partCrcActual.', no: '.$no.', part: '.$part.'"}');
  return;
}



// ���� (��� �����) �� ���������� ����� 
rename($file_name_part_tmp, $file_name_part);



// ���� ������ ��������� ������� � ������� ����� �������, ��� ������, ��
// - ������� ����� � ����� "last.write"

// ������, ����� ����� ��� ������� ���������
$last_write_file_name = $path_box."/last.write";
$last_write_data_str = file_get_contents($last_write_file_name);
if ($last_write_data_str) {
  $last_write_data_json = json_decode($last_write_data_str, true);
  $last_write_no = intval($last_write_data_json["no"]);
  $last_write_part = intval($last_write_data_json["part"]);
} else {
  $last_write_no = 0;
  $last_write_part = 0;
}

// C����� ��������� ������� � ������� ������� (��� ���� �� ������), ��� ������?
if (($no >= $last_write_no) || ($no == $last_write_no && $part >= $last_write_part)) {
  // --- ���� last.write
  // ��������� json
  $last_write_data_json["dt"] = date("c");
  $last_write_data_json["no"] = $no;
  $last_write_data_json["part"] = $part;
  $last_write_data_json["all"] = false;
  $last_write_data_str = json_encode($last_write_data_json);

  // ������ ����
  unlink($last_write_file_name);
  if (!file_put_contents($last_write_file_name, $last_write_data_str)) {
    header("HTTP/1.1 520 Unknown Error");
    print('{"error": "Upload file error: file_put_contents, last.write"}');
    return;
  }
}



//
print('{"result": "ok"}');



?>