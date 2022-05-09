<?php

ini_set('display_errors', "On");
  require_once __DIR__."/utils/utils.php";

  if(isset($user_id)) {
   //;
  }else {
    $msg = "ログインしていません";
    $link = "signin.php";
    $link_v = "ログイン";
    echo $msg . "<br><a href='$link'>$link_v</a>";
    exit;
  }
  if(isset($_GET["room_id"]) == true) {
    $ret = $db->query("SELECT * FROM `rooms` WHERE id= " . $_GET["room_id"]);
    $room_info = $ret["result"][0];
    if($_GET["room_id"] != $room_info["id"] || $user_id != $room_info["owner_id"]) {
      $msg = "ルームは非公開です";
      $link = "room_histories.php";
      $link_v = "別のルームを選ぶ";
      echo $msg . "<br><a href='$link'>$link_v</a>";
    }
  }
  else {
    $msg = "ルームを選択してください";
    $link = "room_histories.php";
    $link_v = "過去のルームを選ぶ";
    echo $msg . "<br><a href='$link'>$link_v</a>";
  }

  // 以下データセット形成
  $reactions = $db->query("SELECT * FROM `reactions` WHERE room_id = " . $room_info["id"] . " ORDER BY id");
  $comments = $db->query("SELECT * FROM `comments` WHERE room_id = " . $room_info["id"] . " ORDER BY id");
  $date = new DateTime($room_info["created_at"]);

  $i = 0;
  $j = 0;
  $n = intval($reactions["count"]);
  $m = intval($comments["count"]);
  $reactions = $reactions["result"];
  $comments = $comments["result"];
  $users = array();
  $gb = array("good" => 0, "bad" => 0);
  $dataset = array(array("datetime" => $room_info["created_at"], "good" => 0, "bad" => 0, "comments" => array()));

  while($i < $n || $j < $m) {
    $now = $date->format("Y-m-d H:i:s");
    $prev_gb = $gb;
    while($i < $n && $reactions[$i]["created_at"] <= $now) {
      if(!array_key_exists($reactions[$i]["user_id"], $users)) {
        $users[$reactions[$i]["user_id"]] = $reactions[$i]["is_good"];
        if($reactions[$i]["is_good"] == "1")
          $gb["good"]++;
        else
          $gb["bad"]++;
      }
      else {
        if($users[$reactions[$i]["user_id"]] != $reactions[$i]["is_good"]) {
          if($reactions[$i]["is_good"] == "1") {
            $users[$reactions[$i]["user_id"]] = "1";
            $gb["good"]++;
            $gb["bad"]--;
          }
          else {
            $users[$reactions[$i]["user_id"]] = "0";
            $gb["good"]--;
            $gb["bad"]++;
          }
        }
      }
      $i++;
    }

    $tmp_cm = array();
    while($j < $m && $comments[$j]["created_at"] <= $now) {
      $tmp_cm[] = array("is_good" => $comments[$j]["is_good"], "comment" => $comments[$j]["comment"]);
      $j++;
    }
    $tmp = array("datetime" => $now, "good" => $gb["good"], "bad" => $gb["bad"], "comments" => $tmp_cm);
    if($prev_gb != $gb || count($tmp_cm) != 0) {
      $dataset[] = $tmp;
    }
    $date->modify("+1 seconds");
  }
  $tmp["datetime"] = $room_info["closed_at"];
  $dataset[] = $tmp;

 ?>
<!DOCTYPE html>
<html lang="ja">
 <head>
 <meta charset="utf-8">
 <title>履歴</title>
 <meta name="description" content="ディスクリプションを入力">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <link rel="stylesheet" href="css/style.css">
 <link rel="stylesheet" href="css/room_histories.css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
 <script src="https://d3js.org/d3.v7.min.js"></script>
 <script>
  var dataset = JSON.parse('<?= json_encode($dataset,  JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>');
  var comments= JSON.parse('<?= json_encode($comments, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>');
  var rate = 0;
    window.onload = function() {
        var ds = setup();
        make_graph(ds);

		$(document).on("contextmenu", "svg", e => false);
		$(document).on("contextmenu click", ".overrect", function(e) {
			if(e.which == 1) { // 左クリック
				rate++;
			}
			else if(e.which == 3) { // 右クリック
				rate--;
			}
			ds = zoom_graph($(this).data("time"));
			make_graph(ds);
		});

		$(document).on("keydown", function(e) {
			var key_name = e.key;
			if(key_name == "ArrowRight") { // 未来に
				ds = move_graph(ds, 1);
			}
			else if(key_name == "ArrowLeft"){
				ds = move_graph(ds, 0);
			}
			else return;


			make_graph(ds);
		});
    }

 </script>
 </head>
 <body>

 <!-- main -->
 <article>

  <h2><?= $room_info["name"] ?></h2>

  <div id="ressvg">
    <svg/>
  </div>


  <a href="room_histories.php">ルームを選ぶ</a>
  </article>
 <!-- /main -->

 <!-- footer -->
 <!-- /footer -->

    <script src="js/utils.js"></script>
    <script src="js/result.js"></script>
 </body>
</html>
