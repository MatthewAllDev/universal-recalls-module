<?php
	$___notjson=1;
	header('Content-Type: text/html; charset=utf-8');
	setlocale(LC_ALL, 'ru_RU.65001', 'rus_RUS.65001', 'Russian_Russia. 65001', 'russian');
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style>
		*:focus {outline: none;}
		body {font: 18px/24px "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;}
		.recall_form h2, .recall_form label {font-family:Georgia, Times, "Times New Roman", serif;}
		.form_hint, .required_notification {font-size: 11px;}
		.recall_form ul {
		    width:750px;
		    list-style-type:none;
			list-style-position:outside;
			margin:0px;
			padding:0px;
			border: 1px solid #000;
		}
		.recall_form li{
			padding:12px; 
			border-bottom:1px solid #eee;
			position:relative;
		} 
		.recall_form li:first-child, .recall_form li:last-child {
			text-align: center;
		}
		.recall_form h2 {
			margin:0;
			display: inline;
		}
		.required_notification {
			color:#d45252; 
			margin:5px 0 0 0; 
			display:inline;
			float:right;
		}
		.recall_form label {
			width:150px;
			margin-top: 3px;
			display:inline-block;
			float:left;
			padding:3px;
		}
		.recall_form input {
			height:20px; 
			width: 70%;
			padding:5px 8px;
		}
		.recall_form textarea {padding:8px; width:300px;}
		.recall_form input, .recall_form textarea {
			width: 70%; 
			border:1px solid #aaa;
			box-shadow: 0px 0px 3px #ccc, 0 10px 15px #eee inset;
			border-radius:2px;
			padding-right:30px;
			-moz-transition: padding .25s; 
			-webkit-transition: padding .25s; 
			-o-transition: padding .25s;
			transition: padding .25s;
		}
		.recall_form input:focus, .recall_form textarea:focus {
			background: #fff; 
			border:1px solid #555; 
			box-shadow: 0 0 3px #aaa; 
		}
		.recall_form input:required, .recall_form textarea:required {
			background: #fff url(images/red_asterisk.png) no-repeat 98% center;
		}
		.recall_form input:required:valid, .recall_form textarea:required:valid {
			background: #fff url(images/valid.png) no-repeat 98% center;
			box-shadow: 0 0 5px #5cd053;
			border-color: #28921f;
		}
		.recall_form input:focus:invalid, .recall_form textarea:focus:invalid {
			background: #fff url(images/invalid.png) no-repeat 98% center;
			box-shadow: 0 0 5px #d45252;
			border-color: #b03535
		}
		.form_hint {
			background: #d45252;
			border-radius: 3px 3px 3px 3px;
			color: white;
			margin-left:8px;
			padding: 1px 6px;
			z-index: 999;
			position: absolute;
			display: none;
		}
		.form_hint::before {
			content: "\25C0";
			color:#d45252;
			position: absolute;
			top:1px;
			left:-6px;
		}
		.recall_form input:focus + .form_hint {display: inline;}
		.recall_form input:required:valid + .form_hint {background: #28921f;}
		.recall_form input:required:valid + .form_hint::before {color:#28921f;}
		button.submit {
			font-size: 20px;
			background-color: #68b12f;
			background: -webkit-gradient(linear, left top, left bottom, from(#68b12f), to(#50911e));
			background: -webkit-linear-gradient(top, #68b12f, #50911e);
			background: -moz-linear-gradient(top, #68b12f, #50911e);
			background: -ms-linear-gradient(top, #68b12f, #50911e);
			background: -o-linear-gradient(top, #68b12f, #50911e);
			background: linear-gradient(top, #68b12f, #50911e);
			border: 1px solid #509111;
			border-bottom: 1px solid #5b992b;
			border-radius: 3px;
			-webkit-border-radius: 3px;
			-moz-border-radius: 3px;
			-ms-border-radius: 3px;
			-o-border-radius: 3px;
			box-shadow: inset 0 1px 0 0 #9fd574;
			-webkit-box-shadow: 0 1px 0 0 #9fd574 inset ;
			-moz-box-shadow: 0 1px 0 0 #9fd574 inset;
			-ms-box-shadow: 0 1px 0 0 #9fd574 inset;
			-o-box-shadow: 0 1px 0 0 #9fd574 inset;
			color: white;
			font-weight: bold;
			padding: 8px 50px;
			text-align: center;
			text-shadow: 0 -1px 0 #396715;
		}
		button.submit:hover {
			opacity:.85;
			cursor: pointer; 
		}
		button.submit:active {
			border: 1px solid #20911e;
			box-shadow: 0 0 10px 5px #356b0b inset; 
			-webkit-box-shadow:0 0 10px 5px #356b0b inset ;
			-moz-box-shadow: 0 0 10px 5px #356b0b inset;
			-ms-box-shadow: 0 0 10px 5px #356b0b inset;
			-o-box-shadow: 0 0 10px 5px #356b0b inset;
			
		}
	</style>
</head>
<body>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script type="text/javascript">
		$( document ).ready(function() {
		    $(".submit").click(
				function(){
					sendAjaxForm('recall_form', 'addrecall.php');
					return false; 
				}
			);
		});
		 
		function sendAjaxForm(recall_form, url) {
		    jQuery.ajax({
		        url:     url, 
		        type:     "POST", 
		        dataType: "html", 
		        data: $("."+recall_form).serialize(),  
		        success: function(response) { 
		        	alert('Recall successfully added!');
		        	$('input, textarea').each(function(){$(this).val('')})
		    	},
		    	error: function(response) { 
		    		alert('An error occurred while adding a recall!');
		    	}
		 	});
		}
	</script>
	
		<form class="recall_form" action="#" method="post" name="recall_form">
	    <ul>
	        <li>
	             <h2>Add recall</h2>
	        </li>
	        <li>
	            <label for="date">Date:</label>
	            <input type="text" name="date" required pattern="[0-3][0-9]\.[0-1][0-9]\.20[0-9]{2}"/>
	            <span class="form_hint">01.01.2000</span>
	        </li>
	        <li>
	            <label for="name">Name:</label>
	            <input type="text" name="name" required />
	        </li>
	        <li>
	            <label for="text">Text:</label>
	            <textarea name="text" cols="40" rows="6" required ></textarea>
	        </li>
	        <li>
	        	<button class="submit" type="submit">Add</button>
	        </li>
	    </ul>
	</form>

</body>
</html>
