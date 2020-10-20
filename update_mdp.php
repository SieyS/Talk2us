 <!--
//update_mdp.php
!-->

<?php

include('database_connection.php');

session_start();

$message = '';

if(!isset($_SESSION['user_id']))
{
	header('location:index.php');
}

if(isset($_POST['update']))
{
	$query = "
		SELECT * FROM login 
  		WHERE user_id = :user_id
	";
	$statement = $connect->prepare($query);
	$statement->execute(
		array(
			':user_id' => $_SESSION['user_id']
		)
	);	
	$count = $statement->rowCount();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		$oldPassword = trim($_POST["old_password"]);
		$newPassword = trim($_POST["new_password"]);
		if($newPassword == $_POST['confirm_new_password']){
			
			if(preg_match('/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?\*])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?\*]{8,20}$/',$newPassword) AND strlen($newPassword) >= 6) { 
				if(password_verify($oldPassword, $row["password"]))
				{
					$data = array(
						':id'		=>	$_SESSION['user_id'],
						':password'		=>	password_hash($newPassword, PASSWORD_DEFAULT)
					);

					$query = "
					UPDATE login
					SET password = :password
					WHERE user_id = :id
					";
					$statement = $connect->prepare($query);
					if($statement->execute($data))
					{
						$message = "<label>Mot de passe changé avec succès</label>";
					}
				}
				else
				{
					$message = '<label>Mauvais mot de passe</label>';
				}
			}
			else
			{
				$message = '<label>Mot de passe trop faible, il doit contenir au moins : <br />
				- 6 Caractères minimum<br />
				- Une minuscule <br />
				- Une majuscule <br />
				- Un chiffre <br />
				- Un caractère spécial <br />
				</label>';
			}
		}else{
			$message = '<label>Les deux mots de passe ne sont pas égaux</label>';
		}
	}
}


?>

<html>  
    <head>  
        <title>Talk 2 Us</title>  
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    </head>  
    <body>  
        <div class="container">
			<br />
			
			<h3 align="center"><a href="index.php"><img src="images/logo.png" alt="Talk 2 Us" width="200px"/></a></h3><br />
			<br />
			<div class="panel panel-default">
  				<div class="panel-heading">Modification du mot de passe</div>
				<div class="panel-body">
					<p class="text-danger"><?php echo $message; ?></p>
					<form method="post">
						<div class="form-group">
							<label>Ancien mot de passe</label>
							<input type="password" name="old_password" class="form-control" required />
						</div>
						<div class="form-group">
							<label>Nouveau Mot de passe</label>
							<input type="password" name="new_password" class="form-control" required />
							<p><div class="" id="passwordStrength"></div></p>
						</div>
						<div class="form-group">
							<label>Confirmation du nouveau Mot de passe</label>
							<input type="password" name="confirm_new_password" class="form-control" required />
						</div>
						<div class="form-group">
							<input type="submit" name="update" class="btn btn-info" value="Modifier" />
						</div>
					</form>
					<br />
					<br />
					<br />
					<br />
				</div>
			</div>
		</div>
    </body>  
</html>