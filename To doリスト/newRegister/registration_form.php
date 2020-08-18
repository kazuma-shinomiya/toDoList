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
 
if(empty($_GET)) {
	header("Location: registration_mail_form.php");
	exit();
}else{
	//GETデータを変数に入れる
	$urltoken = $_GET["urltoken"];
	//メール入力判定
	if ($urltoken == ''){
		$errors['urltoken'] = "もう一度登録をやりなおして下さい。";
	}else{
		try{
			//flagが0の未登録者・仮登録日から24時間以内
			$sql="SELECT mail FROM pre_users WHERE urltoken=:urltoken AND flag =0 AND date > now() - interval 24 hour";
			$stmt = $pdo->prepare($sql);
			$stmt->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
			$stmt->execute();
			
			//レコード件数取得
			$row_count = $stmt->rowCount();
			
			//24時間以内に仮登録され、本登録されていないトークンの場合
			if( $row_count ==1){
				$mail_array = $stmt->fetch();
				$mail = $mail_array['mail'];
				$_SESSION['mail'] = $mail;
			}else{
				$errors['urltoken_timeover'] = "このURLはご利用できません。有効期限が過ぎた等の問題があります。もう一度登録をやりなおして下さい。";
			}
		}catch (PDOException $e){
			print('Error:'.$e->getMessage());
			die();
		}
	}
}
 
?>
 
<!DOCTYPE html>
<html>
<head>
<title>会員登録画面</title>
<meta charset="utf-8">
</head>
<body>
<h1>会員登録画面</h1>
 
<?php if (count($errors) === 0): ?>
 
<form action="registration_check.php" method="post">
<!-- actionは送信先 -->
 
<p>メールアドレス：<?=htmlspecialchars($mail, ENT_QUOTES, 'UTF-8')?></p>
<p>ユーザーネーム：<input type="text" name="userName"></p>
<p>パスワード：<input type="text" name="password"></p>
 
<input type="hidden" name="token" value="<?=$token?>">
<input type="submit" value="確認する">
 
</form>
 
<?php elseif(count($errors) > 0): ?>
 
<?php
foreach($errors as $value){
	echo "<p>".$value."</p>";
}
?>
 
<?php endif; ?>
 
</body>
</html>