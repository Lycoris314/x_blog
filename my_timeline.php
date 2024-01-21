<?php
$free_name_get = "";
$id_name_get = "";
$profile_get = "";
if (isset($_GET["id_name"]) && $_GET["id_name"] != "") {
    $id_name_get = $_GET["id_name"];

    try {
        require_once("./system/DBInfo.php");
        $pdo = new PDO(DBInfo::DNS, DBInfo::USER, DBInfo::PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "select free_name, profile from user where id_name=?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $id_name_get);
        $stmt->execute();
        $row = $stmt->fetch();
        $free_name_get = $row[0];
        $profile_get = $row[1];

    } catch (PDOException $e) {

    }
    $pdo = null;
}

$id_name = "";
session_start();
if (
    isset($_SESSION["id_name"]) && $_SESSION["id_name"] != "" &&
    isset($_SESSION["free_name"]) && $_SESSION["free_name"] != "" &&
    isset($_SESSION["profile"])
) {
    $id_name = $_SESSION["id_name"];
    $free_name = $_SESSION["free_name"];
    $profile = $_SESSION["profile"];
}

$free_name_use = "";
if ($id_name_get == "") {
    $free_name_use = $free_name;
} else {
    $free_name_use = $free_name_get;
}

$id_name_use = "";
if ($id_name_get == "") {
    $id_name_use = $id_name;
} else {
    $id_name_use = $id_name_get;
}

$profile_use = "";
if ($id_name_get == "") {
    $profile_use = $profile;
} else {
    $profile_use = $profile_get;
}


try {
    require_once("./system/DBInfo.php");
    $pdo = new PDO(DBInfo::DNS, DBInfo::USER, DBInfo::PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "select * from tweet  where id_name=? order by date desc, time desc  limit 10";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $id_name_use);
    $stmt->execute();

} catch (PDOException $e) {

}
$pdo = null;



?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>X blog</title>
    <link rel="stylesheet" href="my_timeline.css">
</head>

<body>
    <header>
        <p>X blog</p>

        <?php
        if ($id_name != "") {
            print "
        <p>ようこそ、
            <a href='my_timeline.php'>
                {$free_name}
            </a>さん
        </p>
        <a href='timeline.php'>タイムライン</a>
        <a href='notice.php'>通知</a>
        <a href='main.php'>発言する</a>
        <a href='system/system_logout.php'>ログアウト</a>
        ";
        }
        ?>
    </header>
    <main>
        <section class="section1">
            <h2>
                <?= $free_name_use ?>さんのプロフィール
            </h2>
            <div>
                <h3>アイコン</h3>
                <h3>ユーザ名</h3>
                <p>
                    <?= $id_name_use ?>
                </p>
                <h3>表示名</h3>
                <p>
                    <?= $free_name_use ?>
                </p>
                <h3>自己紹介</h3>
                <p>
                    <?= $profile_use ?>
                </p>
                <a href="following.php">フォロー一覧</a>
                <?php
                if ($id_name == $id_name_use) {
                    print "
                <a href=''>アカウント編集</a>
                <a href=''>プロフィール編集</a>
                ";
                }
                ?>
            </div>
        </section>
        <section>
            <h2>発言</h2>
            <ul>
                <?php
                while ($row = $stmt->fetch()) {
                    print("<li>");
                    print("{$row[1]}");
                    print("{$row[2]}");
                    print("{$row[3]}");
                    print("</li>");
                }
                ?>
            </ul>
        </section>
    </main>
</body>

</html>