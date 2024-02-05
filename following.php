<?php
require_once("./helper_function.php");

if (nonempty_get("user_no")) {
    $user_no_show = $_GET["user_no"];
} else {
    header("location:error.php");
    exit();
}

$user_no = "";
session_start();
session_regenerate_id(true);
if (nonempty_session("user_no")) {
    $user_no = $_SESSION["user_no"];
}


try {
    require_once("./system/DBInfo.php");
    $pdo = new PDO(DBInfo::DNS, DBInfo::USER, DBInfo::PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    require_once("uncfm_no.php");

    if ($user_no != "") {
        $free_name= select_from_user_no($user_no, "free_name")[0];
    }

    $free_name_show= select_from_user_no($user_no_show, "free_name")[0];

    $sql = "select id_name,free_name,profile, user_no from follow inner join user on followed=user_no where following=?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $user_no_show);
    
    $stmt->execute();
    $pdo = null; 

} catch (PDOException $e) {
    $pdo = null;
    header("location:error.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>X blog</title>
    <link rel="stylesheet" href="common.css">
</head>

<body>
    <header>
        <div>
            <h1>X blog</h1>
            <img src="image/0.png" alt="X blog">
        </div>

        <?php
        if ($user_no != "") {
            print "
            <p>ようこそ、
               <a href='my_timeline.php?user_no={$user_no}'>
                   {$free_name}
                </a>さん
            </p>
            <ul>
                <li><a href='timeline.php'>タイムライン</a></li>
                <li><a href='notice.php'>通知{$unconfirmed_no}</a></li>
                <li><a href='main.php'>発言する</a></li>
                <li><a href='system/system_logout.php'>ログアウト</a></li>
            </ul>
            ";
        }else{
            print "
                <a href='login.html'>ログイン</a>
            ";
        }
        ?>
    </header>
    <main>
        <h2>
            <?= $free_name_show ?>さんのフォロー一覧
        </h2>
        <ul>
            <?php

            while ($row = $stmt->fetch()) {              
                print("
                <li class='tweet'>
                    <img src='image/{$row[3]}.png'>
                    <div>
                        <p>
                            {$row[1]} <a href='my_timeline.php?user_no={$row[3]}'>@{$row[0]}</a>
                        </p>
                        <p>{$row[2]}</p>
                    </div>
                </li>
                ");
            }
            ?>
        </ul>
    </main>
</body>

</html>