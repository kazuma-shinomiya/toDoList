<?php
//DB接続
require_once("../function/dataconnect.php");
$pdo=dbConnect();
require_once("../function/tablemaker.php");
$sql=tableMaker2();
$sql=tableMaker3();



 
 
//エラーメッセージの初期化
$errors = array();
 
if(!empty($_POST)) {
  //POSTされたデータを変数に入れる
  global$mail;//変数をどこでも使えるようにする
	$mail = $_POST['mail'];
	
	//メール入力判定
	if ($mail == ''){
		$errors['mail'] = "メールが入力されていません。";
	}else{
		if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $mail)){
			$errors['mail_check'] = "メールアドレスの形式が正しくありません。";
    }
    //メールアドレスが既に使われていないか確認
    $sql="SELECT mail FROM users where mail=:mail";
    $stmt=$pdo->prepare($sql);
    $stmt->bindValue(":mail",$mail);
    $stmt->execute();
    $result=$stmt->fetch();
    if($result['mail']===$mail){
      $errors['user_check'] = "このメールアドレスはすでに利用されております。";
    }
	}
  if (count($errors) === 0){
    
    $urltoken = hash('sha256',uniqid(rand(),1));
    global$url;
    $url = "https://tb-220191.tech-base.net/newRegister/registration_form.php"."?urltoken=".$urltoken;
    
    //ここでデータベースに登録する
    try{
      //例外処理を投げる（スロー）ようにする
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      //メールアドレスとurlトークンをデータベースに登録
      $sql="INSERT INTO pre_users (urltoken,mail,date) VALUES (:urltoken,:mail,now() )";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
      $stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
      $stmt->execute();

      require_once('../phpmailer/send_test.php');
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
<title>メール登録画面</title>
<meta charset="utf-8">
</head>
<body>
<h1>メール登録画面</h1>
  
<form method="post">
  
<p>メールアドレス：<input type="text" name="mail" size="50"></p>
  
<input type="hidden" name="token" value="<?=$token?>">
<input type="submit" value="登録する">

</form>

<?php if(count($errors) > 0): ?>
 
 <?php
 foreach($errors as $value){
   echo "<p>".$value."</p>";
 }
 ?>
  
 <input type="button" value="戻る" onClick="history.back()">
  
 <?php endif; ?>
  
  
</body>
</html>
