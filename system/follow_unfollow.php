<?php
session_start();
session_regenerate_id(true);
$onoff="";
if (isset($_SESSION["user_no"]) && $_SESSION["user_no"] != "" &&
    isset($_GET["ed"]) && $_GET["ed"] !="" &&
    isset($_GET["onoff"]) && $_GET["onoff"] !="" 
    ){
    $user_no = $_SESSION["user_no"];
    $ed = $_GET["ed"];
    $onoff = $_GET["onoff"];
    
} else {
    
    header("location:../error.php");
    exit();
}

try {
    require_once("./DBInfo.php");
    $pdo = new PDO(DBInfo::DNS, DBInfo::USER, DBInfo::PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql="";
    if($onoff=="on"){
        $sql="delete from follow where following=? and followed=?";
    }
    else if($onoff=="off"){
        $sql="insert into follow values(NULL,?,?)";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $user_no);
    $stmt->bindValue(2, $ed);
  
    $pdo->beginTransaction();
    $stmt->execute();
    $pdo->commit();


    if($onoff=="off"){

        //ツイート番号の事前取得
        $sql = "select tweet_no from tweet order by tweet_no desc limit 1";
        $stmt = $pdo->query($sql);
        $stmt->execute();
        $row = $stmt->fetch();
        $tweet_no = $row[0] + 1;

        $sql = "select id_name from user where user_no=?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $user_no);
        $stmt->execute();
        $row = $stmt->fetch();
        $id_name=$row[0];


        $content="お知らせ：<a href='my_timeline.php?user_no={$user_no}'>@{$id_name}</a> さんにフォローされました。";
        $sql2="insert into tweet values(?,?,?,?,?,?,?)";
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->bindValue(1, $tweet_no);
        $stmt2->bindValue(2, date("Y-m-d"));
        $stmt2->bindValue(3, date("H:i:s"));
        $stmt2->bindValue(4, $content);
        $stmt2->bindValue(5, "Xblog");
        $stmt2->bindValue(6, "Xblog");
        $stmt2->bindValue(7, 0);

        $sql3="insert into notice values(NULL,?,?,0)";
        $stmt3 = $pdo->prepare($sql3);
        $stmt3->bindValue(1, $tweet_no);
        $stmt3->bindValue(2, $ed);

        $pdo->beginTransaction();
        $stmt2->execute();
        $stmt3->execute();
        $pdo->commit();
    }

    $pdo = null;
    print "success";

} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $pdo = null;
    header("location:../error.php");
    //print($e->getMessage());
}