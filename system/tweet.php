<?php
if (
    isset($_POST["content"]) && $_POST["content"] != "" &&
    isset($_POST["id_name"]) && $_POST["id_name"] != "" &&
    isset($_POST["free_name"]) && $_POST["free_name"]
) {
    $content = $_POST["content"];
    $id_name = $_POST["id_name"];
    $free_name = $_POST["free_name"];
    $user_no= $_POST["user_no"];
} else {
    header("location:../login.html");
    exit();
}

try {
    require_once("./DBInfo.php");
    $pdo = new PDO(DBInfo::DNS, DBInfo::USER, DBInfo::PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "insert into tweet values(NULL,?,?,?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1,date("Y-m-d"));
    $stmt->bindValue(2,date("H:i:s"));
    $stmt->bindValue(3,$content);
    $stmt->bindValue(4,$id_name);
    $stmt->bindValue(5,$free_name);
    $stmt->bindValue(6,$user_no);

    $pdo->beginTransaction();
    $stmt->execute();
    $pdo->commit();   
    print "success"; 

} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction() ) {
        $pdo->rollBack();
    }    
}
$pdo = null;