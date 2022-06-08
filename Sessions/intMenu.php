<?php
class IntMenu
{
	public function viewHeader()
	{
		?>
	<!DOCTYPE html>
		<head>
			<title>Sessions example</title>
			<link href="https://fonts.googleapis.com/css2?&family=Ubuntu&display=swap&family=Oxanium:wght@600" rel="stylesheet">
			<link rel="stylesheet" href="./template2.css?<?php echo time(); ?>" type="text/css">
		</head>
		<body>
		<?php
	}

	
	public function viewScriptClick()
	{
		?>
			<script>
			window.addEventListener('click', function(e)
			{
				if(document.getElementById('dropdown-account'))
				{
					if(!document.getElementById('dropdown-account').contains(e.target))
					{
						if(document.getElementById('input-dropdown-account').checked) document.getElementById('input-dropdown-account').checked = false;
					}
				}
			})	
		</script>
		<?php
			
	}
	
	
	public function viewBoxUser($authenticated)
	{
		
		?>
	
			<div class="box-user center" name="box-user">
				<div style="position: relative;" id="dropdown-account">
					<input type="checkbox" id="input-dropdown-account" class="input-dropdown-account">
						<label for="input-dropdown-account" class="btdrop-account">
							<span class="btdrop-face-account image-background" style="width: 120px; height: 75px; padding-top: 15px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.2); font-size: 16px;">							
								<?php if($authenticated) echo "\"" . $_SESSION["userName"] . "\" logged in"; else echo "Options"; ?>
							</span>				
						</label>													
						<div class="options-dropdown-account" style="position: absolute; top: 75px; left: -30px; z-index: 3;">
						<?php
						if($authenticated)
						{			
							?>
							<a href="./?goto=logout" class="button-menu-account"> Log out</a>
							<?php
						}
						else
						{
							?>
							<a href="./?goto=login" class="button-menu-account"> Login</a>
							<?php
						}
						?>
						</div>
				</div>
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