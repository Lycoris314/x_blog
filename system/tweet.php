<?php

function h($str)
{
    return htmlspecialchars($str, null, "UTF-8");
}

$user_no = "";
$content = "";

session_start();
session_regenerate_id(true);
if (
    isset($_SESSION["user_no"]) && $_SESSION["user_no"] != "" &&
    isset($_POST["content"]) && $_POST["content"] != ""
) {
    $content = $_POST["content"];
    $user_no = $_SESSION["user_no"];
} else {
    header("location:../error.php");
    exit();
}





//$matches[1],$matches[2]にリプライするユーザのid_nameが入る
//preg_match_all("/@(\w{1,16})[\s]|@(\w{1,16})$/m", $content, $matches);
/*preg_match_all("/@(\w{1,16})[\s]/m", $content, $matches);


$content = preg_replace("/(@(\w{1,16}))[\s]|(@(\w{1,16}))$/m", "<a href='system/my_timeline_sub.php?id_name=$2$4'>$1$3</a> ", $content);

$content = str_replace(PHP_EOL, "<br>", $content);*/





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

                $sql = "select user_no from user where id_name='{$m[2]}'";

                $stmt = $pdo->query($sql);
                $stmt->execute();

                if ($row = $stmt->fetch()) {

                    $list->append($row[0]);
                    return "<a href='my_timeline.php?user_no={$row[0]}'>{$m[1]}</a>$m[3]";
                } else {
                    return $m[1];
                }
            },
            $content
        );

    $content = str_replace(PHP_EOL, "<br>", $content);


    $sql = "select id_name, free_name from user where user_no=?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $user_no);
    $stmt->execute();
    $row = $stmt->fetch();

    $id_name = $row[0];
    $free_name = $row[1];

    //$list =new ArrayObject();

    /*foreach ($matches[1] as $value) {
        if ($value == "") {
            continue;
        }
        $sql = "select user_no from user where id_name=?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $value);
        $stmt->execute();
        if ($row = $stmt->fetch()) {
            print $row[0];
            $rep_user_no = $rep_user_no . $row[0] . "/";
            $list->append($row[0]);
        }
    }
    なぜか関数がうまく働かないので仕方なくコピペ
    foreach ($matches[2] as $value) {
        if ($value == "") {
            continue;
        }
        $sql = "select user_no from user where id_name=?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $value);
        $stmt->execute();
        if ($row = $stmt->fetch()) {
            $rep_user_no = $rep_user_no . $row[0] . "/";
            $list->append($row[0]);
        }
    }*/



    $sql = "select tweet_no from tweet order by tweet_no desc limit 1";
    $stmt = $pdo->query($sql);
    $stmt->execute();
    $row = $stmt->fetch();
    $tweet_no = $row[0] + 1;


    $sql = "insert into tweet values(?,?,?,?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $tweet_no);
    $stmt->bindValue(2, date("Y-m-d"));
    $stmt->bindValue(3, date("H:i:s"));
    $stmt->bindValue(4, $content);
    $stmt->bindValue(5, $id_name);
    $stmt->bindValue(6, $free_name);
    $stmt->bindValue(7, $user_no);

    $pdo->beginTransaction();
    $stmt->execute();
    $pdo->commit();

    $list = array_unique((array) $list);

    foreach ($list as $value) {
        /*if ($value == "") {
            continue;
        }*/
        $sql = "insert into notice values(NULL,?,?,0)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $tweet_no);
        $stmt->bindValue(2, $value);

        $pdo->beginTransaction();
        $stmt->execute();
        $pdo->commit();

    }
    $pdo = null;
    print "success";

    //リプライの通知機能を追加する。
    /*$sql2 = "select id_name from user";
    $stmt2 = $pdo->query($sql2);
    while( $row2 = $stmt2->fetch() ){
        if(str_contains("@".$content,$row2[0]." ")){
            //通知する。

        }
    }*/

} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $pdo = null;

    print $e->getMessage();
    //header("location:../error.php");
}
