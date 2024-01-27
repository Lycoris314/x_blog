<?php

session_start();
session_regenerate_id(true);
if (isset($_SESSION["user_no"]) && $_SESSION["user_no"] != "") {
    $user_no = $_SESSION["user_no"];
} else {
    header("location:error.php");
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

    require_once("uncfm_no.php");
    /*
        $sql_uncfm = "select count(notice_no) from notice where user_no=? and confirm=0";
        $stmt_uncfm = $pdo->prepare($sql_uncfm);
        $stmt_uncfm->bindValue(1, $user_no);
        $stmt_uncfm->execute();

        $row_uncfm = $stmt_uncfm->fetch();
        $uncfm_no = $row_uncfm[0];
        $text_uncfm="";
        if($uncfm_no !=0){
            $text_uncfm="({$uncfm_no})";
        }
    */
    $pdo = null;

} catch (PDOException $e) {
    $pdo = null;
    header("location:error.html");
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
    <script src="main.js"></script>
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
                    <?= $text_uncfm ?>
                </a></li>
            <li><a href="main.php">発言する</a></li>
            <li><a href="system/system_logout.php">ログアウト</a></li>
        </ul>
    </header>

    <main class="main">
        <h2>発言する</h2>
        <form id=tweet action="system/tweet.php" method="post">
            <textarea name="content" cols="40" rows="15" required></textarea>

            <p class='main'><button>送信</button></p>
        </form>
        <p id="tweeted"></p>
    </main>
</body>

</html>