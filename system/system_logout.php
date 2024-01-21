<?php
session_start();
session_regenerate_id(true);
$_SESSION["user_id"] = "";
$_SESSION["user_no"]= "";
$_SESSION["id_name"] = "";
$_SESSION["free_name"] = "";
$_SESSION["profile"] = "";
header("location:../login.html");