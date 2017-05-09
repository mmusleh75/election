<?php require_once('db/conn.php'); ?>

<!DOCTYPE HTML>
<html>
<head>

<title>Election</title>
<link rel="stylesheet" type="text/css" href="css/style2.css" />
<link rel="stylesheet" type="text/css" href="css/form-style.css" />

<meta http-equiv="refresh" content="5">

</head>
<body>


<?php
$db_found = mysql_select_db($database, $conn);

if (!$db_found) {
	print "Database NOT Found ";
	mysql_close($conn);
}

$NomineesSQL = sprintf("SELECT n.name, COUNT(1) AS votes
FROM nominees n
INNER JOIN ballots b
	ON b.nominee_id = n.id
GROUP BY n.name
ORDER BY 2 DESC;");

$BallotsSQL = sprintf("SELECT COUNT(DISTINCT ballot_id) AS ballots FROM ballots;");

$NomineesResult = mysql_query($NomineesSQL);
$BallotsResult = mysql_query($BallotsSQL);
$TotalBallots = mysql_fetch_assoc($BallotsResult)['ballots'];

?>

<div class="form-style-5">


<div id="holder">

	<div>
		
		<table align="center" width=50% border=1 cellpadding="10">
		<tr>
		<td colspan=2><font class="name"><b>Total Ballots: <?php echo ($TotalBallots); ?></b></font></td>
		</tr>
		
		<tr bgcolor="#e9ecee">
		<td><font class="name"><b>Nominee Name</b></font></td>
		<td><font class="name"><b>Votes</font></b></td>
		</tr>
<?php

$c=0;

while ( $db_field = mysql_fetch_assoc($NomineesResult) ) {
$c++;

?>
		<tr>
		<td><font class="name"><?php echo ($db_field['name']); ?></font></td>
		<td><font class="name"><?php echo ($db_field['votes']); ?></font></td>
		</tr>
	
<?php
}
mysql_close($conn);

?>	
		
	</div>
</div>


</div>

</body>
</html>