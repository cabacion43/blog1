<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_blogconnect = "localhost";
$database_blogconnect = "blog";
$username_blogconnect = "root";
$password_blogconnect = "";
$blogconnect = mysql_pconnect($hostname_blogconnect, $username_blogconnect, $password_blogconnect) or trigger_error(mysql_error(),E_USER_ERROR); 
?>