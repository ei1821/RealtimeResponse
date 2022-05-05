<?php
require_once __DIR__."/../utils/utils.php";

if (isset($_GET["room_id"]) == true && $_GET["room_id"] != "") {
  $room_id = intval($_GET["room_id"]);

  $db = new MyDB();
  $date = new DateTime();
  $now_date = $date->format('Y-m-d H:i:s');
  $before_date = $date->modify("-10 second")->format("Y-m-d H:i:s");
  $ret = $db->query("SELECT * FROM `comments` WHERE room_id = $room_id AND '$before_date' <= created_at AND created_at < '$now_date'");
  $retarr = array();
  foreach($ret["result"] as $row) {
    $retarr[] = array("comment" => $row["comment"], "is_good" => $row["is_good"] == "1");

  }
  echo(json_encode($retarr, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}
