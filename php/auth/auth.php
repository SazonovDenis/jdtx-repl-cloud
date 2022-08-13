<?php
// =================================
// �������� �� �����������.
// ����������� �������: isAuth, login, logout
// �������������� ������ ��������� ��������, ������������� � ������ cookie.
// =================================



const file_pass_md5 = "../auth/pass_md5.txt";
const file_pass_token = "../auth/pass_token.txt";


function isAuth() {
    //
    $token_cooke = $_COOKIE["token"];

    //
    $token_cooke_requred = getTokenRequred();

    //
    if (is_null($token_cooke_requred) || $token_cooke == $token_cooke_requred) {
        // ����� ���������� (��� ������ � file_pass_md5 �� �����)
        return true;
    } else {
        return false;
    }
}


function getTokenRequred() {
    $token_cooke = $_COOKIE["token"];

    // ������ �����?
    $pass_md5 = strtolower(file_get_contents(file_pass_md5));
    if ($pass_md5 !== false && $pass_md5 != "") {
      // ������ �����
      $arr = explode(":", $token_cooke);
      $pass_token = $arr[0];
      //
      $token_cooke_requred = $pass_token.":".strtolower(md5($pass_token.$pass_md5));
    } else {
      // ������ �� �����
      $token_cooke_requred = null;
    }

    //
    return $token_cooke_requred;
}


function login($password) {
    $pass_md5 = md5($password);
    $pass_md5_requred = file_get_contents(file_pass_md5);
    if ($pass_md5 == $pass_md5_requred) {
        $pass_token = file_get_contents(file_pass_token);
        $token_cooke = $pass_token.":".md5($pass_token.$pass_md5);
        //
        setcookie("token", $token_cooke);
        //
        return $token_cooke;
    }

    //
    return false;
}


function logout() {
    setcookie("token", "");
}


?>