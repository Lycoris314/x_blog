<?php

require_once("./helper_function.php");

session_start();
session_regenerate_id(true);
if (nonempty_session("user_no")) {
    $user_no = $_SESSION["user_no"];
} else {
    header("location:error.php");
    exit();
}

try {
    require_once("./system/DBInfo.php");
    $pdo = new PDO(DBInfo::DNS, DBInfo::USER, DBInfo::PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    require_once("uncfm_no.php");

    $free_name = select_from_user_no($user_no, "free_name")[0];

    $sql = "update notice set confirm =1 where user_no=?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $user_no);
    $pdo->beginTransaction();
    $stmt->execute();
    $pdo->commit();


    $sql = "select distinct content ,date,time,id_name,free_name,tweet.user_no from tweet inner join notice on tweet.tweet_no=notice.tweet_no where notice.user_no=? order by date desc,time desc  limit 0,20";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $user_no);
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
        <p>ようこそ、
            <a href="my_timeline.php?user_no=<?= $user_no ?>">
                <?= $free_name ?>
            </a>さん
        </p>
        <ul>
            <li><a href="timeline.php">タイムライン</a></li>
            <li><a href="notice.php">通知
                    <?= $unconfirmed_no ?>
                </a></li>
            <li><a href="main.php">発言する</a></li>
            <li><a href="system/system_logout.php">ログアウト</a></li>
        </ul>
    </header>
    <main>
        <h2>通知</h2>
        <ul>
            <?php
            while ($row = $stmt->fetch()) {
                print("
                <li class='tweet'>
                    <img class='min_img' src='image/{$row[5]}.png'>
                    <div>
                        <p>{$row[4]} <a href='my_timeline.php?user_no={$row[5]}'>@{$row[3]}</a></p>
                        <p>{$row[0]}</p>
                        <p class='time'>{$row[1]} {$row[2]}</p>
                    </div>
                </li>");
            }
            ?>
        </ul>

    </main>
</body>

</html>