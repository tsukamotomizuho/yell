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

if($_SERVER['REQUEST_METHOD']=='POST' && 
   isset($_POST['message'] )&&
	isset($_POST['user'])){
	
	checkToken();
	   
	$message = trim($_POST['message']);
	$user = trim($_POST['user']);
	
   if($message !== ''){
		
	   	$message = str_replace("\t", '' , $message);
	   
		$user =  ($user == '')? 'ななしさん' : $user;
		
		$postedAt = date('Y-m-d H:i:s');
		
		$newData = $message . "\t" . $user . "\t" . $postedAt. "\n";

		$fp = fopen($dataFile, 'a');
		fwrite($fp, $newData);
		fclose($fp);
	   
   }
}else{
	setToken();
}

$posts = file($dataFile, FILE_IGNORE_NEW_LINES);

$posts = array_reverse($posts);

$posts_list = json_encode( $posts , JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);

?>
<!DOCTYPE HTML>
<html>
	<head>
	<meta charset="utf-8">
		<title>エールをおくれ！</title>
	</head>
	<body>
		<h1>エールをおくれ！</h1>
		<form action="insert.php" method="post">
			yell: <input type="text" name="message">
			user: <input type="text" name="user">
			<input type="submit" value="エール！">
			<input type="hidden" name="token" value="<?php echo h($_SESSION['token']); ?>">
		</form>
		<h2>エール一覧(<?php echo count($posts); ?>件)</h2>
		<ul>
			<?php if (count($posts)) :?>
				<?php foreach ($posts as $post) : ?>
				<?php list($message, $user, $postedAt) = explode("\t",$post)
				?>
					<li><?php echo h($message); ?> (<?php echo h($user); ?>) - <?php echo h($postedAt); ?></li>
				<?php endforeach; ?>
			<?php else :?>
			<li>エールはありません</li>
			<?php endif; ?>
		</ul>
	</body>
</html>
<script>
	let posts = <?php echo $posts_list; ?>;
	console.log(posts);
</script>
