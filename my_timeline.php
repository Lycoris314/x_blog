<?php
    session_start();
    if (
        isset($_SESSION["id_name"]) && $_SESSION["id_name"]!="" &&
        isset($_SESSION["free_name"]) && $_SESSION["free_name"]!= "" &&
        isset($_SESSION["profile"]) 
    ){
        $id_name=$_SESSION["id_name"];
        $free_name=$_SESSION["free_name"];
        $profile=$_SESSION["profile"];
    }

    try {
        require_once("./system/DBInfo.php");
        $pdo = new PDO(DBInfo::DNS, DBInfo::USER, DBInfo::PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql="select * from tweet order by date desc, time desc limit 10" ;
        $stmt = $pdo->query($sql);

    }catch(PDOException $e) {

    }
    $pdo=null;
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

    </header>
    <main>
        <section>
            <h2><?=$free_name ?>さんのプロフィール</h2>
            <div>
                <h3>アイコン</h3>
                <h3>ユーザ名</h3>
                <p><?=$id_name ?></p>
                <h3>表示名</h3>
                <p><?=$free_name ?></p>
                <h3>自己紹介</h3>
                <p><?=$profile ?></p>
                <a href="">フォロー一覧</a>
                <a href="">アカウント編集</a>
                <a href="">プロフィール編集</a>
            </div>
        </section>
        <section>
            <h2>発言</h2>
            <ul>
                <?php
                    while($row = $stmt->fetch()){
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