<?php
    require_once __DIR__."/utils/utils.php";

    session_start();
    $username = $_SESSION['name'];
    if (isset($_SESSION['id'])) {//ログインしているとき
        echo "ログインしています。<br>";
        echo "<a href='index.php'>ホームへ</a>";
        exit;
    }
?>
<!DOCTYPE html>
<html lang="ja">
 <head>
 <meta charset="utf-8">
 <title>新規登録</title>
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

    <h1>新規会員登録</h1>
    <form action="register.php" method="post">
    <div>
        <label>名前：<label>
        <input type="text" name="name" required>
    </div>
    <div>
        <label>メールアドレス：<label>
        <input type="text" name="mail" required>
    </div>
    <div>
        <label>パスワード：<label>
        <input type="password" name="pass" required>
    </div>
    <input type="submit" value="新規登録">
    </form>
    <p>すでに登録済みの方は<a href="signin.php">こちら</a></p>
