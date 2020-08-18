<?php
session_start();
//データベースの接続
require_once("../function/dataconnect.php");
$pdo=dbConnect();
//テーブルの作成
require_once("../function/tablemaker.php");
$tbl=tablemaker2(); 

 


 
//エラーメッセージの初期化
$errors = array();
 
if(empty($_POST)) {
	header("Location: registration_mail_form.php");
	exit();
}
 
$mail = $_SESSION['mail'];
$userName = $_SESSION['userName'];
 
//パスワードのハッシュ化
$password_hash =  password_hash($_SESSION['password'], PASSWORD_DEFAULT);
 
//ここでデータベースに登録する
try{
	//例外処理を投げる（スロー）ようにする
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	//トランザクション開始
	$pdo->beginTransaction();
	
  //usersテーブルに本登録する
  $sql="INSERT INTO users (userName,mail,password) VALUES (:userName,:mail,:password_hash)";
	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':userName', $userName, PDO::PARAM_STR);
	$stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
	$stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
	$stmt->execute();
		
  //pre_usersのflagを1にする
  $sql="UPDATE pre_users SET flag=1 WHERE mail=(:mail)";
	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
	$stmt->execute();
	
	// トランザクション完了（コミット）
	$pdo->commit();
		
	//セッション変数を全て解除
	$_SESSION = array();
	
	//セッションクッキーの削除・sessionidとの関係を探れ。つまりはじめのsesssionidを名前でやる
	if (isset($_COOKIE["PHPSESSID"])) {
    		setcookie("PHPSESSID", '', time() - 1800, '/');
	}
	
 	//セッションを破棄する
 	session_destroy();
 	
 	/*
 	登録完了のメールを送信
 	*/
	
}catch (PDOException $e){
	//トランザクション取り消し（ロールバック）
	$dbh->rollBack();
	$errors['error'] = "もう一度やりなおして下さい。";
	print('Error:'.$e->getMessage());
}
 
?>
 
<!DOCTYPE html>
<html>
<head>
<title>会員登録完了画面</title>
<meta charset="utf-8">
</head>
<body>
 
<?php if (count($errors) === 0): ?>
<h1>会員登録完了画面</h1>
 
<p>登録完了いたしました。ログイン画面からどうぞ。</p>
<p><a href="https://tb-220191.tech-base.net/login.php">ログイン画面へ</a></p>
 
<?php elseif(count($errors) > 0): ?>
 
<?php
foreach($errors as $value){
	echo "<p>".$value."</p>";
}
?>
 
<?php endif; ?>
 
</body>
</html>