<?php
require_once("../helper_function.php");

session_start();
session_regenerate_id(true);

if (nonempty_session("user_no") &&
    regular_get_id_name("new_id_name") &&
    regular_get_password("new_password") &&
    regular_get_password("password")
    ){
    $user_no = $_SESSION["user_no"];
    $new_id_name = $_GET["new_id_name"];
    $new_password = $_GET["new_password"];
    $password = $_GET["password"];
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
    bindValues($stmt, $user_no, hash("sha256", $password));

    $stmt->execute();
    if ($row = $stmt->fetch()) {

        $sql = "update user set id_name=? ,password=? where user_no=? ";
        $stmt = $pdo->prepare($sql);
        bindValues($stmt, $new_id_name, hash("sha256", $new_password), $user_no);

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
    header("location:../error.php?msg=更新に失敗しました。");
}
