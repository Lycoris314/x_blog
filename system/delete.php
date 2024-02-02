<?php
require_once("../helper_function.php");

session_start();
session_regenerate_id(true);

if (
    nonempty_session("user_no") &&
    nonempty_get("tweet_no") &&
    nonempty_get("from")
) {
    $user_no = $_SESSION["user_no"];
    $tweet_no = $_GET["tweet_no"];
    $from = $_GET["from"];
} else {
    header("location:../error.php");
    exit();
}

try {
    require_once("./DBInfo.php");
    $pdo = new PDO(DBInfo::DNS, DBInfo::USER, DBInfo::PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql="select date, time from tweet where tweet_no=?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1,$tweet_no);
    $stmt->execute();
    $row= $stmt->fetch();
    $date=$row[0];
    $time=$row[1];

    $sql="delete from tweet where tweet_no=?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1,$tweet_no);

    $sql="insert into tweet values(?,?,?,'deleted','Xblog','Xblog',0)";
    $stmt2 =$pdo->prepare($sql);
    $stmt2->bindValue(1,$tweet_no);
    $stmt2->bindValue(2,$date);
    $stmt2->bindValue(3,$time);

    $pdo->beginTransaction();
    $stmt->execute();
    $stmt2->execute();
    $pdo->commit();
    $pdo=null;
    header("location:../{$from}.php?user_no={$user_no}");

} catch (PDOException $e) {
    $pdo=null;
    header("location:../error.php");
    exit();
}

