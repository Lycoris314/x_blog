<?php
//多分もう不要になった
session_start();
session_regenerate_id();
if (isset($_SESSION["user_no"]) && $_SESSION["user_no"] != "") {
    $user_no = $_SESSION["user_no"];
} else {
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
    <script src="account_edit_cfm.js"></script>
</head>
<body>
    <header></header>
    <main>
        <h2>アカウント編集</h2>
        <p>ユーザ名</p>
        <p></p>
        <p>パスワード</p>
        <p>セキュリティのため表示しません</p>
        <button id="back">戻る</button>
        <button id="submit">送信</button>
    </main>
</body>
</html>