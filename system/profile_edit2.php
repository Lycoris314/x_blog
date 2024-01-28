<?php

function h($str)
{
    return htmlspecialchars($str, null, "UTF-8");
}

session_start();
session_regenerate_id(true);
if (
    isset($_SESSION["user_no"]) && $_SESSION["user_no"] != "" &&
    isset($_POST["free_name"]) && $_POST["free_name"] != "" && strlen($_POST["free_name"])<=20 && 
    isset($_POST["profile"])
) {
    $user_no = $_SESSION["user_no"];
    $new_free_name = $_POST["free_name"];
    $new_free_name = h($new_free_name);
    $new_profile = $_POST["profile"];
    $new_profile = h($new_profile);

} else {
    header("location:../error.php");
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
    header("location:../error.php");
    exit();
}


//仮にアップロードしなくても空ではないみたい
if (isset($_FILES["upfile"]) && $_FILES["upfile"] != "") {
    $upfileName = $_FILES["upfile"]["name"];
    $upfileTemp = $_FILES["upfile"]["tmp_name"];
    $upfileSize = $_FILES["upfile"]["size"];
    $upfileType = $_FILES["upfile"]["type"];
    $upfileError = $_FILES["upfile"]["error"];
} else {
    header("location:../error.php");
    exit();
}

//アップしていない場合ここで終了
if($upfileSize ==0){
    header("location:../my_timeline.php?user_no={$user_no}");
    exit();
};


$errorFlag = false;
if ($upfileTemp == "") {
    $errorFlag = true;
}

$byte = 1000 * 1000 * 3; 
if ($upfileSize > $byte) {
    $errorFlag = true;
}

if ($upfileType != "image/png") {
    $errorFlag = true;
}

if ($upfileError != UPLOAD_ERR_OK) {
    $errorFlag = true;
}

if ($errorFlag) {
    header("location:../error.php?msg=画像のアップロードに失敗しました。");

} else if (!$errorFlag) {
    $folderPath = "../image/";
    $fullPath = $folderPath . $user_no . ".png";

    if (move_uploaded_file($upfileTemp, $fullPath) == false) {
        header("location:../error.php?msg=画像のアップロードに失敗しました。");
    } else {
        header("location:../my_timeline.php?user_no={$user_no}");
    }
}