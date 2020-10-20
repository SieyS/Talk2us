<?php

//fetch_user.php

include('database_connection.php');

session_start();

$query = "
SELECT * FROM login 
WHERE user_id != '".$_SESSION['user_id']."' 
";

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

$output = '
<table class="table table-bordered table-striped">
	<tr>
		<th width="70%">Nom d\'utilisateur</td>
		<th width="20%">Status</td>
		<th width="10%">Action</td>
	</tr>
';

foreach($result as $row)
{
	$status = '';
	$current_timestamp = strtotime(date("Y-m-d H:i:s") . '- 12900 second');
	$current_timestamp = date('Y-m-d H:i:s', $current_timestamp);
	$absent = strtotime(date("Y-m-d H:i:s") . '- 16500 second');
	$absent = date('Y-m-d H:i:s', $absent);
	$user_last_activity = fetch_user_last_activity($row['user_id'], $connect);
	if($user_last_activity > $current_timestamp)
	{
		$status = '<span class="label label-success">En Ligne</span>';
	}
	else if($user_last_activity > $absent){
		$status = '<span class="label label-success" style="background-color:#FFA500">Absent</span>';
	}
	else
	{
		$status = '<span class="label label-danger">Hors Ligne</span>';
	}
	$output .= '
	<tr>
		<td>'.$row['username'].' '.count_unseen_message($row['user_id'], $_SESSION['user_id'], $connect).' '.fetch_is_type_status($row['user_id'], $connect).'</td>
		<td>'.$status.'</td>
		<td><button type="button" class="btn btn-info btn-xs start_chat" data-touserid="'.$row['user_id'].'" data-tousername="'.$row['username'].'">Discussion</button></td>
	</tr>
	';
}

$output .= '</table>';

echo $output;

?>