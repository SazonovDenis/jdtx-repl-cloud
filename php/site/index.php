<?php 
// =================================
// HTML страница.
// Авторизация
// =================================
error_reporting(0);


//
$guid = $_GET["guid"];
//
if ($guid != null && $guid != undefined) {
  // Список только для одного клиента - не нужна авторизация
  setcookie("token", $token_cooke);
  header("Location: web_status_all.html?guid=".$guid);
  exit;
} else {

?>
<head>
    <link href="css/css.css" rel="stylesheet">
    <link rel="shortcut icon" href="images/icon.png">


    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
    <title>Мониторинг репликации Jadatex.Sync</title>
</head>


<html>


<form id="data">

    <div class="jadatex-flex-container_login">
        <div class="jadatex-flex-block_login">
            <img src="images/logo_200.png">
        </div>
    </div>

    <div class="jadatex-flex-container_login">


        <div class="jadatex-flex-block_login">
            <input type="password" name="pass">
        </div>

        <div class="jadatex-flex-block_login">
            <button class="button"
                    type="submit"
                    form="data"
                    formmethod="post"
                    formaction="web_login.php">
                Вход
            </button>
        </div>

    </div>

</form>

</html>

<?php 
}
?>
