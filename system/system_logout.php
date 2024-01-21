<?php
session_start();
session_regenerate_id(true);
$_SESSION["user_id"] = "";
header("location:../login.html");