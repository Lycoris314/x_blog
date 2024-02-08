<?php
require_once("../helper_function.php");

$user_no = "";
$content = "";

session_start();
session_regenerate_id(true);
if (
    nonempty_session("user_no") &&
    nonempty_post("content")
) {
    $content = $_POST["content"];
    $user_no = $_SESSION["user_no"];
} else {
    header("location:../error.php");
    exit();
}


try {
    require_once("./DBInfo.php");
    $pdo = new PDO(DBInfo::DNS, DBInfo::USER, DBInfo::PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $content = h($content);
    $content .= " ";

    $list = new ArrayObject();

    $content =
        preg_replace_callback(
            "/(@([a-zA-Z0-9]{1,16}))(\s)/",
            function ($m) {

                global $pdo, $list;

                $sql = "select user_no from user where id_name=?";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(1, $m[2]);
                $stmt->execute();

                if ($row = $stmt->fetch()) {

                    $list->append($row[0]);
                    return "<a href='my_timeline.php?user_no={$row[0]}'>{$m[1]}</a>{$m[3]}";
                } else {
                    return $m[0];
                }
            },
            $content
        );

        $content =
            preg_replace_callback(
                "|https?://[\w!?/+\-~;.,*&@#$%()'[\]]+|",
                function ($m) {
                    return "<a href={$m[0]}>{$m[0]}</a>";
                },
                $content
            );
    
    $content = str_replace(PHP_EOL, "<br>", $content);

    [$id_name, $free_name] = select_from_user_no($user_no, "id_name, free_name");


    $sql = "insert into tweet values(NULL,?,?,?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    bindValues($stmt, date("Y-m-d"), date("H:i:s"), $content ,$id_name, $free_name, $user_no);

    $pdo->beginTransaction();
    $stmt->execute();
    $tweet_no=(int)$pdo->lastInsertId(); 
    $pdo->commit();

    $list = array_unique((array) $list);


    foreach ($list as $value) {

        $sql = "insert into notice values(NULL,?,?,0)";
        $stmt = $pdo->prepare($sql);
        bindValues($stmt, $tweet_no, $value);

        $pdo->beginTransaction();
        $stmt->execute();
        $pdo->commit();

    }
    $pdo = null;
    print "success";

} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $pdo = null;
    header("location:../error.php");
}
