<?php
    require_once __DIR__."/utils/utils.php";
    session_start();

    if (isset($_SESSION['id'])) {//ログインしているとき
        $msg = 'こんにちは' . htmlspecialchars($user_name, \ENT_QUOTES, 'UTF-8') . 'さん';
        $link = '<a href="logout.php">ログアウト</a>';
        $db = new MyDB();
    } else {//ログインしていない時
        $msg = 'ログインしていません';
        $link = '<a href="signin.php">ログイン</a>';
        echo "$msg<br>$link";
        exit;
    }
    if(isset($_SESSION["room_id"])) {
        $room_id = $_SESSION["room_id"];
        $room_name = $_SESSION["room_name"];
    }
    else {

        $res = $db->query("SELECT * FROM `rooms` WHERE owner_id=$user_id AND is_closed=0");
        if($res["count"] > 0) {
            $res = $res["result"][0];
            $room_id = $res["id"];
            $room_name = $res["name"];
            $_SESSION["room_id"] = $room_id;
            $_SESSION["room_name"] = $room_name;
        }
        else {
            $msg = "ルームを作成していません。";
            echo "$msg<br><a href='createroom.php'>ルームを作る</a>";
            exit;
        }
    }

?>
<!DOCTYPE html>
<html lang="ja">
 <head>
 <meta charset="utf-8">
 <title>なんかのサイトのホーム</title>
 <meta name="description" content="ディスクリプションを入力">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <link rel="stylesheet" href="css/comment.css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
 <script src="https://d3js.org/d3.v7.min.js"></script>
 <script >
$(() => res_poll());
$(() => com_poll());
var res_logs = [];
var comments = [];
async function res_poll() {
    var id = <?= $room_id ?>;
    try {
        const response = await $.getJSON("response_ajax.php?room_id=" + id);
        res_logs.unshift(response);
        //console.log(res_logs);
        //$('#response').prepend("<p>good : " + response.good + " bad : " + response.bad + "</p>");
        $("#response_hiddenbutton").trigger("click");

    } catch (e) {
        console.error(e);
    } finally {
        setTimeout(res_poll, 1000);
    }
}

async function com_poll() {
    var id = <?= $room_id ?>;
    try {
      comments = await $.getJSON("comment_ajax.php?room_id=" + id);
      for(let i = 0; i < comments.length; ++i ){
        niconicomment(i, comments[i].comment, comments[i].is_good);
      }
    }catch(e) {
      console.error(e);
    } finally {
      setTimeout(com_poll, 10 * 1000);
    }
}
 </script>
 </head>
 <body>

 <!----- header----->
 <header><?= $msg ?></header>
 <nav></nav>
 <!----- /header ----->

 <!----- main ----->
 <article>
<h1><?= $room_name ?></h1>
<h2>理解度チェック</h2>
<div id="ressvg">
  <svg width="500" height="100">
  </svg>
</div>

<input type="hidden" id="response_hiddenbutton" onclick="test_call();"></input>
 <script src="js/graph.js"></script>
 <script src="js/comment.js"></script>
<a href="room_close.php">ルームを閉じる</a>
<p><a href="index.php">ホームに戻る</a></p>
</section>
 </article>
 <!----- /main ----->

 <!----- footer ----->
<!----- /footer ----->
 </body>
</html>
