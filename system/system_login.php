<?php

if(
    isset($_GET["id_name"]) && $_GET["id_name"] != "" &&
    isset($_GET["password"]) && $_GET["password"] !=""
){
    $id_name = $_GET["id_name"];
    $password = $_GET["password"];
}else{
    header("location:./login.html");
    exit();
}

try {
    require_once("./DBInfo.php");
    $pdo = new PDO(DBInfo::DNS, DBInfo::USER, DBInfo::PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql="select user_no, free_name, profile from user where id_name=? and password=?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1,$id_name);
    $stmt->bindValue(2,hash("sha256",$password));

    $stmt->execute();
    if($row = $stmt->fetch()){
        
        //$sql2="select free_name, profile from user where user_no={$row[0]}";
        //$row2 = $pdo->query($sql2)->fetch();

        //$sql3="select profile from user where user_no={$row[0]}";
        //$row3 = $pdo->query($sql3)->fetch();

        session_start();
        session_regenerate_id(true);
        $_SESSION["user_no"]= $row[0];
        $_SESSION["id_name"] = $id_name;
        $_SESSION["free_name"] = $row[1];
        $_SESSION["profile"] = $row[2];
        print "success";
    };
    $pdo=null;

} catch (PDOException $e) {
    print $e->getMessage();
    $pdo = null;
}