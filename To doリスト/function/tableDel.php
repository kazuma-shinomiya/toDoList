<?php
require_once("dataconnect.php");
$pdo=dbConnect();
$stmt=$pdo->query("DROP TABLE users");
?>
<?php
require_once("dataconnect.php");
$pdo=dbConnect();
$stmt=$pdo->query("DROP TABLE pre_users");
?>

