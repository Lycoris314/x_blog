<?php
session_start();
session_regenerate_id(true);

$_SESSION["user_no"]= "";

header("location:../login.html");