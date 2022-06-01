<?php
require_once __DIR__."/utils/utils.php";

//フォームからの値をそれぞれ変数に代入
$name = $_POST['name'];
$mail = $_POST['mail'];
$pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);

$name = $db->escape($name);
$mail = $db->escape($mail);

//フォームに入力されたmailがすでに登録されていないかチェック
$sql = "SELECT * FROM `users` WHERE mail = '$mail'";

$ret = $db->query($sql);
if($ret["count"] > 0) {
    $msg = '同じメールアドレスが存在します。';
    $link = '<a href="signup.php">戻る</a>';
}
else {
    $date = new DateTime();$date = $date->format('Y-m-d H:i:s');
    $ret = $db->query("INSERT INTO users values(null, '$name', '$pass', '$mail', '$date', '$date')");
    $msg = '会員登録が完了しました';
    $link = '<a href="signin.php">ログインページ</a>';
}
?>

<div class="echo_msg">
<h1><?php echo $msg; ?></h1><!--メッセージの出力-->
<?php echo $link; ?>
</div>
