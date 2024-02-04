<?php



function select_from_user_no(int $user_no, string $get)
{
    $sql = match ($get) {
        "id_name, free_name" => "select id_name, free_name from user where user_no=?",
        "free_name" => "select free_name from user where user_no=?",
        "id_name" => "select id_name from user where user_no=?",
        "free_name, profile" => "select free_name, profile from user where user_no=?",
        "id_name, free_name, profile" => "select id_name, free_name, profile from user where user_no=?",

    };

    global $pdo;

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $user_no);
    $stmt->execute();
    $row = $stmt->fetch();

    return $row;
}

//スーパーグローバル変数に値がセットされているか確認する関数
function nonempty_get(string $param)
{
    return isset($_GET[$param]) && $_GET[$param]!="" ;
}
function nonempty_post(string $param)
{
    return isset($_POST[$param]) && $_POST[$param]!="" ;
}
function nonempty_session(string $param)
{
    return isset($_SESSION[$param]) && $_SESSION[$param]!="" ;
}





function logg($v)
{
    if ($v == "") {
        file_put_contents("../log.txt", "通過" . "\n\r", FILE_APPEND);
    } else {
        file_put_contents("../log.txt", var_export($v, true) . "\n\r", FILE_APPEND);
    }
    if ($v == "") {
        file_put_contents("log.txt", __LINE__ . "通過" . "\n\r", FILE_APPEND);
    } else {
        file_put_contents("log.txt", var_export($v, true) . "\n\r", FILE_APPEND);
    }
}