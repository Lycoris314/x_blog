<?php
require_once("../helper_function.php");

if (
    regular_get_id_name("new_id_name") &&
    regular_get_password("new_password") 
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

    $sql = "insert into user values(NULL,?,?,?,'')";
    $stmt = $pdo->prepare($sql);
    bindValues($stmt, $new_id_name, $new_id_name, hash("sha256", $new_password));

    $pdo->beginTransaction();
    $stmt->execute();
    $user_no=(int)$pdo->lastInsertId(); 
    $pdo->commit();
    $pdo = null;

    //アイコンはデフォルトに設定
    copy("../image/default.png","../image/{$user_no}.png");

    session_start();
    session_regenerate_id(true);
    $_SESSION["user_no"] = $user_no;

    header("location:../timeline.php");

} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $pdo = null;
    header("location:../error.php?msg=アカウント作成に失敗しました。");
}
