<?php

$db_conx = mysqli_connect("localhost", "DB_Username", "DB_Pass", "DB_name");

if (mysqli_connect_errno()) {
    echo mysqli_connect_error();
    exit();
}
?>
