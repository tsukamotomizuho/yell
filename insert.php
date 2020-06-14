<?php
//1. POST受信
$user    = $_POST["user"];
$yell     = $_POST["yell"];
//$book_comment = $_POST["book_comment"];

//2. DB接続
try {
  $pdo = new PDO('mysql:dbname=yell;charset=utf8;host=localhost','root','');
} catch (PDOException $e) {
  exit('DbConnectError:'.$e->getyell());
}
//xampはid:root,passは空


//３．SQLを作成(stmlの中で)
$stmt = $pdo->prepare("INSERT INTO yell_table(id, user, yell,
create_date )VALUES(NULL, :user, :yell, sysdate())");
$stmt->bindValue(':user', $user, PDO::PARAM_STR); 
$stmt->bindValue(':yell', $yell, PDO::PARAM_STR);
$status = $stmt->execute();
//実行後、エラーだったらfalseが返る
//PDO::PARAM_STR 文字列なら追加(セキュリティ向上)
//数値の場合はPDO::PARAM_INT

//４．エラー表示
if($status==false){
  $error = $stmt->errorInfo();
  exit("QueryError:".$error[2]);//エラー表示
  
}else{//処理が終われば『index.php』に戻る。
  header("Location: index.php");//スペース必須
  exit;//おまじない

}
?>
