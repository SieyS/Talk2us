<?php

//insert_chat.php

include('database_connection.php');

session_start();

$data = array(
	':channel_id'		=>	$_POST['channel_id'],
	':from_user_id'		=>	$_SESSION['user_id'],
	':chat_message'		=>	$_POST['chat_message'],
	':status'			=>	'1'
);

$query = "
INSERT INTO channel_message 
(user_from, channel_id, chat_message, status) 
VALUES (:from_user_id, :channel_id, :chat_message, :status)
";

$statement = $connect->prepare($query);

if($statement->execute($data))
{
	echo fetch_user_chan_history($_SESSION['user_id'], $_POST['channel_id'], $connect);
}

?>