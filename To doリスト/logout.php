<?php
require_once("function/dataconnect.php");
$pdo=dbConnect();

session_start();
//セッションの中身を全て削除
$_SESSION=array();
//セッションを破壊
session_destroy();

//ログイン画面に戻る
http_response_code(301);
header("Location:login.php");
?>
