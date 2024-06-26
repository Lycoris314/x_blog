<?php
require_once("./helper_function.php");

const NUM_OF_TWEET =20; //１ページに表示するツイートの数

$page = 1;
if (nonempty_get("page")) {
    $page = (int) $_GET["page"];
}
$from = ($page - 1) * NUM_OF_TWEET;


session_start();
session_regenerate_id(true);
if (nonempty_session("user_no")) {
    $user_no = $_SESSION["user_no"];
} else {
    header("location:timeline_no_login.php");
    exit();
}


try {
    require_once("./system/DBInfo.php");
    $pdo = new PDO(DBInfo::DNS, DBInfo::USER, DBInfo::PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    require_once("uncfm_no.php");
    $msg = "";

    if (isset($_GET["login"]) && $_GET["login"] == 1 && $uncfm_no != 0) {
        $msg = "未読の通知が{$uncfm_no}件あります。";
    }

    $free_name =select_from_user_no($user_no, "free_name")[0];

    $sql = "select count(distinct tweet_no) from tweet left join follow on user_no=followed where user_no=? or following=?";
    $stmt = $pdo->prepare($sql);
    bindValues($stmt, $user_no, $user_no);
    $stmt->execute();
    $row = $stmt->fetch();

    $totalpage = ceil((int) $row[0] / NUM_OF_TWEET);


    $sql = "select distinct content ,date,time,id_name,free_name,user_no,tweet_no from tweet left join follow on user_no=followed where user_no=? or following=? order by date desc,time desc  limit {$from},". NUM_OF_TWEET;
    $stmt = $pdo->prepare($sql);
    bindValues($stmt, $user_no, $user_no);
    $stmt->execute();

    $pdo= null;

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
    <script src="jquery.js"></script>
    <script src="timeline.js"></script>
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
            <li><a href="notice.php">通知<?= $unconfirmed_no ?></a></li>
            <li><a href="main.php">発言する</a></li>
            <li><a href="system/system_logout.php">ログアウト</a></li>
        </ul>
        <p><?=$msg?></p>
    </header>
    <main>
        <h2>タイムライン</h2>
        <ul>
            <?php
            while ($row = $stmt->fetch()) {
                $delete="";
                if($row[5]==$user_no){
                    $delete= "<a class='del' href='system/delete.php?tweet_no={$row[6]}&from=timeline'>削除</a>";
                }
                print 
                "<li class='tweet'>
                    <img src='image/{$row[5]}.png'>
                    <div>
                        <p>$row[4] <a href='my_timeline.php?user_no={$row[5]}'>@{$row[3]}</a></p>
                        <p>{$row[0]}</p>
                        <p class='time'>{$row[1]} {$row[2]} ".$delete."</p>
                    </div>
                </li>";
            }
            ?>
        </ul>

        <section class="pagenation">
            <?php
            for ($i = 1; $i <= $totalpage; $i++) {
                if ($page == $i) {
                    print "<a>";
                } else {
                    print "<a href='?page={$i}'>";
                }
                print "{$i} </a> &emsp;";
            }
            ?>
        </section>

    </main>
</body>

</html>