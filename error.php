<?php
    $msg="エラーが発生しました。";
    if(isset($_GET["msg"]) && $_GET["msg"] != ""){
        $msg = $_GET["msg"];
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>X blog</title>
</head>
<body>
    <p><?= $msg ?></p>
</body>
</html>