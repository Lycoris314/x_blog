<?php
function h($str){
    return htmlspecialchars($str,null,"UTF-8");
}

session_start();
session_regenerate_id(true);
if (isset($_SESSION["free_name"]) && $_SESSION["free_name"] != "") {
    $free_name = h($_SESSION["free_name"]);
    $user_no= $_SESSION["user_no"];
} else {
    header("location:timeline_no_login.php");
    exit();
}
try {
    require_once("./system/DBInfo.php");
    $pdo = new PDO(DBInfo::DNS, DBInfo::USER, DBInfo::PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "select distinct content ,date,time,id_name,free_name from tweet inner join follow on user_no=followed where user_no=? or following=? order by date desc,time desc  limit 20;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $user_no);
    $stmt->bindValue(2, $user_no);
    $stmt->execute();

} catch (PDOException $e) {
    $pdo = null;
    print $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>X blog</title>
</head>

<body>
    <header>
        <h1>X blog</h1>
        <p>ようこそ、
            <a href="my_timeline.php"><?= $free_name ?></a>さん
        </p>
        <a href="timeline.php">タイムライン</a>
        <a href="notice.php">通知</a>
        <a href="main.php">発言する</a>
        <a href="system/system_logout.php">ログアウト</a>
    </header>
    <main>
        <h2>タイムライン</h2>
        <ul>
        <?php
        while ($row = $stmt->fetch()) {
            print "<li>";
            print $row[0];
            print h($row[4]);
            print $row[3];

            print "</li>";
        }
        ?>
        </ul>
    </main>
</body>

</html>