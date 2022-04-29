<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8"/>

<?php

require_once __DIR__.'/utils/utils.php';


// 処理モードの取得
$id = 1;

$db = new MyDB();
$ret =  $db->query("SELECT * FROM user WHERE id=$id");

var_dump($ret);

?>
