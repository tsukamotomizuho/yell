<?php
$dataFile ='bbs.dat';

//CSRF対策

session_start();

function setToken(){
	$token = sha1(uniqid(mt_rand(), true));
	$_SESSION['token'] = $token;
}

function checkToken(){
	if(empty($_SESSION['token'])||$_SESSION['token'] != $_POST['token']){
		echo "不正なPOSTが行われました！";
		exit;
	}
}

function h($s){
	return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}


//$posts_list = json_encode( $posts , JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);


//2. DB接続
try {
  $pdo = new PDO('mysql:dbname=yell;charset=utf8;host=localhost','root','');
} catch (PDOException $e) {
  exit('DbConnectError:'.$e->getyell());
}


//３．SQLを作成(ｓｔｍｌの中で)
$stmt = $pdo->prepare("SELECT * FROM yell_table ORDER BY id DESC");
$status = $stmt->execute();
//実行後、エラーだったらfalseが返る


//４．エラー表示
$view ='';

if($status==false){
  $error = $stmt->errorInfo();
  exit("QueryError:".$error[2]);//エラー表示
  
}else{//正常
	while($r = $stmt->fetch(PDO::FETCH_ASSOC)){
		$view .= '<p>';
		$view .= $r["create_date"]."　".$r["user"]."(".$r["id"].")";
		$view .= '<br>';
		$view .= '　';
		$view .= $r["yell"];
		$view .= '</a>';
		$view .= '</p>';
	}
}


?>
<!DOCTYPE HTML>
<html lang="ja">
	<head>
	<meta charset="utf-8">
		<title>エールをおくれ！</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
	</head>
	<body>
		<h1>エールをおくれ！</h1>
		<form action="insert.php" method="post">
			yell: <input type="text" name="yell">
			user: <input type="text" name="user">
			<input type="submit" value="エール！">
		</form>
		<h2>エール一覧</h2>
		<div>
			<div><?=$view?></div>
		</div>
	</body>
</html>
<script>

</script>
