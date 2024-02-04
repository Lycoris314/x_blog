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

    [$free_name, $profile] = select_from_user_no($user_no, "free_name, profile");

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
    <script src="profile_edit.js"></script>
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

    <main class="profile_edit">
        <h2>プロフィール編集</h2>
        <form id="form" action="system/profile_edit2.php" method="post" enctype="multipart/form-data">
            <p>アイコン</p>
            <img class="icon" src="image/<?= $user_no ?>.png" alt="アイコン">
            <input type="file" id="upfile" name="upfile">
            <p class="small_font">png形式(3MB以下)のみ利用できます。</p>

            <table class="profile_edit">
                <tr>
                    <td>表示名</td>
                    <td><input name="free_name" type="text" value=<?= $free_name ?> maxlength="20">
                        <p class="small_font">表示名は1文字以上20文字以内です。</p>
                    </td>
                </tr>
                <tr>
                    <td>自己紹介</td>
                    <td><textarea name="profile" id="" cols="30" rows="10"><?= $profile ?></textarea></td>
                </tr>
            </table>
            <div class="buttons">
                <button type="reset">キャンセル</button>
                <button class="to_cfm" type="button">確認</button>
            </div>
        </form>
    </main>

    <div class="cfm">
        <h2>プロフィール編集</h2>
        <p>アイコン</p>
        <img class="icon" src="image/<?= $user_no ?>.png" alt="人物アイコン">
        <table class="profile_edit">
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
        <div class="buttons">
            <button class="back" type="button">戻る</button>
            <button form="form">送信</button>
        </div>

    </div>
</body>

</html>