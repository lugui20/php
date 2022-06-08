<?php
	
	// +++++++++++++++++++++++++++++++++++++++++++++++++
	// This examples illustrates the use of IntDB Class
	// It implements PDO and provides a very easy way to customize DB functions
	// when you may need to change the database provider for example.
	// +++++++++++++++++++++++++++++++++++++++++++++++++

	$CPF = "12345678911";
	
	include_once("intDB.php");
	$intDB = new IntDB();
	
	$pdo = $intDB->pdo_db_connect();
	
	if($pdo)
	{
		$query = "SELECT username FROM users WHERE cpf = :cpf LIMIT 1";
		
		$pQuery = $intDB->pdo_db_prepare($pdo, $query);
		$intDB->pdo_db_bindValue($pQuery, "cpf", $CPF);
		$intDB->pdo_db_query($pQuery);
		$result = $intDB->pdo_db_fetch_assoc($pQuery);
		
		if($result)
		{
			echo "Success! User name is: " . $result["username"] . ".";
		}
		else
		{
			echo "Sorry. Can't find username with CPF: " . $CPF . ".";
		}
	}
	else
	{
		if(isset($GLOBALS["error"]))
			echo "<b>Ooooops!</b> Please check this error message: <i>\"" . $GLOBALS["error"] . "\"<i>";
		
	}
	
?>