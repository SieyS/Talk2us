<?php

//fetch_user_chan_history.php

include('database_connection.php');

session_start();

echo fetch_user_chan_history($_SESSION['user_id'], $_POST['channel_id'], $connect);

?>