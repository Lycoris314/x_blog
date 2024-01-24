<?php

function h($str)
{
    return htmlspecialchars($str, null, "UTF-8");
}

$user_no="";
$content="";

session_start();
session_regenerate_id(true);
if (
    isset($_SESSION["user_no"]) && $_SESSION["user_no"] != "" &&
    isset($_POST["content"]) && $_POST["content"] != ""
) {
    $content = $_POST["content"];
    $user_no = $_SESSION["user_no"];
} else {
    header("location:../error.html");
    exit();
}

//$contentの調整
$content = h($content);

$content = preg_replace("/(@(\w{1,16}))[\s]|(@(\w{1,16}))$/m", "<a href='system/my_timeline_sub.php?id_name=$2$4'>$1$3</a> " ,$content);

$content = str_replace(PHP_EOL, "<br>", $content);





try {
    require_once("./DBInfo.php");
    $pdo = new PDO(DBInfo::DNS, DBInfo::USER, DBInfo::PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "select id_name, free_name from user where user_no=?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $user_no);
    $stmt->execute();
    $row = $stmt->fetch();

    $id_name = $row[0];
    $free_name = $row[1];


    $sql = "insert into tweet values(NULL,?,?,?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, date("Y-m-d"));
    $stmt->bindValue(2, date("H:i:s"));
    $stmt->bindValue(3, $content);
    $stmt->bindValue(4, $id_name);
    $stmt->bindValue(5, $free_name);
    $stmt->bindValue(6, $user_no);

    $pdo->beginTransaction();
    $stmt->execute();
    $pdo->commit();
    $pdo = null;
    print "success";

    //リプライの通知機能を追加する。
    /*$sql2 = "select id_name from user";
    $stmt2 = $pdo->query($sql2);
    while( $row2 = $stmt2->fetch() ){
        if(str_contains("@".$content,$row2[0]." ")){
            //通知する。

        }
    }*/

} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $pdo = null;
    //header("location:../error.html");
}
