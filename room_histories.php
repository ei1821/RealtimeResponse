<?php
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
?>
<!DOCTYPE html>
<html lang="ja">
 <head>
 <meta charset="utf-8">
 <title>過去のルーム</title>
 <meta name="description" content="ディスクリプションを入力">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <link rel="stylesheet" href="css/room_histories.css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
 <script>
  $(function() {
    $(".history_row").on("click", function() {
      var room_id = $(this).data("id");

      if(room_id != "Nan")
        window.location.href = "room_result.php?room_id=" + room_id ;
    });
  });
 </script>
 </head>
 <body>

 <!----- main ----->
 <article>
  <h2>過去のルーム</h2>

  <div id="history">
<?php

  $res = $db->query("SELECT * FROM `rooms` WHERE owner_id = $user_id ORDER BY id DESC");

  if($res["count"] == 0) {
    echo "ルームの履歴がありません";
  }
  else {
	?>
<div class="history_row" data-id="Nan">
	<div class="history_row_name">ルーム名</div>
	<div class="history_row_created_at">開始日時</div>
	<div class="history_row_closed_at">終了日時</div>
	<div class="history_row_num">参加人数</div>
</div>

<?php
    foreach ($res["result"] as $row) {
      $tr = array();
      $tr["hitsory_row_name"] = $row["name"];
      $tr["history_row_created_at"] = $row["created_at"];
      $tr["history_row_closed_at"] = ($row["is_closed"] == 1 ? $row["closed_at"] : "開催中");
      $ret_cnt = $db->query("SELECT COUNT(*) as count FROM `reactions` WHERE room_id = ". $row["id"] . " AND id in (SELECT max(id) FROM `reactions` WHERE room_id = " . $row["id"] . " GROUP BY user_id )");
      $tr["history_row_num"] = $ret_cnt["result"][0]["count"];

      echo '<div class="history_row" data-id="'. ($row["is_closed"] == 1 ? $row["id"] : "Nan")  . '">';
      foreach($tr as $key => $val) {
        echo "<div class='" . $key . "'>" . $val ."</div>";
      }
      echo "</div>";
   }

 }

  ?>
  </div>

<br><br>
 <div>
 <a href="index.php">ホームに戻る</a>
 </div>
 </article>
 <!----- /main ----->

 <!----- footer ----->
 <!----- /footer ----->
 </body>
</html>
