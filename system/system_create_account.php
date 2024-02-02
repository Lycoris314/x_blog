<?php
require_once("../helper_function.php");

if (
    nonempty_get("new_id_name") &&
    nonempty_get("new_password")
) {
    $new_id_name = $_GET["new_id_name"];
    $new_password = $_GET["new_password"];
} else {
    header("location:../error.php");
    exit();
}

try {
    require_once("./DBInfo.php");
    $pdo = new PDO(DBInfo::DNS, DBInfo::USER, DBInfo::PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "select user_no from user where id_name=?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $new_id_name);

    $stmt->execute();
    if ($row = $stmt->fetch()) {
        print "duplicate";
    } else {
        print "success";
    }

    $pdo = null;

} catch (PDOException $e) {
    $pdo = null;
    header("location:../error.php");
    exit();
}