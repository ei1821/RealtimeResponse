<?php
session_start();
$_SESSION = array();//セッションの中身をすべて削除
session_destroy();//セッションを破壊
?>

<p>ログアウトしました。</p>
<a href="signin.php">ログインへ</a>
<a href="index.php">ホームへ</a>