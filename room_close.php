<?php
    require_once __DIR__."/utils/utils.php";
?>
<!DOCTYPE html>
<html lang="ja">
 <head>
 <meta charset="utf-8">
 <title>room closed</title>
 <meta name="description" content="ディスクリプションを入力">
 <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
 <link rel="stylesheet" href="css/style.css">
 <script ></script>
 </head>

<?php
    if(isset($_SESSION["room_id"])) {
        $room_id = $_SESSION["room_id"];
        $date = new DateTime();$date = $date->format('Y-m-d H:i:s');
        $ret = $db->query("UPDATE `rooms` SET is_closed=1 , closed_at = '$date' WHERE id='$room_id'");
        unset($_SESSION["room_id"]);
        unset($_SESSION["room_name"]);
        if($ret) {
			echo_msg("ルームは閉じられました。<br><a href='index.php'>ホームへ</a>");
        }
    }
    else {
        header("Location: index.php");
        exit;
    }

?>
