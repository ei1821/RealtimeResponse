<?php
session_start();
$_SESSION = array();//セッションの中身をすべて削除
session_destroy();//セッションを破壊
?>
<!DOCTYPE html>
<html lang="ja">
 <head>
 <meta charset="utf-8">
 <title>なんかのサイトのホーム</title>
 <meta name="description" content="ディスクリプションを入力">
 <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
 <link rel="stylesheet" href="css/style.css">
 <script ></script>
 </head>
<body>
<div class="echo_msg">
<p>ログアウトしました。</p>
<a href="signin.php">ログインへ</a>
<a href="index.php">ホームへ</a>
</div>
</body>

</html>
