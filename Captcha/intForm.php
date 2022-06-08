<?php
class IntForm
{



	public function viewHeader()
	{
		?>
	<!DOCTYPE html>
		<head>
			<title>Captcha example</title>
			<link href="https://fonts.googleapis.com/css2?&family=Ubuntu&display=swap" rel="stylesheet">
			<link rel="stylesheet" href="./template.css?" type="text/css">
		</head>
		<body>
		<?php
	}

	
	public function viewForm($error, $message, $captcha)
	{
		
		?>
			
			<div class="coluna-100 formulario" style="text-align: center;">

				<?php					
					
					if(!$message)
					{
						?>
				<div>
					<b>Captcha example:</b>
				</div>				
						<?php
					}
					else
					{
						?>
				<div class="<?php if($error) echo "error"; else echo "success"; ?>">					
					<b><?php echo $message; ?></b>
				</div>
						<?php
					}					
						?>
				<form action="./index.php" method="post">						
					<div>					
						<b>Please, enter the following sequence and submit:</b>
					</div>
					<div>
						<img src="<?php echo $captcha; ?>" class="captcha">
					</div>
					<div>
						<input type="text" size="8" name="captcha" placeholder="Captcha" id="captcha" required>
					</div>
					<div>
						<input type="submit" name="submit" value="Submit" class="button-submit">
					</div>
				</form>	
			</div>		
		<?php		
	}
	

	public function viewFooter()
	{
		?>
		</body>
		</html>
		<?php
	}

	
}
?>