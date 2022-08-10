<?php
// =================================
// jdtx.repl.main.api.IJdxMailer#getReplicaInfo
// =================================

error_reporting(0);
include "param_version.php";
include "content_root.php";
include "param_guid_box.php";
include "param_no.php";
include "check_guid.php";
include "check_box.php";


// "15" -> "000000015"
$file_no = str_pad($no, 9, "0", STR_PAD_LEFT);


// ����� �� ���������
$part_max_no = -1;
$total_bytes = 0;
$file_info = "{}";


// ���� ������ ���� ��������� �� ��������� - 
// ����� ���������� ��� ����� ������ (����� ���� "000000015.000", "000000015.001" � �.�.)
$file_mask = $path_box."/".$file_no.".???";
$filelist = glob($file_mask);


// ����� �� �������
if (count($filelist) == 0) {
  // ��������� error
  print('{"result": "error", "error": "Replica not found, guid ['.$guid.'], box ['.$box.'], no ['.$no.']", "part_info": {"part_max_no": '.$part_max_no.', "total_bytes": '.$total_bytes.'}, "file_info": '.$file_info.'}');
  return;
}


// �������� ��������� ����� ($part_max) � ����� ������ ($total_bytes)
foreach ($filelist as $file_name_no) {
  $s = substr($file_name_no, strlen($path_box)+1+9+1, 3);
  $no = intval($s);
  if ($no > $part_max_no) {
    $part_max_no = $no;
  }
  //
  $total_bytes = $total_bytes + filesize($file_name_no);
}



// ���� ������ ���� ��������� ��������� - 
// ������������� ���� ������ �� ����� .info (�������� "000000015.info")
$file_name_info = $path_box."/".$file_no.".info";
if (file_exists($file_name_info)) {
  $file_info = file_get_contents($file_name_info);
}



// ��������� ok
$result='{"result": "ok", "part_info": {"part_max_no": '.$part_max_no.', "total_bytes": '.$total_bytes.'}, "file_info": '.$file_info.'}';
header("Content-Length: ".strlen($result));
print($result);


?>
