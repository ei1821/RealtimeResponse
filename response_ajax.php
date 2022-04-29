<?php


require_once __DIR__.'/utils/utils.php';


// 処理モードの取得
$room_id = -1;
if (isset($_GET["room_id"]) == true && $_GET["room_id"] != "") {
    $room_id = intval($_GET["room_id"]);
}

$db = new MyDB();
$ret =  $db->query("SELECT * FROM `reactions` WHERE id in (SELECT max(id) FROM `reactions` WHERE room_id=$room_id GROUP BY user_id)");

$date = new  DateTime();
$date = $date->format('Y-m-d H:i:s');
$retarr = array("good"=>0, "bad"=>0, "datetime"=>$date);
foreach($ret["result"] as $row) {
    if($row["is_good"] == 1) {
        $retarr["good"]++;
    }
    else {
        $retarr["bad"]++;
    }
}
// 結果を返す
echo(json_encode($retarr, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
