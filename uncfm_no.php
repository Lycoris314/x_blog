<?php

$sql_uncfm = "select count(notice_no) from notice where user_no=? and confirm=0";
$stmt_uncfm = $pdo->prepare($sql_uncfm);
$stmt_uncfm->bindValue(1, $user_no);
$stmt_uncfm->execute();

    $row_uncfm = $stmt_uncfm->fetch();
    $uncfm_no = $row_uncfm[0];
    $text_uncfm="";
    if($uncfm_no !=0){
        $text_uncfm="({$uncfm_no})";
    }

    //$text_uncfmを通知リンクに付加する。