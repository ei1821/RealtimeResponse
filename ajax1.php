<?php
require_once __DIR__.'/utils/utils.php';


// 処理モードの取得
$id = -1;
if (isset($_GET["id"]) == true && $_GET["id"] != "") {
    $id = intval($_GET["id"]);
}

$db = new MyDB();
$ret =  $db->query("SELECT * FROM users WHERE id=$id");

if($ret["error"] === "" && $ret["count"] == 1) {
    $strRet = $ret["result"][0]["name"];
}
 
// 結果を返す
echo(json_encode($strRet, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));