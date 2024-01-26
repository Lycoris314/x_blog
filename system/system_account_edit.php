<?php
session_start();
session_regenerate_id(true);
if (
    isset($_GET["new_id_name"]) && $_GET["new_id_name"] != "" &&
    isset($_GET["password"]) && $_GET["password"] != "" &&
    isset($_GET["new_password"]) && $_GET["new_password"] != ""&&
    isset($_SESSION["user_no"]) && $_SESSION["user_no"] != ""
) {
    $new_id_name = $_GET["new_id_name"];
    $password = $_GET["password"];
    $new_password = $_GET["new_password"];
    $user_no = $_SESSION["user_no"];
} else {
    header("location:../error.html");
    exit();
}

try {
    require_once("./DBInfo.php");
    $pdo = new PDO(DBInfo::DNS, DBInfo::USER, DBInfo::PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql="select user_no from user where user_no=? and password=?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1,$user_no);
    $stmt->bindValue(2,hash("sha256",$password));

    $stmt->execute();
    if($row = $stmt->fetch()){
        //新しいIDとパスワードの適正チェック

        //$_SESSION["new_id_name"]=$new_id_name;
        //$_SESSION["new_password"]=$new_password;

        print "success";
    };
    $pdo=null;

} catch (PDOException $e) {
    print $e->getMessage();
    $pdo = null;
    header("location:../error.html");
    exit();
}