<?php

//
$token_cooke = $_COOKIE["token"];
$arr = explode(":", $token_cooke);
$pass_token = $arr[0];

//
$pass_md5_requred = file_get_contents("web_pass/pass_md5.txt");
$pass_token_pass_md5_requred = md5($pass_token.$pass_md5_requred);
$token_cooke_requred = $pass_token.":".$pass_token_pass_md5_requred;

?>