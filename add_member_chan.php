<?php

//add_member_chan.php

include('database_connection.php');

session_start();

$query = "
SELECT * FROM login
";

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();
$output = '<br /><br /><SELECT name="add_member"><OPTION value="0" selected>Choisir membre';

foreach($result as $row)
{
	$already_add = "
	SELECT login.user_id, login.username FROM login,channel_user 
	WHERE channel_user.channel_id = '".$_POST['channel_id']."'
	AND channel_user.user_id = login.user_id
	";
	$stat = $connect->prepare($already_add);
	$stat->execute();
	$res = $stat->fetchAll();
	$i=0;
	foreach($res as $add)
	{
		if($add['user_id'] == $row['user_id']){
			$i++;
		}
	}
	if($i == 0){
		$output .= '<OPTION value="'.$row['user_id'].'">'.$row['username'];
	}
}

$output .= '</SELECT>  <button type="button" name="add_member_button" id="add_member_button" class="btn btn-info add_member_button" data-chanid="'.$_POST['channel_id'].'" style="height:20px;padding:0">Ajouter au channel</button>';
echo $output;

?>