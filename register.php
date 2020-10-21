<!--
//register.php
!-->

<?php

include('database_connection.php');

session_start();

$message = '';

if(isset($_SESSION['user_id']))
{
	header('location:index.php');
}

if(isset($_POST["register"]))
{
	$username = trim($_POST["username"]);
	$password = trim($_POST["password"]);
	$mail = trim($_POST["mail"]);
	$check_query = "
	SELECT * FROM login 
	WHERE username = :username
	";
	$statement = $connect->prepare($check_query);
	$check_data = array(
		':username'		=>	$username
	);
	if($statement->execute($check_data))	
	{
		if($statement->rowCount() > 0)
		{
			$message .= '<p><label>Nom d\'utilisateur deja utilisé</label></p>';
		}
		else
		{
			if(empty($username))
			{
				$message .= '<p><label>Nom d\'utilisateur obligatoire</label></p>';
			}
			if(empty($mail))
			{
				$message .= '<p><label>Adresse mail obligatoire</label></p>';
			}
			if(empty($password))
			{
				$message .= '<p><label>Mot de passe obligatorie</label></p>';
			}
			else
			{
				if($password != $_POST['confirm_password'])
				{
					$message .= '<p><label>Les deux mots de passe ne sont pas égaux</label></p>';
				}
			}
			if(preg_match('/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?\*])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?\*]{8,20}$/',$password) AND strlen($password) >= 6) {
			}else
			{
				$message .= '<label>Mot de passe trop faible, il doit contenir au moins : <br />
				- 6 Caractères minimum<br />
				- Une minuscule <br />
				- Une majuscule <br />
				- Un chiffre <br />
				- Un caractère spécial <br />
				</label>';
			}
			if($message == '')
			{
				$data = array(
					':username'		=>	$username,
					':mail'		=>	$mail,
					':password'		=>	password_hash($password, PASSWORD_DEFAULT)
				);

				$query = "
				INSERT INTO login 
				(username, password, mail) 
				VALUES (:username, :password, :mail)
				";
				$statement = $connect->prepare($query);
				if($statement->execute($data))
				{
					$message = "<label>Inscription complète</label>";
				}
			}
		}
	}
}

?>

<html>  
    <head>  
        <title>Talk 2 Us</title>  
		<link rel="stylesheet" href="jquery-ui.css">
        <link rel="stylesheet" href="/css/bootstrap.min.css">
		<script src="jquery-1.12.4.js"></script>
  		<script src="jquery-ui.js"></script>
    </head>  
    <body>  
        <div class="container">
			<br />
			
			<h3 align="center"><a href="index.php"><img src="images/logo.png" alt="Talk 2 Us" width="200px"/></a></h3><br />
			<br />
			<div class="panel panel-default">
  				<div class="panel-heading">Inscription</div>
				<div class="panel-body">
					<form method="post">
						<span class="text-danger"><?php echo $message; ?></span>
						<div class="form-group">
							<label>Nom d'utilisateur</label>
							<input type="text" name="username" class="form-control" />
						</div>
						<div class="form-group">
							<label>Mot de passe</label>
							<input type="password" name="password" class="form-control" />
						</div>
						<div class="form-group">
							<label>Confirmation du mot de passe</label>
							<input type="password" name="confirm_password" class="form-control" />
						</div>
						<div class="form-group">
							<label>Adresse Mail</label>
							<input type="text" name="mail" class="form-control" />
						</div>
						<div class="form-group">
							<input type="submit" name="register" class="btn btn-info" value="S'inscrire" />
						</div>
						<div align="center">
							<a href="login.php">Se connecter ?</a>
						</div>
					</form>
				</div>
			</div>
		</div>
    </body>  
</html>
