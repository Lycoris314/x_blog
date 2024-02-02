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

    $free_name= select_from_user_no($user_no, "free_name")[0];

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
            <li><a href="notice.php">通知<?= $text_uncfm ?></a></li>
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