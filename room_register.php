<?php
    require_once __DIR__."/utils/utils.php";
?>
<!DOCTYPE html>
<html lang="ja">
 <head>
 <meta charset="utf-8">
 <title>なんかのサイトのホーム</title>
 <meta name="description" content="ディスクリプションを入力">
 <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
 <link rel="stylesheet" href="css/style.css">
 <script ></script>
 </head>
<?php
    if (!isset($_SESSION['id'])) {//ログインしているとき
        $msg = 'ルームを作る方は先にログインしてください。';
		echo_msg("$msg<br><a href='index.php'>ホームへもどる</a>");
        exit;
    }
    else if(isset($_SESSION["room_id"])) {  //ルーム作成済みの場合
        header("Location: room.php");
        exit;
    }

    $room_name = $_POST["name"];
    $user_id = $_SESSION["id"];

    $date = new DateTime();$date = $date->format('Y-m-d H:i:s');
    $ret = $db->query("INSERT INTO `rooms` VALUES(null, '$user_id', '$room_name','$date', 0, DEFAULT)");

    if($ret["status"]) {    // True -> 作成成功
        $_SESSION["room_id"] = $ret["insert_id"];
        $_SESSION["room_name"] = $room_name;
        header("Location: ./room.php");
        exit;
    }
    else {
		echo_msg("ルームの作成に失敗しました。<br><a href='index.php'>ホームへもどる</a>");
    }
