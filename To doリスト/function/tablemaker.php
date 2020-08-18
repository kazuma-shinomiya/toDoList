<?php
//テーブル作成のSQLを作成
function tableMaker(){
  require_once("dataconnect.php");
  $pdo=dbConnect();
  try{
    $sql = "CREATE TABLE IF NOT EXISTS toDoList" 
            ."("
            . "id INT AUTO_INCREMENT PRIMARY KEY,"
            . "date DATETIME,"
            . "listContents TEXT,"
            . "urgency TEXT,"
            . "importance TEXT"
            .");";        
    //SQLを実行
    $stmt=$pdo->query($sql);
  }catch(PDOException $e){
    echo "テーブル作成失敗<br>".$e->getMessage().'<br>';
    exit();//処理を終了
  }
}


function tableMaker2(){
  require_once("dataconnect.php");
  $pdo=dbConnect();
  try{
    $sql = "CREATE TABLE IF NOT EXISTS users" 
            ."("
            . "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
            . "userName TEXT,"
            . "mail varchar(50) NOT NULL," //uniqueをつけると重複がないか検索してくれる
            . "password varchar(255),"
            . "flag TINYINT(1) NOT NULL DEFAULT 1"//デフォルトで１が自動入力
            .");";        
    //SQLを実行
    $stmt=$pdo->query($sql);
  }catch(PDOException $e){
    echo "テーブル作成失敗<br>".$e->getMessage().'<br>';
    exit();//処理を終了
  }
}


function tableMaker3(){
  require_once("dataconnect.php");
  $pdo=dbConnect();
  try{
    $sql = "CREATE TABLE IF NOT EXISTS pre_users" 
            ."("
            . "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
            . "urltoken varchar(128),"
            . "mail varchar(50) NOT NULL," //uniqueをつけると重複がないか検索してくれる
            . "date DATETIME,"
            . "flag TINYINT(1) NOT NULL DEFAULT 0"//デフォルトで１が入力されて会員登録完了時に１に変わる
            .");";        
    //SQLを実行
    $stmt=$pdo->query($sql);
  }catch(PDOException $e){
    echo "テーブル作成失敗<br>".$e->getMessage().'<br>';
    exit();//処理を終了
  }
}
?>
