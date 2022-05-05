<?php
    require_once __DIR__."/utils/utils.php";
    session_start();
    $username = $_SESSION['name'];
    if (!isset($_SESSION['id'])) {//ログインしているとき
        $msg = '先にログインしてください。';
        echo "$msg<br><a href='signin.php'>ログインする</a>";
        exit;
    }
    $room_id  = $_POST["room_id"];
    $db = new MyDB();

    $user_id = $_SESSION["id"];

    $res = $db->query("SELECT * FROM `rooms` WHERE owner_id=$user_id AND is_closed=0");
    if($res["count"] > 0) {
        header("Location: room.php");
        exit;
    }
    $res = $db->query("SELECT * FROM `rooms` WHERE id=$room_id AND is_closed=0");
    if($res["count"] == 0) {
      $meg = "このルームは終了しました。";
      echo $msg . "<br><a href='joinroom.php'>別のルームに参加</a>";
      exit;
    }
?>
<!DOCTYPE html>
<html lang="ja">
 <head>
 <meta charset="utf-8">
 <title>なんかのサイトのホーム</title>
 <meta name="description" content="ディスクリプションを入力">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <link rel="stylesheet" href="css/take_room.css">
 <!-- [if lt IE 9] -->
 <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
 <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
 <!-- [endif] -->
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
 <script src="https://d3js.org/d3.v7.min.js"></script>
 <script type="text/javascript">
//Ajax関数
function sendReaction(tf) {
    var txt = d3.select("#comment").property("value");
    // マスタデータの取得
    $.ajax({
        type: "POST"
      , url: "ajax/reaction_send_ajax.php"
      , data: { user_id: <?= $user_id ?>, room_id: <?= $room_id ?>, is_good: tf, text: txt}
    }).done(function(){
        // 特になし
    }).fail(function() {
        // 取得エラー
        alert('取得エラー');
    }).always(function() {
      if(txt != "") {
        d3.select("#comment").property("value", "");
      }
    });
    return false;
}
</script>
 </head>
 <body>

 <!----- main ----->
 <article>
     <?php
        $room_id = $_POST["room_id"];

        $res = $db->query("SELECT * FROM `rooms` WHERE id=" . $room_id);
        $res = $res["result"][0];
        $room_name = $res["name"];
        $room_owner = $db->query("SELECT name FROM `users` WHERE id = ".$res["owner_id"])["result"][0]["name"];

?>
    <div class="title">
      <h2>ルーム: <?= $room_name ?></h2>
    </div>
    <div class="owner_info">
      <p>開催者: <?= $room_owner ?> </p>
    </div>
    <div class="button_row">
      <div id="goodButton" class="button">
        <a href="javascript:void(0)" value=1 onclick="sendReaction(1);">わかる</a>
      </div>
      <div id="badButton" class="button">
        <a href="javascript:void(0)" value=0 onclick="sendReaction(0);">わからん</a>
      </div>
    </div>
    <br>
    <div class="comment">
      <input type="text" id="comment"></input>
    </div>
    <br>

    <div class="room_exit">
    <a href="joinroom.php">ルームを退出する</a>
    </div>

</article>
 <!----- /main ----->
 <div id="comment_div">
 </div>

 <!----- footer ----->
 <script src="js/comment.js"></script>

 <!----- /footer ----->
 </body>
</html>
