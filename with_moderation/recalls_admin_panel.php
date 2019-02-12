<?php
	$___notjson=1;
	header('Content-Type: text/html; charset=utf-8');
	setlocale(LC_ALL, 'ru_RU.65001', 'rus_RUS.65001', 'Russian_Russia. 65001', 'russian');
?>
<!DOCTYPE html>
<html>

<head>
	<title>Recall Control Panel</title>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<link rel="stylesheet" href="recalls_admin_panel.css">
	<script src="recalls_admin_panel.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
	<div id="popup_area">
		<div class="overlay"></div>
		<div class="popup">
			<div class="close"><i class="far fa-times-circle" onclick="$('#popup_area').hide();"></i></div>
			<div class="form">
				<ul>
					<li>
						<h2></h2> </li>
					<li>
						<label for="date">Date:</label>
						<input type="text" name="date" required pattern="[0-3][0-9]\.[0-1][0-9]\.20[0-9]{2}" /> <span class="form_hint">01.01.2000</span> </li>
					<li>
						<label for="name">Name:</label>
						<input type="text" name="name" required /> </li>
					<li>
						<label for="email">E-mail:</label>
						<input type="email" name="email" required /> </li>
					<li>
						<label for="text">Text:</label>
						<textarea name="text" cols="40" rows="6" required></textarea>
					</li>
					<li>
						<button class="submit" type="submit" onclick="add_and_edit(this)"></button>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div id="content">
	</div>
</body>

</html>
