<?php


function delete_file_by_no($path_box, $guid, $box, $no) {
  // "15" -> "000000015"
  $file_no = str_pad($no, 9, "0", STR_PAD_LEFT);
  $file_name = $path_box."/".$file_no;



  // Удаляем .info
  $file_name_info = $file_name.".info";

  //
  if (file_exists($file_name_info) && !unlink($file_name_info)) {
    header("HTTP/1.1 520 Unknown Error");
    print('{"error": "Error delete .info file in ['.$guid.'], box ['.$box.'], no ['.$no.']"}');
    return false;
  }



  // Удаляем файлы данных
  $file_mask = $file_name.".???";
  $filelist = glob($file_mask);

  //
  foreach ($filelist as $file) {
    if (file_exists($file) && !unlink($file)) {
      header("HTTP/1.1 520 Unknown Error");
      print('{"error": "Error delete file ['.$file.'] in ['.$guid.'], box ['.$box.'], no ['.$no.']"}');
      return false;
    }  
  }


  //
  return true;
}


?>