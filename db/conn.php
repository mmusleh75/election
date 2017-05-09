<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname = "localhost";
$database = "atheerso_elec";
$username = "atheerso_elec";
$password = "atheerso_elec";

$conn = mysql_connect($hostname, $username, $password) or trigger_error(mysql_error(),E_USER_ERROR); 

mysql_query('SET character_set_results=utf8');
mysql_query('SET names=utf8');
mysql_query('SET character_set_client=utf8');
mysql_query('SET character_set_connection=utf8');
mysql_query('SET character_set_results=utf8');
mysql_query('SET collation_connection=utf8_general_ci');
?>