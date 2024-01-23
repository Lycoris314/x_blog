<?php
session_start();
session_regenerate_id(true);
if (isset($_SESSION["user_no"]) && $_SESSION["user_no"] != "" &&
    isset($_GET["ed"]) && $_GET["ed"] !="" &&
    isset($_GET["onoff"]) && $_GET["onoff"] !="" 
    ){
    $user_no = $_SESSION["user_no"];
    $ed = $_GET["ed"];
    $onoff = $_GET["onoff"];
    
} else {
    
    //header("location:../error.html");
    //exit();
}

try {
    require_once("./DBInfo.php");
    $pdo = new PDO(DBInfo::DNS, DBInfo::USER, DBInfo::PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if($onoff=="on"){
        $sql="delete from follow where following=? and followed=?";
    }
    else if($onoff=="off"){
        $sql="insert into follow values(NULL,?,?)";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $user_no);
    $stmt->bindValue(2, $ed);
  
    $pdo->beginTransaction();
    $stmt->execute();
    $pdo->commit();
    $pdo = null;
    print "success";

} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $pdo = null;
    //header("location:../error.html");
}