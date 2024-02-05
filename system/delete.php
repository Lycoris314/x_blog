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

    $sql = "select tweet_no from tweet where tweet_no=? and user_no=?";
    $stmt = $pdo->prepare($sql);
    bindValues($stmt, $tweet_no, $user_no);
    $stmt->execute();
    if ($row = $stmt->fetch()) {

        $sql = "update tweet set content='deleted', id_name='Xblog', free_name='Xblog', user_no=0 where tweet_no=?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $tweet_no);

        $pdo->beginTransaction();
        $stmt->execute();
        $pdo->commit();
    }
    $pdo = null;
    header("location:../{$from}.php?user_no={$user_no}");

} catch (PDOException $e) {
    $pdo = null;
    header("location:../error.php");
    exit();
}

