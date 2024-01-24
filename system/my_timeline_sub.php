<?php

if (isset($_GET["id_name"]) && $_GET["id_name"] != "") {
    $id_name = $_GET["id_name"];
}else{
    header("location:error.html");
    exit();
}

try {
    require_once("./DBInfo.php");
    $pdo = new PDO(DBInfo::DNS, DBInfo::USER, DBInfo::PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "select user_no from user where id_name= ?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $id_name);
    $stmt->execute();
    $row = $stmt->fetch();
    $pdo=null;

    header("location:../my_timeline.php?user_no={$row[0]}");

} catch (PDOException $e) {
    $pdo = null;
    header("location:error.html");
    exit();
}

