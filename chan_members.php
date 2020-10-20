<?php

//chan_members.php

include('database_connection.php');

session_start();

$query = "
SELECT login.user_id, login.username FROM login,channel_user 
WHERE channel_user.channel_id = '".$_POST['channel_id']."'
AND channel_user.user_id = login.user_id
";

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

$output="";

foreach($result as $row)
{
	$status = '';
	$current_timestamp = strtotime(date("Y-m-d H:i:s") . '- 12900 second');
	$current_timestamp = date('Y-m-d H:i:s', $current_timestamp);
	$absent = strtotime(date("Y-m-d H:i:s") . '- 16500 second');
	$absent = date('Y-m-d H:i:s', $absent);
	$user_last_activity = fetch_user_last_activity($row['user_id'], $connect);
	if($row['user_id'] == $_SESSION['user_id']){
		$name = '<img src="images/crown.png" height="16px"/><p style="display:inline;color:black">  '.$row['username'].'</p>';
	}else{
		$name = $row['username'];
	}
	if($user_last_activity > $current_timestamp)
	{
		$output .= '   <span class="label label-success">'.$name.'</span>';
	}
	else if($user_last_activity > $absent){
		$output .= '   <span class="label label-success" style="background-color:#FFA500">'.$name.'</span>';
	}
	else
	{
		$output .= '   <span class="label label-danger">'.$name.'</span>';
	}
}
echo $output;
?>