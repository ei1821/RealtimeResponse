<?php
    require_once __DIR__."/utils/utils.php";
?>
<!DOCTYPE html>
<html lang="ja">
 <head>
 <meta charset="utf-8">
 <title>新規登録</title>
 <meta name="description" content="ディスクリプションを入力">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <link rel="stylesheet" href="css/style.css">
 <script src="main.js"></script>
 </head>
<?php
    if(isset($_SESSION['id'])) {//ログインしているとき
		echo_msg("ログインしています。<br>\n<a href='index.php'>ホームへもどる</a>");
        exit;
    }
?>
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

</body>

</html>
