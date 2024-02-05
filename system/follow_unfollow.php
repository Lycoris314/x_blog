<?php
require_once("../helper_function.php");

session_start();
session_regenerate_id(true);

$onoff = "";
if (
    nonempty_session("user_no") &&
    nonempty_get("ed") &&
    nonempty_get("onoff")
) {
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

    $sql = "";
    if ($onoff == "on") {
        $sql = "delete from follow where following=? and followed=?";
    } else if ($onoff == "off") {
        $sql = "insert into follow values(NULL,?,?)";
    }
    $stmt = $pdo->prepare($sql);
    bindValues($stmt, $user_no, $ed);

    $pdo->beginTransaction();
    $stmt->execute();
    $pdo->commit();


    if ($onoff == "off") {

        $id_name = select_from_user_no($user_no, "id_name")[0];

        $content = "お知らせ：<a href='my_timeline.php?user_no={$user_no}'>@{$id_name}</a> さんにフォローされました。";
        $sql2 = "insert into tweet values(NULL,?,?,?,?,?,0)";
        $stmt2 = $pdo->prepare($sql2);
        bindValues($stmt2, date("Y-m-d"), date("H:i:s"), $content, "Xblog", "Xblog");

        $sql3 = "insert into notice values(NULL,?,?,0)";
        $stmt3 = $pdo->prepare($sql3);
        bindValues($stmt3, $tweet_no, $ed);

        $pdo->beginTransaction();
        $stmt2->execute();
        $tweet_no=(int)$pdo->lastInsertId();
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
}