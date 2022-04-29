<?php 
    require_once __DIR__."/utils/utils.php"; 
    session_start();

    $db = new MyDB();

    if (!isset($_SESSION['id'])) {//ログインしてないとき
        $msg = 'ルームを作る方は先にログインしてください。';
        echo "$msg<br><a href='signin.php'>ログインする</a>";
        exit;
    }
    else if(isset($_SESSION["room_id"])) {  //ルーム作成済みの場合
        header("Location: room.php");
        exit;
    }
    else {
        $username = $_SESSION['name'];
        $user_id = $_SESSION["id"];
        
        $res = $db->query("SELECT * FROM `rooms` WHERE owner_id=$user_id AND is_closed=0");
        if($res["count"] > 0) {
            
            $res = $res["result"][0];
            $_SESSION["room_id"] = $res["id"];
            $_SESSION["room_name"]  = $res["name"];
            header("Location: room.php");
            exit;      
        }
    }


?>
<!DOCTYPE html>
<html lang="ja">
 <head>
 <meta charset="utf-8">
 <title>なんかのサイトのホーム</title>
 <meta name="description" content="ディスクリプションを入力">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <link rel="stylesheet" href="style.css">
 <!-- [if lt IE 9] -->
 <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
 <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
 <!-- [endif] -->
 <script src="main.js"></script>
 </head>
 <body>

 <!----- main ----->
 <article>
    <h2>ルームの作成</h2>
 <section>
 <form action="room_register.php" method="post">
    <div>
        <label>ルーム名：<label>
        <input type="text" name="name" required>
    </div>
    <input type="submit" value="ルームを作成">
    </form>

    <br><br>
    <div>
        <a href="index.php">ホームに戻る</a>
</div>
</section>
 </article>
 <!----- /main ----->
 
 <!----- footer ----->
 <footer>フッター</footer>
 <!----- /footer ----->
 </body>
</html>