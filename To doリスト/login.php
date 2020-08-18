<?php
//データ接続
require_once("function/dataconnect.php");
$pdo=dbConnect();

if(!empty($_POST["mail"]) and !empty($_POST["password"])){
  session_start();
  $mail=$_POST["mail"];
  $sql="SELECT*FROM users where mail=:mail";
  $stmt=$pdo->prepare($sql);
  $stmt->bindValue(":mail",$mail);
  $stmt->execute();
  $member=$stmt->fetch();
  //指定したハッシュがパスワードにマッチしているかをチェック
  if(password_verify($_POST["password"],$member['password'])){
    $_SESSION['id']=$member["id"];
    $_SESSION["userName"]=$member["userName"];
    //マイページに移動
    http_response_code(301);
    header("Location:mypage.php");
  }else{
    echo "メールアドレスもしくはパスワードが間違っています<br>";
  }
}else{
  echo "下記フォームに全て入力してください<br>";
}
?>









<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<!-- CSSファイルの読み込み -->
<link rel="stylesheet" href="style.css"> 
<title>ログイン画面</title>
</head>
<body>
<header>
  <h1>ようこそ、ログインしてください</h1>
</header>
<main>
  <form action="#" method="post" class="postForm">
    <fieldset class="post new">
      <legend>ログインしてください</legend>
      <p><label for='mail'>メールアドレス</label><br> <!-- ラベル付けするとクリックしてフォームに飛べる -->
      <input type="text" name="mail" id='mail'></p>
      <p><label for='password'>パスワード</label><br>
      <input type="text" name="password" id='password'></p>
      <button type="submit">ログイン</button>
    </fieldset>
  </form>
  <a href="https://tb-220191.tech-base.net/newRegister/registration_mail_form.php">新規登録はこちら</a>
</main>
<footer>
</footer>
</body>
</html>