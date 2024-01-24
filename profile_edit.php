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

    $sql = "select free_name from user where user_no= ?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $user_no);
    $stmt->execute();
    $row = $stmt->fetch();
    $free_name = $row[0];
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
    <link rel="stylesheet" href="profile_edit.css">
    <script src="jquery.js"></script>
    <script src="profile_edit.js"></script>
</head>

<body>
    <header>
        <h1>X blog</h1>
        <p>ようこそ、
            <a href="my_timeline.php?user_no=<?= $user_no ?>">
                <?= $free_name ?>
            </a>さん
        </p>
        <a href="timeline.php">タイムライン</a>
        <a href="notice.php">通知</a>
        <a href="main.php">発言する</a>
        <a href="system/system_logout.php">ログアウト</a>
    </header>

    <main>
        <h2>プロフィール編集</h2>
        <form id="form" action="system/profile_edit2.php" method="post" enctype="multipart/form-data">
            <p>アイコン</p>
            <img src="" alt="人物アイコン">
            <input type="file" id="upfile" name="upfile">

            <table>
                <tr>
                    <td>表示名</td>
                    <td><input name="free_name" type="text"></td>
                </tr>
                <tr>
                    <td>自己紹介</td>
                    <td><textarea name="profile" id="" cols="30" rows="10"></textarea></td>
                </tr>
            </table>

            <button type=reset>キャンセル</button>
            <button class="to_cfm" type="button">確認</button>
        </form>
    </main>

    <div class="cfm">
        <h2>プロフィール編集</h2>
        <p>アイコン</p>
        <img src="" alt="人物アイコン">
        <table>
            <tr>
                <td>表示名</td>
                <td>
                    <p class="free_name"></p>
                </td>
            </tr>
            <tr>
                <td>自己紹介</td>
                <td>
                    <p class="profile"></p>
                </td>
            </tr>
        </table>

        <button class="back" type="button">戻る</button>
        <button form="form">送信</button>

    </div>
</body>

</html>