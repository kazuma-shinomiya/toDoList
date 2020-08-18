<?php
//データ接続
require_once("function/dataconnect.php");
$pdo=dbConnect();



session_start();
$userName=$_SESSION["userName"];
if(!empty($_SESSION["id"])){
  //ログインしているとき
  $msg= "こんにちは".$userName."さん";
  $link='<a href="logout.php">ログアウト</a>';
}else{
  $msg="ログインしていません";
  $link='<a href="signUp.php">ログイン</a>';
}
echo $msg;
echo $link; 

//テーブル作成
require_once("function/tablemaker.php");
$tbl=tableMaker();

// //テーブル表示
// $sql ='SHOW CREATE TABLE toDoList';
// 	$result = $pdo -> query($sql);
// 	foreach ($result as $row){
// 		echo $row[1];
// 	}
//   echo "<hr>";
  
//データの追加
if(isset($_POST["listContents"])){
  $date=date('Y/m/d H:i:s');
  $listContents=$_POST['listContents'];
  $urgency=$_POST['urgency'];
  $importance=$_POST['importance'];
  //データを入力
  $sql = $pdo -> prepare("INSERT INTO toDoList (date,listContents,urgency,importance) VALUES (:date, :listContents,:urgency,:importance)");
  $sql -> bindParam(':date', $date, PDO::PARAM_STR);
  $sql -> bindParam(':listContents', $listContents, PDO::PARAM_STR);
  $sql -> bindParam(':urgency', $urgency, PDO::PARAM_STR);
  $sql -> bindParam(':importance', $importance, PDO::PARAM_STR);
  $sql -> execute();
  echo 'データを追加しました<br>';
  header('Location:https://tb-220191.tech-base.net/mypage.php');
    //二重投稿防止
}

//データの削除
if(!empty($_POST['delete'])){
  $id=$_POST['delete'];
  $sql="DELETE FROM toDoList where id=:id";
  $stmt=$pdo->prepare($sql);
  $stmt->bindValue(':id',$id,PDO::PARAM_INT);
  $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<!-- CSSファイルの読み込み -->
<link rel="stylesheet" href="style.css"> 
<title>toDoList</title>
</head>
<body>
<header>
  <h1>To Do List</h1>
</header>
<main>
  <section>
      <?php
        //データの表示
      $sql = 'SELECT * FROM toDoList';
      $stmt = $pdo->query($sql);
      $results = $stmt->fetchAll();
      foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['date'].'&nbsp;&nbsp;&nbsp;&nbsp;';
        echo $row['listContents'].'&nbsp;&nbsp;&nbsp;&nbsp;';
        echo "緊急度：".$row['urgency'].'&nbsp;&nbsp;&nbsp;&nbsp;';
        echo "重要度：".$row['importance'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        $id=$row['id'];
        echo <<<EOM
          <form action='#' method='post'>
            <input type='hidden' name='delete' value=$id>
            <input type='submit'  value='削除'>
          </form><br>
        EOM;
        echo "<hr>";
      }
      ?>
  </section>
  <div id="open" class="button"> <!-- モーダル画面を開くボタン -->
    +
  </div>
  <div id="mask" class="hidden"><!-- グレーの透過マスク-->
  </div>
  <div id="modal" class="hidden"><!-- モーダル画面-->
  <form action="#" method="post" class="postForm">
    <fieldset class="post new">
      <legend>新規リスト追加</legend>
      <p><label for='listContents'>タスク名：</label><br> <!-- ラベル付けするとクリックしてフォームに飛べる -->
      <input type="text" name="listContents" id='listContents'></p>
      <p><label for='urgency'>緊急度</label><br>
      <select name="urgency" id="urgency">
        <option value="★★★">高</option>
        <option value="★★">中</option>
        <option value="★">低</option>
      </select>
      <p><label for='importance'>重要度</label><br>
      <select name="importance" id="importance">
        <option value="★★★">高</option>
        <option value="★★">中</option>
        <option value="★">低</option>
      </select>
      <p><input type="submit" value="追加"></p>
    </fieldset>
  </form>
    <div id="close" class="button"><!--モーダル画面を閉じるボタン -->
     -
    <div>
  </div>
  <script src="function/main.js"></script>

</main>
<footer>
  <div class="page-top">
    <a href="#">TOPへもどる</a>
  </div>
</footer>
</body>
</html>
