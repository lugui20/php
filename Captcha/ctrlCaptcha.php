<?php
class CtrlCaptcha
{
	
	public function ctrlMain($name)
	{
		// Create the image
		$img = imagecreatetruecolor(280, 60);

		// Create some colors
		$backColor = imagecolorallocate($img, rand(200,255), rand(200,255), rand(200,255));
		imagefilledrectangle($img, 0, 0, 280, 60, $backColor);

		$letters = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz123456789123456789123456789";
		$len = strlen($letters);

		$font = "./arial.ttf";

		for ($i = 0; $i < 50; $i++)
		{
			$pixelColor = imagecolorallocate($img, rand(0,200), rand(0,200), rand(0,200));
			imageellipse($img, rand()%280, rand()%60, rand(3,15), rand(3,15), $pixelColor);
			imagesetpixel($img, rand()%280, rand()%60, $pixelColor);
		}


		$text = "";
		$offset = rand(10, 35);
		$space = rand(30, 40);
		for ($i = 0; $i< 6;$i++)
		{
			$letter = $letters[rand(0, $len-1)];
			$fontColor = imagecolorallocate($img, rand(0,55), rand(0,55), rand(0,55));
			imagettftext($img, rand(20,30), rand(-10, 10), $offset+($i*$space)+rand(-7,7), 40+rand(-5,5), $fontColor, $font, $letter);
			$text .= $letter;
		}
		
		$_SESSION["captcha_" . $name] = strtolower($text);

		ob_start();
			imagejpeg($img);
			$captcha =  "data:image/jpeg;base64," . base64_encode(ob_get_clean());
		ob_end_flush();
		imagedestroy($img);

		return $captcha;
	}
	
	
	public function checkCaptcha($name)
	{
		$error = 0;
		if(isset($_SESSION["captcha_" . $name]))
		{
			if(isset($_POST["captcha"]) && is_string($_POST["captcha"]))
			{
				if($_SESSION["captcha_" . $name] != strtolower($_POST["captcha"]))
				{
					$error++;
				}
	
			}
			else
			{
				$error++;
			}
		}
		return $error;
	}
	
	
	
}
?>