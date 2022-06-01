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
 <!-- [if lt IE 9] -->
 <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
 <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
 <!-- [endif] -->
 <script ></script>
 </head>
<?php
    if (isset($_SESSION['id'])) {//ログインしているとき
        $username = $_SESSION['name'];
        $user_id = $_SESSION["id"];
        $msg = 'こんにちは' . htmlspecialchars($username, \ENT_QUOTES, 'UTF-8') . 'さん';
        $link = '<a href="logout.php">ログアウト</a>';
        $db= new MyDB();
    } else {//ログインしていない時
        $msg = 'ログインしていません';
        $link = '<a href="signin.php">ログイン</a>';
    }
?>
<body>
 <!-- header-->
 <header><?= $msg ?> <?= $link ?> </header>
 <nav></nav>
 <!-- /header -->

 <!-- main -->
 <article>
 <h1>リアルタイムで理解度のレスポンスを返すやつ</h1>
 <section>
<a href="./signup.php">新規登録</a>
<a href="./signin.php">ログイン</a>
<a href="./createroom.php">ルームの作成</a>
<?php
  if(isset($room_id)) {
?>
<a href="./room.php">ルームを表示</a>
<?php
}else{
?>
<a href="./joinroom.php">ルームに参加</a>
<?php
}
if( isset($user_id) ) {
  echo "<a href='./room_histories.php'>過去のルーム</a>";
}
?>
</section>
 </article>
 <!--- /main ----->
 <!--- /footer ----->
 </body>
</html>
