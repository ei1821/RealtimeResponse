<?php
  /* 定義するもの
    user_id : $_SESSION["id"]
    user_name : $_SESSION["name"]

    ルームを所持する場合
    room_id : $_SESSION["room_id"]
    room_name : $_SESSION["room_name"]

    $db
  */
  require_once __DIR__."/MyDB.class.php";
  session_start();
  $db = new MyDB();
  if(isset($_SESSION["id"])) {

    $user_id = $_SESSION["id"];
    $user_name = $_SESSION["name"];
    $db = new MyDB();
    $ret = $db->query("SELECT * FROM `rooms` WHERE owner_id = $user_id AND is_closed = 0");
    if($ret["count"] == 1) {
      $ret = $ret["result"][0];
      $room_id = $ret["id"];
      $room_name = $ret["name"];
      $_SESSION["room_id"] = $room_id;
      $_SESSION["room_name"] = $room_name;
    }
  }
?>
