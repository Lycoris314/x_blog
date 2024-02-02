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

    [$id_name, $free_name]=select_from_user_no($user_no, "id_name, free_name");
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
    <script src="account_edit.js"></script>
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

    <main class="account_edit">
        <h2>アカウント編集</h2>
        <form id="form">
            <table>
                <tbody>
                    <tr>
                        <td>現在のユーザ名</td>
                        <td>
                            <?= $id_name ?>
                        </td>
                    </tr>
                    <tr>
                        <td>新しいユーザ名</td>
                        <td><input id="new_id_name" type="text" name="new_id_name" value="<?= $id_name ?>" required
                                maxlength="16" pattern="[a-zA-Z0-9]{1,16}">
                            <p class=small_font>ユーザ名は1字以上16字以内の英数字です。</p>
                        </td>
                    </tr>
                    <tr>
                        <td>現在のパスワード</td>
                        <td><input id="password" type="password" name="password" required minlength="4" maxlength="12"
                                pattern="[a-zA-Z0-9]{4,12}"></td>
                    </tr>
                    <tr>
                        <td>新しいパスワード</td>
                        <td><input id="new_password" type="password" name="new_password" required minlength="4"
                                maxlength="12" pattern="[a-zA-Z0-9]{4,12}">
                            <p class=small_font>パスワードは4字以上16字以内の英数字です。</p>
                        </td>
                    </tr>
                    <tr>
                        <td>新しいパスワード確認</td>
                        <td><input id="cfm_password" type="password" required minlength="4" maxlength="12"
                                pattern="[a-zA-Z0-9]{4,12}"></td>
                    </tr>
                </tbody>
            </table>
            <button class="center" form="form">確認</button>
            <p class="wrong center"></p>
        </form>

    </main>

    <div class="cfm">
        <h2>アカウント編集</h2>
        <table>
            <tbody>
                <tr>
                    <td>現在のユーザ名</td>
                    <td>
                        <?= $id_name ?>
                    </td>
                </tr>
                <tr>
                    <td>新しいユーザ名</td>
                    <td>
                        <p class="id_name"></p>
                    </td>
                </tr>
                <tr>
                    <td>新しいパスワード</td>
                    <td>セキュリティのため表示しません</td>
                </tr>
            </tbody>
        </table>
        <div class="buttons">
            <button type="button" class="back">戻る</button>

            <form id="form2" action="system/system_account_edit_exe.php" method="get">

                <input type="hidden" name=new_id_name id="new_id_name2">
                <input type="hidden" name=password id="password2">
                <input type="hidden" name=new_password id="new_password2">

                <button>送信</button>
            </form>
        </div>
    </div>
</body>

</html>
