<?php
    session_start();
    session_regenerate_id(true);
    if(true){
        $id_name=$_SESSION["id_name"];
    }
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>X blog</title>
    <script src="jquery.js"></script>
    <script src="account_edit.js"></script>
</head>

<body>
    <header>

    </header>
    <main>
        <h2>アカウント編集</h2>
        <form action="system/system_account_edit.php" method="get">
            <table>
                <tbody>
                    <tr>
                        <td>現在のユーザ名</td>
                        <td><?= $id_name ?></td>
                    </tr>
                    <tr>
                        <td>新しいユーザ名</td>
                        <td><input id="new_id_name" type="text" name="new_id_name" required></td>
                    </tr>
                    <tr>
                        <td>現在のパスワード</td>
                        <td><input type="text" name="password" required></td>
                    </tr>
                    <tr>
                        <td>新しいパスワード</td>
                        <td><input id="new_password" type="text" name="new_password" required></td>
                    </tr>
                    <tr>
                        <td>新しいパスワード確認</td>
                        <td><input type="text" required></td>
                    </tr>
                </tbody>
            </table>
            <button>確認</button>
            <p></p>
        </form>
    </main>
</body>

</html>