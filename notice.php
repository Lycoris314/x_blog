<?php

session_start();
session_regenerate_id(true);
if (isset($_SESSION["user_no"]) && $_SESSION["user_no"] != "") {
    $user_no = $_SESSION["user_no"];
} else {
    header("location:error.html");
    exit();
}

try {
    require_once("./system/DBInfo.php");
    $pdo = new PDO(DBInfo::DNS, DBInfo::USER, DBInfo::PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "select free_name from user where user_no=?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $user_no);
    $stmt->execute();

    $row = $stmt->fetch();
    $free_name = $row[0];


    $sql = "select distinct content ,date,time,id_name,free_name,tweet.user_no from tweet inner join notice on tweet.tweet_no=notice.tweet_no where notice.user_no=? order by date desc,time desc  limit 0,20";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $user_no);
    $stmt->execute();


    $pdo = null;

} catch (PDOException $e) {
    $pdo = null;
    //header("location:error.html");
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
    <link rel="stylesheet" href="notice.css">
</head>
<body>
    <header>
        <h1>X blog</h1>
        <p>ようこそ、
            <a href="my_timeline.php?user_no=<?=$user_no?>">
                <?= $free_name ?>
            </a>さん
        </p>
        <a href="timeline.php">タイムライン</a>
        <a href="notice.php">通知</a>
        <a href="main.php">発言する</a>
        <a href="system/system_logout.php">ログアウト</a>
    </header>
    <main>
        <h2>通知</h2>
        <ul>
                <?php
                while ($row = $stmt->fetch()) {
                    print("<li class='tweet'>");
                    print("<img class='min_img' src='image/{$row[5]}.png'>");
                    print("<div>");
                   
                    print("{$row[3]}{$row[4]}");
                    print("<p>{$row[0]}</p>");
                    print("<p>{$row[1]}{$row[2]}</p>");
                    

                    print("</div>");
                    print("</li>");
                }
                ?>
            </ul>

    </main>
</body>
</html>