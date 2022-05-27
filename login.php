<?php
session_start();
require_once __DIR__."/utils/utils.php";

$db = new MyDB();
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

<h1><?php echo $msg; ?></h1>
<?php echo $link; ?>
