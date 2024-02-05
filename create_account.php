<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>X blog</title>
    <link rel="stylesheet" href="common.css">
    <script src="jquery.js"></script>
    <script src="create_account.js"></script>
</head>

<body>
    <header>
        <div>
            <h1>X blog</h1>
            <img src="image/0.png" alt="X blog">
        </div>
    </header>

    <main class="account_edit">
        <h2>アカウント作成</h2>
        <form id="form">
            <table>
                <tbody>
                    <tr>
                        <td>ユーザ名</td>
                        <td><input id="new_id_name" type="text" name="new_id_name" required
                                maxlength="16" pattern="[a-zA-Z0-9]{1,16}">
                            <p class=small_font>ユーザ名は1字以上16字以内の英数字です。</p>
                        </td>
                    </tr>
                    <tr>
                        <td>パスワード</td>
                        <td><input id="new_password" type="password" name="new_password" required minlength="4"
                                maxlength="12" pattern="[a-zA-Z0-9]{4,12}">
                            <p class=small_font>パスワードは4字以上12字以内の英数字です。</p>
                        </td>
                    </tr>
                    <tr>
                        <td>パスワード確認</td>
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
        <h2>アカウント作成</h2>
        <table>
            <tbody>
                <tr>
                    <td>ユーザ名</td>
                    <td>
                        <p class="id_name"></p>
                    </td>
                </tr>
                <tr>
                    <td>パスワード</td>
                    <td>セキュリティのため表示しません</td>
                </tr>
            </tbody>
        </table>
        <div class="buttons">
            <button type="button" class="back">戻る</button>

            <form id="form2" action="system/system_create_account_exe.php" method="get">

                <input type="hidden" name=new_id_name id="new_id_name2">
                <input type="hidden" name=new_password id="new_password2">

                <button>送信</button>
            </form>
        </div>
    </div>
</body>

</html>