<?php
/*function h($str){
    return htmlspecialchars($str,null,"UTF-8");
}*/

$page = 1;
if (isset($_GET["page"]) && is_numeric($_GET["page"])) {
    $page = (int) $_GET["page"];
}
$from = ($page - 1) * 20;


try {
    require_once("./system/DBInfo.php");
    $pdo = new PDO(DBInfo::DNS, DBInfo::USER, DBInfo::PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $sql = "select count(tweet_no) from tweet where not user_no=0";
    $stmt = $pdo->query($sql);
    $stmt->execute();
    $row = $stmt->fetch();
    $totalpage = ceil((int) $row[0] / 20);


    $sql = "select * from tweet where not user_no=0 order by date desc, time desc  limit {$from},20";
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
                print"<li class='tweet'>
                        <img src='image/{$row[6]}.png'>
                        <div>
                        <p>{$row[5]}{$row[4]}</p>
                        <p>{$row[3]}</p>
                        <p>{$row[1]}{$row[2]}</p>
                        </div>
                     </li>";
            }
            ?>
        </ul>

        <section class="pagenation">
            <?php
            for ($i = 1; $i <= $totalpage; $i++) {
                if ($page == $i) {
                    print "<a>";
                }else{
                    print "<a href='?page={$i}'>";
                }
                print "{$i} </a> &emsp;";
            }
            ?>
        </section>
    </main>
</body>
</html>