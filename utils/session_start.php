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
	ini_set('display_errors', "On");
	$db = new MyDB();
	if(isset($_SESSION["id"])) {
		$user_id = $_SESSION["id"];
		$user_name = $_SESSION["name"];
		$db = new MyDB();
		if(!isset($_SESSION["room_id"])) {
			$ret = $db->query("SELECT * FROM `rooms` WHERE owner_id = $user_id AND is_closed = 0");
			if($ret["count"] == 1) {
				$ret = $ret["result"][0];
				$room_id = $ret["id"];
				$room_name = $ret["name"];
				$_SESSION["room_id"] = $room_id;
				$_SESSION["room_name"] = $room_name;
			}
		}
	}

	$skip_pages = ["login.php", "signin.php", "signup.php", "regster.php"];

	if(isset($_SESSION["current_page"])) {
		$uri = $_SESSION["current_page"];
		$skip_flag = false;
		foreach($skip_pages as $str) {
			if(preg_match("/" . $str . "/", $uri)) {
				$skip_flag = true;
				break;
			}
		}
		if(!$skip_flag) {
			$_SESSION["past_page"] = $_SESSION["current_page"];
		}
	}
	$_SESSION["current_page"] = $_SERVER["REQUEST_URI"];

?>
