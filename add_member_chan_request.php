<?php

//add_member_chan_request.php

include('database_connection.php');

session_start();

$data = array(
	':invit_user_id'		=>	$_POST['member'],
	':channel_id'		=>	$_POST['channel_id'],
	':from_user_id'		=>	$_SESSION['user_id']
);

$query = "
INSERT INTO channel_user 
(user_id, channel_id, user_from) 
VALUES (:invit_user_id, :channel_id, :from_user_id)
";

$statement = $connect->prepare($query);

if($statement->execute($data))
{
	alert('Membre ajouté au channel');
}

?>