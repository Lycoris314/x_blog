<?php
//リダイレクト対策のち、IDとパスワードの変更を完了する。
session_start();
session_regenerate_id(true);
if(true){
    $new_id_name=$_SESSION["new_id_name"];
    $new_password=$_SESSION["new_password"];
    $user_no=$_SESSION["user_no"];
}

try {
    require_once("./DBInfo.php");
    $pdo = new PDO(DBInfo::DNS, DBInfo::USER, DBInfo::PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql="update user set password=? where user_no=? ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(2,$user_no);
    $stmt->bindValue(1,hash("sha256",$new_password));

    $pdo->beginTransaction();
    $stmt->execute();
    $pdo->commit();   ;
    $pdo = null;
    header("location:../main.php");

} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction() ) {
        $pdo->rollBack();
        $pdo = null;
    }
}
