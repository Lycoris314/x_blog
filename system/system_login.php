<?php
//ログイン時にユーザ名とパスワードの組のチェックをするページ

if(
    isset($_GET["id_name"]) && $_GET["id_name"] != "" &&
    isset($_GET["password"]) && $_GET["password"] !=""
){
    $id_name = $_GET["id_name"];
    $password = $_GET["password"];
}else{
    header("location:../error.php");
    exit();
}

try {
    require_once("./DBInfo.php");
    $pdo = new PDO(DBInfo::DNS, DBInfo::USER, DBInfo::PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   
    $sql="select user_no from user where id_name=? and password=?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1,$id_name);
    $stmt->bindValue(2,hash("sha256",$password));
    $stmt->execute();
    if($row = $stmt->fetch()){

        session_start();
        session_regenerate_id(true);
        $_SESSION["user_no"]= $row[0]; //これに値がセットされていることがログイン中を表す。

        print "success";
    };
    $pdo=null;

} catch (PDOException $e) {
    $pdo = null;
    header("location:../error.php");
}