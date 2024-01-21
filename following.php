<?php
function h($str){
    return htmlspecialchars($str,null,"UTF-8");
}

session_start();
session_regenerate_id(true);
if (isset($_SESSION["free_name"]) && $_SESSION["free_name"] != "") {
    $user_no = $_SESSION["user_no"];
    $free_name = $_SESSION["free_name"];
    
} else {
}

try {
    require_once("./system/DBInfo.php");
    $pdo = new PDO(DBInfo::DNS, DBInfo::USER, DBInfo::PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //$sql = "select followed from follow where following=?";
    $sql = "select id_name,free_name,profile from follow inner join user on followed=user_no where following=?";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $user_no);
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
        <p>X blog</p>
        <p>ようこそ、
            <a href="my_timeline.php">
                <?= $free_name ?>
            </a>さん
        </p>
        <a href="timeline.php">タイムライン</a>
        <a href="notice.php">通知</a>
        <a href="main.php">発言する</a>
        <a href="system/system_logout.php">ログアウト</a>
    </header>
    <main>
        <h2>
            <?= $free_name ?>さんのフォロー一覧
        </h2>
        <ul>
            <?php
            
            while ($row = $stmt->fetch()) {
                $row[1]=h($row[1]);
                print("<li>");
                print("<a href='my_timeline.php?id_name={$row[0]}'>{$row[0]}</a>");
                print("{$row[1]}");
                print("{$row[2]}");
                print("</li>");
            }
            ?>
        </ul>
    </main>
</body>

</html>