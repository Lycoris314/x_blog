<?php
function h($str){
    return htmlspecialchars($str,null,"UTF-8");
}

try {
    require_once("./system/DBInfo.php");
    $pdo = new PDO(DBInfo::DNS, DBInfo::USER, DBInfo::PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "select * from tweet order by date desc, time desc  limit 20";
    $stmt = $pdo->query($sql);

} catch (PDOException $e) {

}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>X blog</title>
    <link rel="stylesheet" href="common.css">
</head>
<body>
    <header>
        <h1>X blog</h1>
        <a href="login.html">ログイン</a>
    </header>
    <main>
        <h2>タイムライン</h2>
        <ul>
            <?php
            while( $row = $stmt->fetch()){
                //$row[5]=h($row[5]);
                //$row[3]=h($row[3]);
                print"<li class='tweet'>
                        <div>
                        アイコン
                        </div>
                        <div>
                        <p>{$row[5]}{$row[4]}</p>
                        <p>{$row[3]}</p>
                        <p>{$row[1]}{$row[2]}</p>
                        </div>
                     </li>";
            }
            ?>
        </ul>
    </main>
</body>
</html>