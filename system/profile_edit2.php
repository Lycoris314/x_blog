<?php
session_start();
session_regenerate_id(true);
if (isset($_SESSION["user_no"]) && $_SESSION["user_no"] != "") {

    $user_no = $_SESSION["user_no"];

    $upfileName = $_FILES["upfile"]["name"];
    $upfileTemp = $_FILES["upfile"]["tmp_name"];
    
    $new_free_name =$_POST["free_name"];
    $new_profile =$_POST["profile"];

} else {
    header("location:error.html");
    exit();
}

try {
    require_once("./DBInfo.php");
    $pdo = new PDO(DBInfo::DNS, DBInfo::USER, DBInfo::PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "update user set free_name=?, profile=? where user_no=?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $new_free_name);
    $stmt->bindValue(2, $new_profile);
    $stmt->bindValue(3, $user_no);

    $pdo->beginTransaction();
    $stmt->execute();
    $pdo->commit();
    $pdo = null;
} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $pdo = null;
    //header("location:../error.html");
}   



$folderPath="../image/";
$fullPath=$folderPath . $user_no . ".png";

if(move_uploaded_file($upfileTemp, $fullPath)==false){
    print "何の成果も得られませんでした。";
};

header("location:../my_timeline.php?user_no={$user_no}");