<?php

require_once __DIR__.'/../utils/utils.php';

$MAX_COMMENT_LENGTH = 128;

// 処理モードの取得
$id = 1;
if (isset($_POST["user_id"]) == true && $_POST["user_id"] != "") {
    $user_id = intval($_POST["user_id"]);
    $room_id = $_POST["room_id"];
    $is_good = $_POST["is_good"];
}

$db = new MyDB();
$date = new DateTime();$date = $date->format('Y-m-d H:i:s');

$res = $db->query("INSERT INTO `reactions` VALUE(null, '$room_id', '$user_id', '$is_good', '$date')");

if(isset($_POST["text"]) == true && $_POST["text"] != "" && count($_POST["text]" < $MAX_COMMENT_LENGTH) {
  $txt = $db->escape($_POST["text"]);
  $res = $db->query("INSERT INTO `comments` VALUE(null, '$room_id', '$user_id', '$is_good', '$txt', '$date')");
}

