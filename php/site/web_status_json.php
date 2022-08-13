<?php
// =================================
// Статус репликации, json
// =================================

//
error_reporting(0);
include "../api.04/content_root.php";
include "../auth/auth.php";


// =================================
// Список только для одного клиента - не нужна авторизация

$guid = $_GET["guid"];

// Чтение
if ($guid != null && $guid != undefined) {
  $guid_root = $content_root.$guid;

  //
  if (!file_exists($guid_root)) {
    print('{"success": false, "errors": [{"text": "Guid root not found, guid '.$guid.'"}]}');
    exit();
  }

  //
  $company_res = [];
  $company_res["company_name"] = $guid;
  $company_res["ws_list"] = get_company_ws_list($guid_root);
  //
  $res = [];
  $res[0] = $company_res;


  //
  $dt = strftime("%Y.%m.%d %H:%M:%S", time());
  $res["dt"] = $dt;

  //
  print(json_encode($res));
  exit();
}



// =================================
// Список для всех клиентов - нужна авторизация

//
if (!isAuth()) {
  $res = [];
  $res["success"] = false;
  $error = [];
  $error["text"] = "Authorisation failed";
  $error["code"] = 401;
  $res["errors"] = [$error];
  echo(json_encode($res));
  exit;
}


// Чтение

//
$res = [];
$res = array_merge($res, get_all_companies($content_root));

//
$dt = strftime("%Y.%m.%d %H:%M:%S", time());
$res["dt"] = $dt;

//
echo(json_encode($res));



// =================================
// Методы

// Организации
function get_all_companies($dir)
{
    $res = array();
    $res_count = 0;

    //
    //$dirlist = glob($dir . "/????????????????.*", GLOB_ONLYDIR);
    $dirlist = glob($dir . "/*", GLOB_ONLYDIR);
    foreach ($dirlist as $dir_name_company) {
        $company_res = [];
        $guid = substr($dir_name_company, strlen($dir)+1);
        $company_res["company_name"] = $guid;
        $company_res["ws_list"] = get_company_ws_list($dir_name_company);

        //
        $res[$res_count] = $company_res;
        //
        $res_count = $res_count + 1;
    }

    //
    return $res;
}


// Станции для организации
function get_company_ws_list($dir_name_company)
{
    $res_ws_list = [];


    //
    if (file_exists($dir_name_company."/ws_list.json")) {
      $ws_list_info_s = file_get_contents($dir_name_company."/ws_list.json");
    } else {
      $ws_list_info_s = "[]";
    }
    $ws_list_info = json_decode($ws_list_info_s, true);

    //
    $res_count = 0;
    $dirlist = glob($dir_name_company . "/???*");
    foreach ($dirlist as $dir_name_ws) {
        if (!is_dir($dir_name_ws)) {
            continue;
        }
        
        $ws_res = get_ws($dir_name_ws);

        //
        $dir_guid = substr($dir_name_ws, strlen($dir_name_company) + 1, 255);

        // Найдем $ws_info по $dir_guid
        $ws_info = null;
        $i = 0;
        while ($i < count($ws_list_info)) {
            substr($dir_name_ws, strlen($dir_name_company) + 1, 255);
            if ($ws_list_info[$i]["guid"] == $dir_guid) {
                $ws_info = $ws_list_info[$i];
                break;
            }
            $i = $i + 1;
        }

        //
        $ws_res["ws_name"] = $dir_guid;
        if ($ws_info <> null) {
            $ws_res["hide"] = $ws_info["hide"];
	    $ws_res["ws_title"] = $ws_info["title"];
	} else {
	    $ws_res["ws_title"] = "";
	}

        //
        $res_ws_list[$res_count] = $ws_res;
        //
        $res_count = $res_count + 1;
    }

    //
    return $res_ws_list;
}


function get_ws($dir_name_ws)
{
    $res_ws = [];

    // Версии приложения берем из to/ping.read или from/ping.write
    if (file_exists($dir_name_ws . "/from/ping.write")) {
        $last_ping_s = file_get_contents($dir_name_ws . "/from/ping.write");
        $last_ping_json = json_decode($last_ping_s, true);
        $res_ws["protocol_version"] = $last_ping_json["protocolVersion"];
        $res_ws["app_version"] = $last_ping_json["appVersion"];
    } else if (file_exists($dir_name_ws . "/to/ping.read")) {
        $last_ping_s = file_get_contents($dir_name_ws . "/to/ping.read");
        $last_ping_json = json_decode($last_ping_s, true);
        $res_ws["protocol_version"] = $last_ping_json["protocolVersion"];
        $res_ws["app_version"] = $last_ping_json["appVersion"];
    }


    // ws.info
    if (file_exists($dir_name_ws . "/ws.info")) {
        $ws_info_s = file_get_contents($dir_name_ws . "/ws.info");
        $ws_info_s_json = json_decode($ws_info_s, true);
        $res_ws["out_queAvailable"] = $ws_info_s_json["out_queAvailable"];
        $res_ws["out_sendDone"] = $ws_info_s_json["out_sendDone"];
        $res_ws["in_queInNoAvailable"] = $ws_info_s_json["in_queInNoAvailable"];
        $res_ws["in_queInNoDone"] = $ws_info_s_json["in_queInNoDone"];
        $res_ws["databaseInfo"] = $ws_info_s_json["databaseInfo"];
        $res_ws["isMute"] = $ws_info_s_json["isMute"];
    }

    // ws.errors
    if (file_exists($dir_name_ws . "/ws.errors")) {
        $errors_str = file_get_contents($dir_name_ws . "/ws.errors");
        $errors_json = json_decode($errors_str, true);
        $errors_json["dt"] = strftime("%Y.%m.%d %H:%M:%S", strtotime($errors_json["dt"]));
        $res_ws["errors"] = $errors_json;
    }

    // ws.errors.mailRequest
    if (file_exists($dir_name_ws . "/ws.errors.mailRequest")) {
        $errors_str = file_get_contents($dir_name_ws . "/ws.errors.mailRequest");
        $errors_json = json_decode($errors_str, true);
        $errors_json["dt"] = strftime("%Y.%m.%d %H:%M:%S", strtotime($errors_json["dt"]));
        $res_ws["errors_mailRequest"] = $errors_json;
    }

    // srv.errors
    if (file_exists($dir_name_ws . "/srv.errors")) {
        $errors_str = file_get_contents($dir_name_ws . "/srv.errors");
        $errors_json = json_decode($errors_str, true);
        $errors_json["dt"] = strftime("%Y.%m.%d %H:%M:%S", strtotime($errors_json["dt"]));
        $res_ws["errors_srv"] = $errors_json;
    }

    // srv.errors.mail
    if (file_exists($dir_name_ws . "/srv.errors.mail")) {
        $errors_str = file_get_contents($dir_name_ws . "/srv.errors.mail");
        $errors_json = json_decode($errors_str, true);
        $errors_json["dt"] = strftime("%Y.%m.%d %H:%M:%S", strtotime($errors_json["dt"]));
        $res_ws["errors_srv_mail"] = $errors_json;
    }

    // srv.errors.mailRequest
    if (file_exists($dir_name_ws . "/srv.errors.mailRequest")) {
        $errors_str = file_get_contents($dir_name_ws . "/srv.errors.mailRequest");
        $errors_json = json_decode($errors_str, true);
        $errors_json["dt"] = strftime("%Y.%m.%d %H:%M:%S", strtotime($errors_json["dt"]));
        $res_ws["errors_srv_mailRequest"] = $errors_json;
    }

    // log.log
    if (file_exists($dir_name_ws . "/log.log")) {
        $errors_str = file_get_contents($dir_name_ws . "/log.log");
        $errors_json = json_decode($errors_str, true);
        //$errors_json["dt"] = strftime("%Y.%m.%d %H:%M:%S", strtotime($errors_json["dt"]));
        $res_ws["log_log"] = $errors_json;
    }

    // repair.info    
    if (file_exists($dir_name_ws . "/repair.info")) {
        $repair_info_str = file_get_contents($dir_name_ws . "/repair.info");
        $repair_info_json = json_decode($repair_info_str, true);
        $repair_info_json["dt"] = strftime("%Y.%m.%d %H:%M:%S", strtotime($repair_info_json["dt"]));
        $res_ws["repair_info"] = $repair_info_json;
    }


    // Ящики
    $boxes = ["from", "to", "to001"];
    foreach ($boxes as $box) {
        $res_ws[$box] = get_box($dir_name_ws . "/" . $box);
    }

    //
    return $res_ws;
}


function get_box($dir_name_box)
{
    $res_box = [];

    //
    $filelist = glob($dir_name_box . "/*.???");
    //
    $size = 0;
    $no_max = 0;
    $no_min = 999999999;
    foreach ($filelist as $file_name) {
        //
        $size = $size + filesize($file_name);
        //
        $s = substr($file_name, strlen($dir_name_box) + 1, 9);
        $no = intval($s);
        if ($no > $no_max) {
            $no_max = $no;
        }
        if ($no < $no_min) {
            $no_min = $no;
        }
    }
    //
    $res_box["file_count"] = count($filelist);
    $res_box["file_size"] = $size;
    if (count($filelist) != 0) {
        $res_box["file_no_min"] = $no_min;
        $res_box["file_no_max"] = $no_max;
    }

    //
    date_default_timezone_set('Asia/Almaty');

    //
    if (file_exists($dir_name_box . "/last.dat.info")) {
        $last_info = file_get_contents($dir_name_box . "/last.dat.info");
        $last_info_json = json_decode($last_info, true);
        $res_box["last_no"] = $last_no = $last_info_json["no"];
        $res_box["last_age"] = $last_no = $last_info_json["age"];
        $last_dtTo_s = $last_info_json["dtTo"];
        if ($last_dtTo_s != null) {
            $res_box["last_dtTo"] = strftime("%Y.%m.%d %H:%M:%S", strtotime($last_dtTo_s));
            $res_box["data_lag_sec"] = time() - strtotime($last_dtTo_s);
        }
    }

    //
    if (file_exists($dir_name_box . "/ping.write")) {
        $last_ping_write_s = file_get_contents($dir_name_box . "/ping.write");
        $last_ping_write_json = json_decode($last_ping_write_s, true);
        $last_ping_write_dt_s = $last_ping_write_json["dt"];
        $res_box["last_ping_write_dt"] = strftime("%Y.%m.%d %H:%M:%S", strtotime($last_ping_write_dt_s));
        $res_box["repl_write_lag_sec"] = time() - strtotime($last_ping_write_dt_s);
    }

    //
    if (file_exists($dir_name_box . "/ping.read")) {
        $last_ping_read_s = file_get_contents($dir_name_box . "/ping.read");
        $last_ping_read_json = json_decode($last_ping_read_s, true);
        $last_ping_read_dt_s = $last_ping_read_json["dt"];
        $res_box["last_ping_read_dt"] = strftime("%Y.%m.%d %H:%M:%S", strtotime($last_ping_read_dt_s));
        $res_box["repl_read_lag_sec"] = time() - strtotime($last_ping_read_dt_s);
    }


    // last.read
    if (file_exists($dir_name_box . "/last.read")) {
        $last_read_s = file_get_contents($dir_name_box . "/last.read");
        $last_read_json = json_decode($last_read_s, true);
        $res_box["last_read"] = $last_read_json;
        $dt_s = $res_box["last_read"]["dt"];
        $res_box["last_read"]["dt"] = strftime("%Y.%m.%d %H:%M:%S", strtotime($dt_s));
        $res_box["last_read"]["dt_lag_sec"] = time() - strtotime($dt_s);
        $res_box["last_read"]["no"] = intval($res_box["last_read"]["no"]);
        $res_box["last_read"]["part"] = intval($res_box["last_read"]["part"]);
    }

    // last.write
    if (file_exists($dir_name_box . "/last.write")) {
        $last_write_s = file_get_contents($dir_name_box . "/last.write");
        $last_write_json = json_decode($last_write_s, true);
        $res_box["last_write"] = $last_write_json;
        $dt_s = $res_box["last_write"]["dt"];
        $res_box["last_write"]["dt"] = strftime("%Y.%m.%d %H:%M:%S", strtotime($dt_s));
        $res_box["last_write"]["dt_lag_sec"] = time() - strtotime($dt_s);
        $res_box["last_write"]["no"] = intval($res_box["last_write"]["no"]);
        $res_box["last_write"]["part"] = intval($res_box["last_write"]["part"]);
    }

    // required.info
    if (file_exists($dir_name_box . "/required.info")) {
        $required_info_s = file_get_contents($dir_name_box . "/required.info");
        $required_info_json = json_decode($required_info_s, true);
        $res_box["required_info"] = $required_info_json;
        $res_box["required_info"]["requiredFrom"] = intval($res_box["required_info"]["requiredFrom"]);
        $res_box["required_info"]["requiredTo"] = intval($res_box["required_info"]["requiredTo"]);
        if ($res_box["required_info"]["requiredFrom"] == 0) {
            $res_box["required_info"]["requiredFrom"] = -1;
        }
        if ($res_box["required_info"]["requiredTo"] == 0) {
            $res_box["required_info"]["requiredTo"] = -1;
        }
    }
    
    //
    return $res_box;
}


?>