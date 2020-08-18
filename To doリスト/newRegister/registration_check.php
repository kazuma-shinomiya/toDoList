<?php
session_start();


 
//前後にある半角全角スペースを削除する関数
function spaceTrim ($str) {
	// 行頭
	$str = preg_replace('/^[ 　]+/u', '', $str);
	// 末尾
	$str = preg_replace('/[ 　]+$/u', '', $str);
	return $str;
}
 
//エラーメッセージの初期化
$errors = array();
 
if(empty($_POST)) {
	header("Location: registration_mail_form.php");
	exit();
}else{
	//POSTされたデータを各変数に入れる
	$userName = $_POST['userName'];
	$password = $_POST['password'];
	
	//前後にある半角全角スペースを削除
	$userName = spaceTrim($userName);
	$password = spaceTrim($password);
 
	//アカウント入力判定
	if ($userName == ''):
		$errors['userName'] = "アカウントが入力されていません。";
	elseif(mb_strlen($userName)>10):
		$errors['userName_length'] = "アカウントは10文字以内で入力して下さい。";
	endif;
	
	//パスワード入力判定
	if ($password == ''):
		$errors['password'] = "パスワードが入力されていません。";
	elseif(!preg_match('/^[0-9a-zA-Z]{5,30}$/', $_POST["password"])):
		$errors['password_length'] = "パスワードは半角英数字の5文字以上30文字以下で入力して下さい。";
	else:
		$password_hide = str_repeat('*', strlen($password));
	endif;
	
}
 
//エラーが無ければセッションに登録
if(count($errors) === 0){
	$_SESSION['userName'] = $userName;
	$_SESSION['password'] = $password;
}
 
?>
 
<!DOCTYPE html>
<html>
<head>
<title>会員登録確認画面</title>
<meta charset="utf-8">
</head>
<body>
<h1>会員登録確認画面</h1>
 
<?php if (count($errors) === 0): ?>
 
 
<form action="registration_insert.php" method="post">
 
<p>メールアドレス：<?=htmlspecialchars($_SESSION['mail'], ENT_QUOTES)?></p>
<p>ユーザーネーム：<?=htmlspecialchars($userName, ENT_QUOTES)?></p>
<p>パスワード：<?=$password_hide?></p>
 
<input type="button" value="戻る" onClick="history.back()">
<input type="hidden" name="token" value="<?=$_POST['token']?>">
<input type="submit" value="登録する">
 
</form>
 
<?php elseif(count($errors) > 0): ?>
 
<?php
foreach($errors as $value){
	echo "<p>".$value."</p>";
}
?>
 
<input type="button" value="戻る" onClick="history.back()">
 
<?php endif; ?>
 
</body>
</html>