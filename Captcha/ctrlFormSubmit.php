<?php
class CtrlFormSubmit
{
	
	public function ctrlMain($ctrlCaptcha, $name)
	{		
		$error = 0;
		if($error += $ctrlCaptcha->checkCaptcha($name))
		{
			$_SESSION["message"] = "Sorry, the sequence is incorrect.";			
		}
		else
		{
			// +++++++++++++++++++++++++++++++++++++++++
			// Do something if the captcha is correct
			// Login user for example
			// +++++++++++++++++++++++++++++++++++++++++
			
			$_SESSION["message"] = "The sequence is correct!";			
		}
		return $error;
	}
	
}
?>