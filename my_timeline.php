<?php

$page = 1;
if (isset($_GET["page"]) && is_numeric($_GET["page"])) {
    $page = (int) $_GET["page"];
}
$from = ($page - 1) * 10;


//タイムラインなどを表示する番号(必須)
if (isset($_GET["user_no"]) && $_GET["user_no"] != "") {
    $user_no_show = $_GET["user_no"];
} else {
    header("location:error.php");
    exit();
}

$user_no = ""; //ログインしている番号
session_start();
session_regenerate_id(true);
if (isset($_SESSION["user_no"]) && $_SESSION["user_no"] != "") {
    $user_no = $_SESSION["user_no"];
}

$follow_rel = "";

try {
    require_once("./system/DBInfo.php");
    $pdo = new PDO(DBInfo::DNS, DBInfo::USER, DBInfo::PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    require_once("uncfm_no.php");

    //ログインしている場合
    if ($user_no != "") {

        $sql = "select free_name from user where user_no=?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $user_no);
        $stmt->execute();
        $row = $stmt->fetch();
        $free_name = $row[0];
        $row = "";

        $sql = "select follow_no from follow where following=? and followed=?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $user_no);
        $stmt->bindValue(2, $user_no_show);
        $stmt->execute();

        if ($row = $stmt->fetch()) {
            $follow_rel = true;
        } else {
            $follow_rel = false;
        }

    }

    $sql = "select id_name, free_name, profile from user where user_no=?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $user_no_show);
    $stmt->execute();
    $row = $stmt->fetch();
    $id_name_show = $row[0];
    $free_name_show = $row[1];
    $profile_show = $row[2];
    $row = "";


    $sql = "select count(tweet_no) from tweet where user_no=?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $user_no_show);
    $stmt->execute();
    $row = $stmt->fetch();
    $totalpage = ceil((int) $row[0] / 10);
    $row = "";


    $sql = "select count(follow_no) from follow where following=?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $user_no_show);
    $stmt->execute();
    $row = $stmt->fetch();
    $following_count = $row[0];
    $row = "";


    $sql = "select * from tweet  where user_no=? order by date desc, time desc  limit {$from},10";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $user_no_show);
    $stmt->execute();

    $pdo = null;

} catch (PDOException $e) {
    $pdo = null;
    header("location:./error.php");
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
    <script src="my_timeline.js"></script>
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
            <li><a href='notice.php'>通知{$text_uncfm}</a></li>
            <li><a href='main.php'>発言する</a></li>
            <li><a href='system/system_logout.php'>ログアウト</a></li>
        </ul>
        ";
        } else {
            print "
                <p class='to_login'><a href='login.html'>ログイン</a></p>
            ";
        }
        ?>
    </header>
    <main>
        <section class="section1">
            <h2>
                <?= $free_name_show ?>さんのプロフィール
            </h2>
            <div>
                <h3>アイコン</h3>
                <img src="image/<?= $user_no_show ?>.png" alt="">
                <h3>ユーザ名</h3>
                <p>
                    <?= $id_name_show ?>
                </p>
                <h3>表示名</h3>
                <p>
                    <?= $free_name_show ?>
                </p>
                <div class="self_intro">
                    <h3>自己紹介</h3>
                    <p>
                        <?= $profile_show ?>
                    </p>
                </div>
                <a href="following.php?user_no=<?= $user_no_show ?>">フォロー一覧(<?= $following_count ?>)
                </a>
                <?php
                if ($user_no == $user_no_show) {
                    print "
                        &nbsp; <a href='account_edit.php'>アカウント編集</a>
                        &nbsp; <a href='profile_edit.php'>プロフィール編集</a>
                    ";
                } else if ($follow_rel == true) {
                    print "<a class='follow' href='' data-ed='{$user_no_show}' data-onoff='on'>フォロー解除</a>";
                } else if ($follow_rel == false && $user_no != "") {
                    print "<a class='follow' href='' data-ed='{$user_no_show}' data-onoff='off'>フォローする</a>";
                }
                ?>
            </div>
        </section>

        <section class="section2">
            <h2>発言</h2>
            <ul>
                <?php
                while ($row = $stmt->fetch()) {

                    print("<li class='tweet'>");
                    print("<img src='image/{$user_no_show}.png'>");
                    print("<div>");

                    print("<p>{$row[5]} <a href='my_timeline.php?user_no={$user_no_show}'>@{$row[4]}</a></p>");
                    print("<p>{$row[3]}</p>");
                    print("<p class='time'>{$row[1]} {$row[2]}</p>");


                    print("</div>");
                    print("</li>");
                }
                ?>
            </ul>
        </section>

        <section class="pagenation">
            <?php
            for ($i = 1; $i <= $totalpage; $i++) {
                if ($page == $i) {
                    print "<a>";
                } else {
                    print "<a href='?page={$i}&user_no={$user_no_show}'>";
                }
                print "{$i} </a> &emsp;";
            }
            ?>
        </section>
    </main>
</body>

</html>