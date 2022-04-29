<?php
    session_start();
    require_once __DIR__."/utils/utils.php";
    if (!isset($_SESSION['id'])) {//ログインしているとき
        $msg = 'ルームを作る方は先にログインしてください。';
        echo "$msg<br><a href='index.php'>ホームへもどる</a>";
        exit;
    }
    else if(isset($_SESSION["room_id"])) {  //ルーム作成済みの場合
        header("Location: room.php");
        exit;
    }
    $db = new MyDB();

    $room_name = $_POST["name"];
    $user_id = $_SESSION["id"];

    $db = new MyDB();
    $date = new DateTime();$date = $date->format('Y-m-d H:i:s');
    $ret = $db->query("INSERT INTO `rooms` VALUES(null, '$user_id', '$room_name','$date', 0, DEFAULT)");

    if($ret["status"]) {    // True -> 作成成功
        $_SESSION["room_id"] = $ret["insert_id"];
        $_SESSION["room_name"] = $room_name;
        header("Location: ./room.php");
        exit;
    }
    else {
        echo "ルームの作成に失敗しました。<br><a href='index.php'>ホームへもどる</a>";
    }
