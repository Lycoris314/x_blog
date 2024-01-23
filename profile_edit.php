<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>X blog</title>
    <script src="profile_edit.js"></script>
</head>

<body>
    <header>

    </header>
    <main>
        <h2>プロフィール編集</h2>
        <form action="" method="post" enctype="multipart/form-data">
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
            <button>確認</button>
        </form>
    </main>
</body>

</html>