<?php

//fetch_channel.php

include('database_connection.php');

session_start();

$query = "
SELECT channel.channel_id, channel.name, channel.creation FROM channel, channel_user 
WHERE channel_user.user_id = '".$_SESSION['user_id']."' AND channel_user.channel_id = channel.channel_id
";

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

$output = '
<table class="table table-bordered table-striped">
	<tr>
		<th width="70%">Nom du channel</td>
		<th width="20%">Créé par</td>
		<th width="10%">Action</td>
	</tr>
';

foreach($result as $row)
{
	$createur = "
	SELECT login.user_id, login.username FROM login, channel_user 
	WHERE channel_user.user_id = channel_user.user_from 
	AND channel_user.user_id = login.user_id 
	AND channel_user.channel_id = '".$row['channel_id']."'
	";
	$stat = $connect->prepare($createur);
	$stat->execute();
	$res = $stat->fetchAll();
	
	$output .= '
	<tr>
		<td>'.$row['name'].'</td>';
		$i=0;
	foreach($res as $create){
		if($i==0){
			$output .= '<td align="center">';
			if($create['user_id'] == $_SESSION['user_id']){
				$output .= '<img src="images/crown.png" height="16px"/>';
			}
			$output .=$create['username'].' <br /> '.$row['creation'].'</td>';
			$i++;
		}
	}
	$output .='<td><button type="button" class="btn btn-info btn-xs start_chan" data-chanid="'.$row['channel_id'].'" data-channame="'.$row['name'].'">Discussion</button></td>
	</tr>
	';
}

$output .= '</table>';
echo $output;

?>