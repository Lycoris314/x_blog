<?php
require_once("../helper_function.php");

session_start();
session_regenerate_id(true);
if (
    regular_get_id_name("new_id_name") &&
    nonempty_get("password") &&
    regular_get_password("new_password") &&
    nonempty_session("user_no")
) {
    $new_id_name = $_GET["new_id_name"];
    $password = $_GET["password"];
    $new_password = $_GET["new_password"];
    $user_no = $_SESSION["user_no"];
} else {
    header("location:../error.php");
    exit();
}

try {
    require_once("./DBInfo.php");
    $pdo = new PDO(DBInfo::DNS, DBInfo::USER, DBInfo::PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql="select user_no from user where user_no=? and password=?";
    $stmt = $pdo->prepare($sql);
    bindValues($stmt, $user_no, hash("sha256", $password));

    $stmt->execute();
    if($row = $stmt->fetch()){

        $sql="select user_no from user where id_name=? and not user_no=?";
        $stmt = $pdo->prepare($sql);
        bindValues($stmt, $new_id_name, $user_no);

        $stmt->execute();
        if($row = $stmt->fetch()){
            print "duplicate";
        } else {
            print "success";
        }
    }else{
        print "wrong password";
    };
    $pdo=null;

} catch (PDOException $e) {
    $pdo = null;
    header("location:../error.php");
    exit();
}