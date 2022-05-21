<?php
    require_once __DIR__."/utils/utils.php";
    session_start();
    if (!isset($_SESSION['id'])) {//ログインしているとき
        $msg = '先にログインしてください。';
        echo "$msg<br><a href='signin.php'>ログインする</a>";
        exit;
    }
    $username = $_SESSION['name'];
    $user_id = $_SESSION["id"];
    $db = new MyDB();

    $res = $db->query("SELECT * FROM `rooms` WHERE owner_id=$user_id AND is_closed=0"); // まだ開催中のルームを所有する場合
    if($res["count"] > 0) {
        header("Location: room.php");
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
 <link rel="stylesheet" href="css/style.css">
 <!-- [if lt IE 9] -->
 <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
 <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
 <!-- [endif] -->
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
 <script>
     function linkClick(room_id) {
        var formEle = document.roomform;
        document.getElementById("room_id").value = room_id;
		if(!navigator.userAgent.match(/(iPhone|iPad|iPod|Android)/i)){
			window.open('taker_room.php', 'subwin', 'width=300,height=300');
			formEle.target = "subwin";
		}
        formEle.submit();
        return true;
    }
 </script>
 </head>
 <body>

 <!----- main ----->
 <article>
    <h2>ルームの参加</h2>
    <div id="room_select">
    <?php
        $res = $db->query("SELECT * FROM rooms WHERE is_closed=0"); // 開催中のルーム
        foreach($res["result"] as $row) {
            $room_id = $row["id"];
            $room_owner = $db->query("SELECT name FROM `users` WHERE id=" . $row["owner_id"])["result"][0]["name"];
            $room_name = $row["name"];
            $link = "<a href='Room/$room_id'> $room_name [$room_owner]'</a>";
            ?>
            <a href="#" onclick="linkClick(<?= $room_id ?>);"><?= $room_name  ?> [<?= $room_owner ?>]</a><br>
            <?php
        }
    ?>
    <form name="roomform" action="take_room.php" method="post">
    <input type="hidden" id="room_id" name="room_id" value="">
    </form>
    </div>

    <a href="index.php">ホームに戻る</a>

 </article>
 <!----- /main ----->

 <!----- footer ----->
 <!----- /footer ----->
 </body>
</html>
