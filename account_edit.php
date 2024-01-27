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

    $sql = "select id_name, free_name from user where user_no=?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $user_no);
    $stmt->execute();
    $row = $stmt->fetch();
    $id_name = $row[0];
    $free_name = $row[1];

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
                        <td><input id="password" type="text" name="password" required minlength="4" maxlength="12"
                                pattern="[a-zA-Z0-9]{4,12}"></td>
                    </tr>
                    <tr>
                        <td>新しいパスワード</td>
                        <td><input id="new_password" type="text" name="new_password" required minlength="4"
                                maxlength="12" pattern="[a-zA-Z0-9]{4,12}">
                                <p class=small_font>パスワードは4字以上16字以内の英数字です。</p>
                            </td>
                    </tr>
                    <tr>
                        <td>新しいパスワード確認</td>
                        <td><input id="cfm_password" type="text" required minlength="4" maxlength="12"
                                pattern="[a-zA-Z0-9]{4,12}"></td>
                    </tr>
                </tbody>
            </table>
            <button form="form">確認</button>
            <p class="wrong"></p>
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
        <button type="button" class="back">戻る</button>

        <form id="form2" action="system/system_account_edit_exe.php" method="get">

            <input type="hidden" name=new_id_name id="new_id_name2">
            <input type="hidden" name=password id="password2">
            <input type="hidden" name=new_password id="new_password2">

            <button>送信</button>
        </form>
    </div>
</body>

</html>