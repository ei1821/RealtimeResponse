<?php
require_once __DIR__."/utils/utils.php";
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

<?php
//フォームからの値をそれぞれ変数に代入
$mail = $db->escape($_POST['mail']);

$sql = "SELECT * FROM `users` WHERE mail = '$mail'";

$ret = $db->query($sql);

$member = $ret["result"][0];


//指定したハッシュがパスワードにマッチしているかチェック
if (password_verify($_POST['pass'], $member['password'])) {
	session_regenerate_id(true);
    //DBのユーザー情報をセッションに保存
    $_SESSION['id'] = $member['id'];
    $_SESSION['name'] = $member['name'];
    $msg = 'ログインしました。';
    $link = '<a href="index.php">ホーム</a>';
} else {
    $msg = 'メールアドレスもしくはパスワードが間違っています。';
    $link = '<a href="signin.php">戻る</a>';
}
?>

<div class="echo_msg"><h1><?php echo $msg; ?></h1>
<?php echo $link; ?>
</div>

</body>

</html>
