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
 <body>
 <div>
  <h3>使い方</h3>
	講義ごとにルーム（講義の理解度を共有するスペース）を立ち上げる。<br>
	<ul>先生側</ul>
	<p>
	 ルームの作成からルームを立ち上げる。
	 生徒がルームに参加し、ボタンをクリックするとグラフが毎秒更新される。
	</p>
	 <br>ルーム閉鎖後、過去のルームを参照することが可能。
	<ul>生徒側</ul>
	 ルームに参加から先生が立ち上げたルームを選択し、参加する。<br>
	 わかる/わからんのボタンを授業の内容に応じて選択しクリック<br>
	 講義が終われば退出
<br>
</div>


<a href="./index.php">ホームへもどる</a>
 </body>
</html>

