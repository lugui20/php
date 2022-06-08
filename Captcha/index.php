<?php


	// ++++++++++++++++++++++++++++++++++++++
	// This example show ho to use the Class CtrlCaptcha
	// ++++++++++++++++++++++++++++++++++++++

	session_start();

	$name = "Example";
	$error = null;
	$message = null;

	include_once("ctrlCaptcha.php");
	$ctrlCaptcha = new CtrlCaptcha();

	if(isset($_POST["submit"]))
	{
		include_once("ctrlFormSubmit.php");
		$ctrlFormSubmit = new CtrlFormSubmit();
		$error = $ctrlFormSubmit->ctrlMain($ctrlCaptcha, $name);
		unset($ctrlFormSubmit);	
	}
	else
	{
		if(isset($_SESSION["message"])) unset($_SESSION["message"]);
	}
	
	$captcha = $ctrlCaptcha->ctrlMain($name);
	
	unset($ctrlCaptcha);
	
	if(isset($_SESSION["message"])) $message = $_SESSION["message"];

	
	include_once("intForm.php");
	$intForm = new IntForm();
	$intForm->viewHeader();
	$intForm->viewForm($error, $message, $captcha);
	$intForm->viewFooter();	
	
?>