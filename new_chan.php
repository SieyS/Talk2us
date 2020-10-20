<?php

//fetch_channel.php

include('database_connection.php');

session_start();

$data = array(
	':name'		=>	$_GET['name']
);

$query = "
INSERT INTO channel
(name)
VALUES (:name)
";

$statement = $connect->prepare($query);
if($statement->execute($data))
{
	$query_ = "
	SELECT * FROM channel 
	ORDER BY creation DESC 
	LIMIT 1
	";
	$statement_ = $connect->prepare($query_);
	$statement_->execute();
	$result = $statement_->fetchAll();
	foreach($result as $row)
	{
		$data_ = array(
			':user_id'		=>	$_SESSION['user_id'],
			':channel_id'		=>	$row['channel_id'],
			':user_from'	=>	$_SESSION['user_id']
		);

		$req = "
		INSERT INTO channel_user
		(user_id,channel_id,user_from) 
		VALUES (:user_id,:channel_id,:user_from)
		";
		$stat = $connect->prepare($req);
		if($stat->execute($data_)) {
			echo 'Channel créé avec succès';
		}
	}
}else {}
?>