<?php
// =================================
// jdtx.repl.main.api.UtMailerHttpManager#createGuid
// =================================

error_reporting(0);
include "content_root.php";
include "param_version.php";
include "param_guid_post.php";
include "check_content_root.php";
include "../auth/auth.php";



//
if (!isAuth()) {
  header("HTTP/1.1 401 Authorisation failed");

  //$token_cooke = $_COOKIE["token"];
  //$token_cooke_requred = getTokenRequred();
  //print('{"error": "Authorisation failed, guid ['.$guid.'], token_cooke ['.$token_cooke.'], token_cooke_requred ['.$token_cooke_requred.']"}');

  print('{"error": "Authorisation failed, guid ['.$guid.']"}');
  return;
}



//
$path_guid = $content_root.$guid;

// Проверка, что guid нет
if (file_exists($path_guid)) {
  header("HTTP/1.1 520 Unknown Error");
  print('{"error": "Guid already exists, guid ['.$guid.']"}');
  return;
}



// Создание Guid
if (!mkdir($path_guid, 0777, true)) {
  header("HTTP/1.1 520 Unknown Error");
  print('{"error": "Error create guid ['.$guid.']"}');
  return;
}



// Результат ok
print('{"result": "ok"}');


?>

