<?php
    require_once __DIR__."/utils/utils.php";
    session_start();
    if (isset($_SESSION['id'])) {//ログインしているとき
		header("Location: ./index.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="ja">
 <head>
 <meta charset="utf-8">
 <title>ログイン</title>
 <meta name="description" content="ディスクリプションを入力">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <link rel="stylesheet" href="css/style.css">
 <!-- [if lt IE 9] -->
 <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
 <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
 <!-- [endif] -->
 <script src="main.js"></script>
 </head>
 <body>

 <h1>ログインページ</h1>
<form action="login.php" method="post">
<div>
    <label>メールアドレス：<label>
    <input type="text" name="mail" required>
</div>
<div>
    <label>パスワード：<label>
    <input type="password" name="pass" required>
</div>
<input type="submit" value="ログイン">
</form>


<p>新規登録は<a href="signup.php">こちら</a></p>
<br>
<div>
    <a href="index.php">ホームに戻る</a>
</div>

