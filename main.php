<?php
session_start();
session_regenerate_id(true);
if (isset($_SESSION["id_name"]) && $_SESSION["id_name"] != "") {
    $id_name = $_SESSION["id_name"];

    try {
        require_once("./system/DBInfo.php");
        $pdo = new PDO(DBInfo::DNS, DBInfo::USER, DBInfo::PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "select free_name from user where id_name=?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $id_name);
        $stmt->execute();
        $row = $stmt->fetch();
        $free_name = $row[0];

    } catch (PDOException $e) {
        $pdo = null;
        print $e->getMessage();
    }

} else {
    header("location:login.html");
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>X blog</title>
    <script src="jquery.js"></script>
    <script src="main.js"></script>
</head>

<body>
    <header>
        <p>X blog</p>
        <p>ようこそ、
            <a href="my_timeline.php"><?= $free_name ?></a>さん
        </p>
        <a href="system/system_logout.php">ログアウト</a>
    </header>
    <main>
        <form id=tweet action="system/tweet.php" method="post">
            <textarea name="content" cols="30" rows="10" required></textarea>
            <input type="hidden" name="id_name" value=<?=$id_name ?> >
            <input type="hidden" name="free_name" value=<?=$free_name ?> >
            <button>送信</button>
        </form>
        <p id="tweeted"></p>
    </main>
</body>

</html>