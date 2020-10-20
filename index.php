<?php

include('database_connection.php');

session_start();

if(!isset($_SESSION['user_id']))
{
	header("location:login.php");
}

?>

<html>  
    <head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Talk 2 Us</title>  
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.rawgit.com/mervick/emojioneArea/master/dist/emojioneArea.min.css">
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  		<script src="https://cdn.rawgit.com/mervick/emojioneArea/master/dist/emojioneArea.min.js"></script>
  		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
    </head>  
    <body>  
        <div class="container">
			<br />
			
			<h3 align="center"><a href="index.php"><img src="images/logo.png" alt="Talk 2 Us" width="200px"/></a></h3><br />
			<br />
		
					<p align="right" style="float:right;margin-top:-150px">Bonjour - <?php echo $_SESSION['username']; ?> <br /> <a href="update_mdp.php">Modifier mot de passe</a> <br /> <a href="logout.php">Se déconnecter</a></p>
				
			
			<div class="table-responsive">
				
				<h4 style="align:center;font-weight:bold;">Channels</h4>
				<div id="channels"></div>
				<div id="new_channel"></div>
				<input type="text" id="channel_name" />
				<button type="button" name="create_channel" id="create_channel" class="btn btn-info create_channel">Créer nouveau channel</button><br /><br /><br /><br />
				<h4 style="align:center;font-weight:bold;">Utilisateurs en ligne</h4>
				<div id="user_details"></div>
				<div id="user_model_details"></div>
			</div>
			<br />
			<br />
			
		</div>
		
    </body>  
</html>

<style>

.chat_message_area
{
	position: relative;
	width: 100%;
	height: auto;
	background-color: #FFF;
    border: 1px solid #CCC;
    border-radius: 3px;
}

#group_chat_message
{
	width: 100%;
	height: auto;
	min-height: 80px;
	overflow: auto;
	padding:6px 24px 6px 12px;
}

.image_upload
{
	position: absolute;
	top:3px;
	right:3px;
}
.image_upload > form > input
{
    display: none;
}

.image_upload img
{
    width: 24px;
    cursor: pointer;
}

</style>  

<div id="group_chat_dialog" title="Group Chat Window">
	<div id="group_chat_history" style="height:400px; border:1px solid #ccc; overflow-y: scroll; margin-bottom:24px; padding:16px;">

	</div>
	<div class="form-group">
		<!--<textarea name="group_chat_message" id="group_chat_message" class="form-control"></textarea>!-->
		<div class="chat_message_area">
			<div id="group_chat_message" contenteditable class="form-control">

			</div>
			<div class="image_upload">
				<form id="uploadImage" method="post" action="upload.php">
					<label for="uploadFile"><img src="upload.png" /></label>
					<input type="file" name="uploadFile" id="uploadFile" accept=".jpg, .png" />
				</form>
			</div>
		</div>
	</div>
	<div class="form-group" align="right">
		<button type="button" name="send_group_chat" id="send_group_chat" class="btn btn-info">Envoyer</button>
	</div>
</div>


<script>  
$(document).ready(function(){

	fetch_channel();
	fetch_user();

	setInterval(function(){
		update_last_activity();
		fetch_channel();
		fetch_user();
		update_chat_history_data();
		update_chat_members();
		update_chan_history_data();
		fetch_group_chat_history();
	}, 5000);

	$(document).on('click', '.create_channel', function(){
		if($("#channel_name").val()!=''){
			$.ajax({
				url:"new_chan.php?name="+$("#channel_name").val(),
				method:"GET",
				success:function(data){
					$('#new_channel').html(data);
					$("#channel_name").val("");
				}
			})
		}
	});
	function fetch_user()
	{
		$.ajax({
			url:"fetch_user.php",
			method:"POST",
			success:function(data){
				$('#user_details').html(data);
			}
		})
	}
	function fetch_channel()
	{
		$.ajax({
			url:"fetch_channel.php",
			method:"POST",
			success:function(data){
				$('#channels').html(data);
			}
		})
	}

	function update_last_activity()
	{
		$.ajax({
			url:"update_last_activity.php",
			success:function()
			{

			}
		})
	}

	function make_chat_dialog_box(to_user_id, to_user_name)
	{
		var modal_content = '<div id="user_dialog_'+to_user_id+'" class="user_dialog" title="'+to_user_name+'">';
		modal_content += '<div style="height:400px; border:1px solid #ccc; overflow-y: scroll; margin-bottom:24px; padding:16px;" class="chat_history" data-touserid="'+to_user_id+'" id="chat_history_'+to_user_id+'">';
		modal_content += fetch_user_chat_history(to_user_id);
		modal_content += '</div>';
		modal_content += '<div class="form-group">';
		modal_content += '<textarea name="chat_message_'+to_user_id+'" id="chat_message_'+to_user_id+'" class="form-control chat_message"></textarea>';
		modal_content += '</div><div class="form-group" align="right">';
		modal_content+= '<button type="button" name="send_chat" id="'+to_user_id+'" class="btn btn-info send_chat">Envoyer</button></div></div>';
		$('#user_model_details').html(modal_content);
	}
	function make_chan_dialog_box(channel_id,channel_name)
	{
		var modal_content = '<div id="chan_dialog'+channel_id+'" class="user_dialog" title="'+channel_name+'">';
		modal_content += '<div class="chat_members" data-chanid="'+channel_id+'" id="chat_members'+channel_id+'">';
		modal_content += chan_members(channel_id);
		modal_content += '</div>';
		modal_content += '<div style="margin-bottom:25px;" class="add_member_chat" id="add_member_chat'+channel_id+'">';
		modal_content += add_member_chan(channel_id);
		modal_content += '</div>';
		modal_content += '<div style="height:400px; border:1px solid #ccc; overflow-y: scroll; margin-bottom:24px; padding:16px;" class="chat_history" data-chanid="'+channel_id+'" id="chat_history_'+channel_id+'">';
		modal_content += fetch_user_chan_history(channel_id);
		modal_content += '</div>';
		modal_content += '<div class="form-group">';
		modal_content += '<textarea name="chat_message_'+channel_id+'" id="chat_message_'+channel_id+'" class="form-control chat_message"></textarea>';
		modal_content += '</div><div class="form-group" align="right">';
		modal_content+= '<button type="button" name="send_chat_chan" id="'+channel_id+'" class="btn btn-info send_chat_chan">Envoyer</button></div></div>';
		$('#user_model_details').html(modal_content);
	}

	$(document).on('click', '.add_member_button', function(){
		var channel_id = $(this).data('chanid');
		if($("select[name='add_member'] > option:selected").val() != 0)
		{
			var member = $("select[name='add_member'] > option:selected").val();
			$.ajax({
				url:"add_member_chan_request.php",
				method:"POST",
				data:{channel_id:channel_id, member:member},
				success:function(data)
				{
					alert('Membre ajouté avec succès');
					$("select[name='add_member']").val(0);
				}
			})
		}
		
	});
	$(document).on('click', '.start_chan', function(){
		var channel_id = $(this).data('chanid');
		var channel_name = $(this).data('channame');
		make_chan_dialog_box(channel_id,channel_name);
		$("#chan_dialog"+channel_id).dialog({
			autoOpen:false,
			width:400
		});
		$('#chan_dialog'+channel_id).dialog('open');
		$('#chat_message_'+channel_id).emojioneArea({
			pickerPosition:"top",
			toneStyle: "bullet"
		});
	});
	$(document).on('click', '.start_chat', function(){
		var to_user_id = $(this).data('touserid');
		var to_user_name = $(this).data('tousername');
		make_chat_dialog_box(to_user_id, to_user_name);
		$("#user_dialog_"+to_user_id).dialog({
			autoOpen:false,
			width:400
		});
		$('#user_dialog_'+to_user_id).dialog('open');
		$('#chat_message_'+to_user_id).emojioneArea({
			pickerPosition:"top",
			toneStyle: "bullet"
		});
	});

	$(document).on('click', '.send_chat_chan', function(){
		var channel_id = $(this).attr('id');
		var chat_message = $.trim($('#chat_message_'+channel_id).val());
		if(chat_message != '')
		{
			$.ajax({
				url:"insert_chat_chan.php",
				method:"POST",
				data:{channel_id:channel_id, chat_message:chat_message},
				success:function(data)
				{
					$('#chat_message_'+channel_id).val('');
					var element = $('#chat_message_'+channel_id).emojioneArea();
					element[0].emojioneArea.setText('');
					$('#chat_history_'+channel_id).html(data);
				}
			})
		}
		else
		{
			alert('Message vide');
		}
	});
	$(document).on('click', '.send_chat', function(){
		var to_user_id = $(this).attr('id');
		var chat_message = $.trim($('#chat_message_'+to_user_id).val());
		if(chat_message != '')
		{
			$.ajax({
				url:"insert_chat.php",
				method:"POST",
				data:{to_user_id:to_user_id, chat_message:chat_message},
				success:function(data)
				{
					$('#chat_message_'+to_user_id).val('');
					var element = $('#chat_message_'+to_user_id).emojioneArea();
					element[0].emojioneArea.setText('');
					$('#chat_history_'+to_user_id).html(data);
				}
			})
		}
		else
		{
			alert('Message vide');
		}
	});

	function chan_members(channel_id)
	{
		$.ajax({
			url:"chan_members.php",
			method:"POST",
			data:{channel_id:channel_id},
			success:function(data){
				$('#chat_members'+channel_id).html(data);
			}
		})
	}
	function add_member_chan(channel_id)
	{
		$.ajax({
			url:"add_member_chan.php",
			method:"POST",
			data:{channel_id:channel_id},
			success:function(data){
				$('#add_member_chat'+channel_id).html(data);
			}
		})
	}
	function fetch_user_chat_history(to_user_id)
	{
		$.ajax({
			url:"fetch_user_chat_history.php",
			method:"POST",
			data:{to_user_id:to_user_id},
			success:function(data){
				$('#chat_history_'+to_user_id).html(data);
				$('#chat_history_'+to_user_id).scrollTop($('#chat_history_'+to_user_id).prop("scrollHeight"));
			}
		})
	}
	function fetch_user_chan_history(channel_id)
	{
		$.ajax({
			url:"fetch_user_chan_history.php",
			method:"POST",
			data:{channel_id},
			success:function(data){
					//scroll = document.
					$('#chat_history_'+channel_id).html(data);
					$('#chat_history_'+channel_id).scrollTop($('#chat_history_'+channel_id).prop("scrollHeight"));
				
			}
		})
	}

	function update_chat_members()
	{
		$('.chat_members').each(function(){
			var channel_id = $(this).data('chanid');
			chan_members(channel_id);
		});
	}
	function update_chat_history_data()
	{
		$('.chat_history').each(function(){
			var to_user_id = $(this).data('touserid');
			fetch_user_chat_history(to_user_id);
		});
	}

	function update_chan_history_data()
	{
		$('.chat_history').each(function(){
			var channel_id = $(this).data('chanid');
			fetch_user_chan_history(channel_id);
		});
	}

	$(document).on('click', '.ui-button-icon', function(){
		$('.user_dialog').dialog('destroy').remove();
		$('#is_active_group_chat_window').val('no');
	});

	$(document).on('focus', '.chat_message', function(){
		var is_type = 'yes';
		$.ajax({
			url:"update_is_type_status.php",
			method:"POST",
			data:{is_type:is_type},
			success:function()
			{

			}
		})
	});

	$(document).on('blur', '.chat_message', function(){
		var is_type = 'no';
		$.ajax({
			url:"update_is_type_status.php",
			method:"POST",
			data:{is_type:is_type},
			success:function()
			{
				
			}
		})
	});

	$('#group_chat_dialog').dialog({
		autoOpen:false,
		width:400
	});

	$('#group_chat').click(function(){
		$('#group_chat_dialog').dialog('open');
		$('#is_active_group_chat_window').val('yes');
		fetch_group_chat_history();
	});

	$('#send_group_chat').click(function(){
		var chat_message = $.trim($('#group_chat_message').html());
		var action = 'insert_data';
		if(chat_message != '')
		{
			$.ajax({
				url:"group_chat.php",
				method:"POST",
				data:{chat_message:chat_message, action:action},
				success:function(data){
					$('#group_chat_message').html('');
					$('#group_chat_history').html(data);
				}
			})
		}
		else
		{
			alert('Message vide');
		}
	});

	function fetch_group_chat_history()
	{
		var group_chat_dialog_active = $('#is_active_group_chat_window').val();
		var action = "fetch_data";
		if(group_chat_dialog_active == 'yes')
		{
			$.ajax({
				url:"group_chat.php",
				method:"POST",
				data:{action:action},
				success:function(data)
				{
					$('#group_chat_history').html(data);
				}
			})
		}
	}

	$('#uploadFile').on('change', function(){
		$('#uploadImage').ajaxSubmit({
			target: "#group_chat_message",
			resetForm: true
		});
	});

	$(document).on('click', '.remove_chat', function(){
		var chat_message_id = $(this).attr('id');
		if(confirm("Etes vous sur de vouloir supprimer ce chat ?"))
		{
			$.ajax({
				url:"remove_chat.php",
				method:"POST",
				data:{chat_message_id:chat_message_id},
				success:function(data)
				{
					update_chat_history_data();
				}
			})
		}
	});
	$(document).on('click', '.remove_chat_chan', function(){
		var chat_message_id = $(this).attr('id');
		if(confirm("Etes vous sur de vouloir supprimer ce chat ?"))
		{
			$.ajax({
				url:"remove_chat_chan.php",
				method:"POST",
				data:{chat_message_id:chat_message_id},
				success:function(data)
				{
					update_chan_history_data();
				}
			})
		}
	});
	
});  
</script>