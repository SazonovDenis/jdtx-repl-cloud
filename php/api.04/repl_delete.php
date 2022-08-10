<?php
// =================================
// jdtx.repl.main.api.IJdxMailer#delete
// =================================

error_reporting(0);
include "param_version.php";
include "content_root.php";
include "param_guid_box.php";
include "param_no.php";
include "check_guid.php";
include "check_box.php";
include "delete_file_by_no.php";



// Удаляем файлы до номера $no
$no_int = intval($no, 10);

// Нужно ли удалять всё, до заказанного номера $no или только $no
$all = $_GET["all"];



// Список файлов на удаление
$count = 0;
$file_mask = $path_box."/?????????.*";
$filelist = glob($file_mask);



// Улаляем
foreach ($filelist as $file) {
  // 000027693.000 - > 000027693
  // 000027693.info - > 000027693
  $no_file = substr($file, strlen($path_box) + 1, 9);

  // Если имя файла не состоит из 9 цифр - не трогаем его
  if (!is_numeric($no_file)) {
    continue;
  }

  //
  $no_file_int = intval($no_file, 10);

  // Удаляем файлы до заказанного номера $no включительно, если запрошено $all,
  // удаляем файлы равные заказанному номеру $no, если не запрошено $all
  if (($no_file_int <= $no_int && $all) || ($no_file_int == $no_int)) {
    if (!delete_file_by_no($path_box, $guid, $box, $no_file)) {
      // функция delete_file_by_no() уже выдала http-заголовок с ошибкой 5**
      return;
    }
    //
    $count = $count + 1;
  }
}



// Результат ok
print('{"result": "ok", "deleted": '.$count.'}');


?>

