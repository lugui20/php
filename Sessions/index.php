<?php

	if($_SERVER['SERVER_PORT'] != '443') { header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); exit(); }
	header("Cache-Control: no-cache, must-revalidate");;
	include_once("ctrlSession.php");
	include_once("intMenu.php");
	$ctrlSession = new CtrlSession();
	$intMenu = new IntMenu();
	$ctrlSession->ctrlMain("ExampleSession"); // session_start() !!
	$authenticated = false;
	
	
	// ++++++++++++++++++++++++++++++++++++++
	// Only for illustration purposes of the Class CtrlSession the user log in and log out will be simulated via $_GET["goto"] variable
	// In real use username and password would be received via $_POST and searched in the database
	// +++++++++++++++++++++++++++++++++++++++
	
	if(isset($_GET["goto"]) && is_string($_GET["goto"]))
	{
		switch($_GET["goto"])
		{
			case "login":
				$_SESSION["login"] = false;
				
				
				// Here you should check username and password in the database
				
				// Supposing everything ok... (on the contrary break)
				
				$ctrlSession->regenerate();
				$_SESSION["userId"] = "123";
				$_SESSION["userName"] = "User 123";				
				$_SESSION["login"] = true;
				break;
				
			case "logout":
				$ctrlSession->destroy();
				break;
		}
		
	}
	
	if(isset($_SESSION["login"]) && $_SESSION["login"]) $authenticated = true;

	$intMenu->viewHeader();
	
	echo "<div class=\"panel center\">Your session id is: <b>";
	if(session_id()) echo session_id(); else echo "empty";
	echo "</b></div>";
	
	
	$intMenu->viewBoxUser($authenticated);
	$intMenu->viewScriptClick();
	$intMenu->viewFooter();

	
?>