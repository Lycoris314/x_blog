<?php

if(
    isset($_GET["user_id"]) && $_GET["user_id"] != "" &&
    isset($_GET["password"]) && $_GET["password"] !=""
){
    $user_id = $_GET["user_id"];
    $password = $_GET["password"];
}else{
    header("location:./login.html");
    exit();
}

try {
    require_once("./DBInfo.php");
    $pdo = new PDO(DBInfo::DNS, DBInfo::USER, DBInfo::PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql="select user_no from user where id_name=? and password=?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1,$user_id);
    $stmt->bindValue(2,hash("sha256",$password));

    $stmt->execute();
    if($row = $stmt->fetch()){
        print "success";
    };
    $pdo=null;

} catch (PDOException $e) {
    print $e->getMessage();
    $pdo = null;
}