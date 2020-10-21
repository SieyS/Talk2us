<!--
//login.php
!-->

<?php

include('database_connection.php');

session_start();

$message = '';

if(isset($_SESSION['user_id']))
{
	header('location:index.php');
}

if(isset($_POST['login']))
{
	$query = "
		SELECT * FROM login 
  		WHERE username = :username
	";
	$statement = $connect->prepare($query);
	$statement->execute(
		array(
			':username' => $_POST["username"]
		)
	);	
	$count = $statement->rowCount();
	if($count > 0)
	{
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			if(password_verify($_POST["password"], $row["password"]))
			{
				$_SESSION['user_id'] = $row['user_id'];
				$_SESSION['username'] = $row['username'];
				$sub_query = "
				INSERT INTO login_details 
	     		(user_id) 
	     		VALUES ('".$row['user_id']."')
				";
				$statement = $connect->prepare($sub_query);
				$statement->execute();
				$_SESSION['login_details_id'] = $connect->lastInsertId();
				header('location:index.php');
			}
			else
			{
				$message = '<label>Mauvais mot de passe</label>';
			}
		}
	}
	else
	{
		$message = '<label>Mauvais nom d\'utilisateur</labe>';
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
  				<div class="panel-heading">Identification</div>
				<div class="panel-body">
					<p class="text-danger"><?php echo $message; ?></p>
					<form method="post">
						<div class="form-group">
							<label>Nom d'utilisateur</label>
							<input type="text" name="username" class="form-control" required />
						</div>
						<div class="form-group">
							<label>Mot de passe</label>
							<input type="password" name="password" class="form-control" required />
						</div>
						<div class="form-group">
							<input type="submit" name="login" class="btn btn-info" value="Se connecter" />
						</div>
						<div align="center">
							<a href="register.php">S'inscrire ?</a>
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