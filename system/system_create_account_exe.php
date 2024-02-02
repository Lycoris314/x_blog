<?php
require_once("../helper_function.php");

if (
    nonempty_get("new_id_name") &&
    nonempty_get("new_password")
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
    $stmt->bindValue(1, $new_id_name);
    $stmt->bindValue(2, $new_id_name);
    $stmt->bindValue(3, hash("sha256", $new_password));

    $pdo->beginTransaction();
    $stmt->execute();
    $user_no=(int)$pdo->lastInsertId(); 
    $pdo->commit();
    $pdo = null;

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
