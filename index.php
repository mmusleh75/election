<?php require_once('db/conn.php'); ?>


<!DOCTYPE HTML>
<html>
<head>

<title>Elections</title>
<link rel="stylesheet" type="text/css" href="css/style2.css" />
<link rel="stylesheet" type="text/css" href="css/form-style.css" />

</head>
<body>

<!-- Here you need to do bla bla bla -->
<?php
$errorMsg = $successMsg="";
$configMax=0;


$db_found = mysql_select_db($database, $conn);

if (!$db_found) {
	print "Database NOT Found ";
	mysql_close($conn);
}

$NomineesSQL = sprintf("SELECT id, name FROM nominees order by name;");
$MaxSQL = sprintf("SELECT max FROM config;");

$NomineesResult = mysql_query($NomineesSQL,$conn);
$MaxResult = mysql_query($MaxSQL,$conn);

$configMax = mysql_fetch_assoc($MaxResult)['max'];
//$configMax = $MaxField['max'];

if (isset($_POST["submit"])) {

  if (empty($_POST["ballot_id"])) {
	$errorMsg = "Please provide Ballot #!";
	$successMsg="";
  
  }  else if(empty($_POST['nominees']))  {
    //echo("<span class='error'>You must select at least one nominee!!</span>");
	$errorMsg = "You have not selected any nominees!!";
	$successMsg="";
  } 
  else  {

	$nominees = $_POST['nominees'];
	$ballot_id = $_POST['ballot_id'];
	$voted_sql = "SELECT count(ballot_id) as cnt FROM ballots where ballot_id = '".$ballot_id."';";
	$voted_result = mysql_query($voted_sql,$conn);
	$voted_member = mysql_fetch_assoc($voted_result)['cnt'];
	
	if ($voted_member == 0) { // did not vote yet
	
		$selCnt = count($nominees);

		if ($selCnt > $configMax) {
			$errorMsg="You must select at least 1 nominee and at most (".$configMax.") nominees!!";
			$successMsg="";
		} else {
			for($i=0; $i < $selCnt; $i++)
			{
				$sql = "INSERT INTO ballots (nominee_id, ballot_id) VALUES (".$nominees[$i].",'". $ballot_id."');";

				if(!mysql_query($sql,$conn)){
					$errorMsg="Your vote was not saved, please try again!";
					$successMsg="";			
				}

	//			echo("<br>".$ballot_id."-".$nominees[$i]);
			}

			$successMsg="Thank you for voting.";
			$errorMsg="";
		}

	} else {
		$errorMsg="You have already voted, you cannot vote again, thank you!";
		$successMsg="";					
	}
		
  }	
  
  }

?>

<div class="form-style-5">
<form method="POST"
 action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
 
<fieldset>


<div id="holder">

	<div>
		
		<table align="center" width=70% border=0 cellpadding="10">
		<tr>
		<td colspan=2 class="msg"><?php echo("Please provide the Ballot # and select at least 1 nominee and at most (".$configMax.") nominees") ?></td>
		</tr>

		<tr>
		<td colspan=2>
				<?php 
				
				$showErrorMsg=0;
				$showSuccessMsg=0;
				
				if (strlen($errorMsg) > 0)
					$showErrorMsg = 1;

				if (strlen($successMsg) > 0)
					$showSuccessMsg = 1;
				
		if (($showErrorMsg == 0) and ($showSuccessMsg == 0)) 
			echo("");		
		elseif (($showErrorMsg == 1) and ($showSuccessMsg == 1)) {
			echo("<span class='error'>".$errorMsg."</span>");		
			echo("<span class='success'>".$successMsg."</span>");
		}
		elseif	(($showErrorMsg == 1) and ($showSuccessMsg == 0)) 
			echo("<span class='error'>".$errorMsg."</span>");		
		else //	(($showErrorMsg == 0) and ($showSuccessMsg == 1)) 
			echo("<span class='success'>".$successMsg."</span>");
		
		?>
		</td></tr>
		
		<tr>
		<td></td>
		<td><font class="name">Ballot # : </font><input type="text" name="ballot_id"></td>
		</tr>

<?php

$c=0;

while ( $db_field = mysql_fetch_assoc($NomineesResult) ) {
$c++;

?>

		<tr>
		<td width=1% ><input type="checkbox" name="nominees[]" id="checkbox-<?php echo ($c); ?>" class="regular-checkbox big-checkbox" value="<?php echo ($db_field['id']); ?>" /><label for="checkbox-<?php echo ($c); ?>"></label></td>
		<td><font class="name"><?php echo ($db_field['name']); ?></font></td>
		</tr>
	
<?php
}
mysql_close($conn);

?>	
		<tr>
		<td></td>
		<td><input type="submit" name="submit" value="VOTE NOW" /></td>
		</tr>
		
	</div>
</div>


</fieldset>

</form>

</div>

</body>
</html>