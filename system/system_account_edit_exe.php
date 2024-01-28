<?php

session_start();
session_regenerate_id(true);
if (isset($_SESSION["user_no"]) && $_SESSION["user_no"]!="" &&
    isset($_GET["new_id_name"]) && $_GET["new_id_name"]!="" && 
    isset($_GET["new_password"]) && $_GET["new_password"]!="" && 
    isset($_GET["password"]) && $_GET["password"]!="" ){
    $new_id_name = $_GET["new_id_name"];
    $new_password = $_GET["new_password"];
    $password = $_GET["password"];
    $user_no = $_SESSION["user_no"];
}else{
    header("location:../error.php");
    exit();
}

try {
    require_once("./DBInfo.php");
    $pdo = new PDO(DBInfo::DNS, DBInfo::USER, DBInfo::PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "select user_no from user where user_no=? and password=?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $user_no);
    $stmt->bindValue(2, hash("sha256", $password));

    $stmt->execute();
    if ($row = $stmt->fetch()) {

        $sql = "update user set id_name=? ,password=? where user_no=? ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(3, $user_no);
        $stmt->bindValue(2, hash("sha256", $new_password));
        $stmt->bindValue(1, $new_id_name);

        $pdo->beginTransaction();
        $stmt->execute();
        $pdo->commit();
        $pdo = null;
        header("location:../timeline.php");

    } else {
        $pdo = null;
        header("location:../error.php");
        exit();
    }
} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $pdo = null;
    print "あ、". $e->getMessage() ."です。";
    header("location:../error.php?msg=更新に失敗しました。");
}
