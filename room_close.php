<?php
    session_start();
    require_once __DIR__."/utils/utils.php";

    if(isset($_SESSION["room_id"])) {
        $db = new MyDB();
        $room_id = $_SESSION["room_id"];
        $date = new DateTime();$date = $date->format('Y-m-d H:i:s');
        $ret = $db->query("UPDATE `rooms` SET is_closed=1 , closed_at = '$date' WHERE id='$room_id'");
        unset($_SESSION["room_id"]);
        unset($_SESSION["room_name"]);
        if($ret) {
            echo "ルームは閉じられました。<br><a href='index.php'>ホームへ</a>";
        }
    }
    else {
        header("Location: index.php");
        exit;
    }

?>
