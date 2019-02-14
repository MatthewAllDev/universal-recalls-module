<?php
	$___notjson=1;
	header('Content-Type: text/html; charset=utf-8');
	setlocale(LC_ALL, 'ru_RU.65001', 'rus_RUS.65001', 'Russian_Russia. 65001', 'russian');
	error_reporting(0);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Add recall</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<link rel="stylesheet" href="recalls_admin_panel.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script type="text/javascript">
		function add()
		{
			let date = new Date();
			let options = {
				year: 'numeric',
				month: 'numeric',
				day: 'numeric'
			};
			date = date.toLocaleString("ru", options);
			let name = $('.form input[name="name"]').val();
			let email = $('.form input[name="email"]').val();
			let text = $('.form textarea[name="text"]').val();
			$.post('recalls_functions.php', {
				date: date,
				name: name,
				email: email,
				text: text,
				method: 'add_client'
			}, function(response) {
				if(response.indexOf('success') == -1) 
				{
					alert('An error occurred while sending a recall');
				} 
				else 
				{
					alert('Recall successfully sending');
					$('.form input').val('');
					$('.form textarea').val('');
				}
			});
		}
	</script>
</head>
<body>
	<div class="form">
		<ul>
			<li>
				<h2>Add recall</h2> 
			</li>
			<li>
				<label for="name">Name:</label>
				<input type="text" name="name" required /> 
			</li>
			<li>
				<label for="email">E-mail:</label>
				<input type="email" name="email" required /> 
			</li>
			<li>
				<label for="text">Text:</label>
				<textarea name="text" cols="40" rows="6" required></textarea>
			</li>
			<li>
				<button class="submit" type="submit" onclick="add()">Send</button>
			</li>
		</ul>
	</div>
</body>
</html>