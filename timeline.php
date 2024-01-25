<?php

$page = 1;
if (isset($_GET["page"]) && is_numeric($_GET["page"])) {
    $page = (int) $_GET["page"];
}
$from = ($page - 1) * 20;


session_start();
session_regenerate_id(true);
if (isset($_SESSION["user_no"]) && $_SESSION["user_no"] != "") {
    $user_no= $_SESSION["user_no"];
} else {
    header("location:timeline_no_login.php");
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
    $free_name=$row[0];

    $sql = "select count(tweet_no) from tweet inner join follow on user_no=followed where user_no=? or following=?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $user_no);
    $stmt->bindValue(2, $user_no);
    $stmt->execute();
    $row = $stmt->fetch();

    $totalpage = ceil((int) $row[0] / 20);

    $sql = "select distinct content ,date,time,id_name,free_name,user_no from tweet left join follow on user_no=followed where user_no=? or following=? order by date desc,time desc  limit {$from},20";
    //$sql = "select distinct content ,date,time,id_name,free_name,user_no from tweet inner join follow on user_no=followed where following=? order by date desc,time desc  limit {$from},20";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $user_no);
    $stmt->bindValue(2, $user_no);
    $stmt->execute();

} catch (PDOException $e) {
    $pdo = null;
    print $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>X blog</title>
    <link rel="stylesheet" href="common.css">
    <link rel="stylesheet" href="timeline.css">
</head>

<body>
    <header>
        <h1>X blog</h1>
        <p>ようこそ、
            <a href="my_timeline.php?user_no=<?=$user_no ?>"><?= $free_name ?></a>さん
        </p>
        <a href="timeline.php">タイムライン</a>
        <a href="notice.php">通知</a>
        <a href="main.php">発言する</a>
        <a href="system/system_logout.php">ログアウト</a>
    </header>
    <main>
        <h2>タイムライン</h2>
        <ul>
        <?php
        while ($row = $stmt->fetch()) {
            print "<li class='tweet'>";
            print "<img class='min_img' src='image/{$row[5]}.png'>";
            print "<div>";
            print "<a href='my_timeline.php?user_no={$row[5]}'>$row[4]</a> &nbsp;";
            print "<a href='my_timeline.php?user_no={$row[5]}'>@{$row[3]}</a>";
            print "<p>{$row[0]}</p>";
            print "<p>{$row[1]}{$row[2]}</p>";
            print "</div>";
            print "</li>";
        }
        ?>
        </ul>

        <section>
            <?php
            for ($i = 1; $i <= $totalpage; $i++) {
                if ($page == $i) {
                    print "<a>";
                }else{
                    print "<a href='?page={$i}'>";
                }
                print "{$i} </a> &emsp;";
            }
            ?>
        </section>

    </main>
</body>

</html>