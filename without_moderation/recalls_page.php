<?php
	$___notjson=1;
	header('Content-Type: text/html; charset=utf-8');
	setlocale(LC_ALL, 'ru_RU.65001', 'rus_RUS.65001', 'Russian_Russia. 65001', 'russian');
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<style>
	.swchItem {
	    color: #000;
	    border: 1px solid #FFBD85;
	    background: #FFFAF7;
	}
	.swchItemA, .swchItem:hover {
	    font-weight: normal;
	    color: #fff;
	    text-shadow: 0px 1px #CA470E;
	    box-shadow: 0px 1px #EDEDED;
	    -webkit-box-shadow: 0px 1px #EDEDED;
	    -moz-box-shadow: 0px 1px #EDEDED;
	    border: 1px solid #D13F11;
	    background: #E95B2B;
	    background: -moz-linear-gradient(top,#FFBE01 1px,#FE7C02 1px,#E95B2B);
	    background: -webkit-gradient(linear,0 0,0 100%,color-stop(0.02,#FFBE01),color-stop(0.02,#FE7C02),color-stop(1,#E95B2B));
	}
	.swchItemA, .swchItem {
	    -moz-border-radius: 3px;
	    -webkit-border-radius: 3px;
	    border-radius: 3px;
	    padding: 6px 9px;
	    margin-left: 3px;
	    text-decoration: none;
	    cursor: pointer;
	}
	.otziv {
	    padding: 5 8px;
	    font: bold 13px/16px 'Century Gothic';
	    color: #2d1f0b;
	    border: 1px solid #756859;
	    border-left: none;
	    border-top: none;
	    border-radius: 5px;
	    background: linear-gradient(to bottom,#f9d401, #e87b00);
	    cursor: pointer;
	}
	</style>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script type="text/javascript">
		function load_recalls() {
		    jQuery.ajax({
		        url:     'loadrecalls.php', 
		        type:     "GET", 
		        dataType: "html", 
		        data: {method: 'load_recalls'},  
		        success: function(response) { 
		        	$('#recalls').html(response);
		    	},
		    	error: function(response) { 
		    		$('#recalls').html('<center style="margin: 50px auto 50px auto;"><b>There was an error loading recalls.</b><br /><button class="otziv" style="width:175px; height:50px;font-size:15px;margin-top: 3px;margin-bottom: 6px; margin-left:11px;" onclick="load_recalls()">Reload.</button></center>');
		    	}
		 	});
		}
		function load_page(target)
		{
			if (!$(target).hasClass('swchItemA'))
			{			
				$('.swchItemA').attr('class', 'swchItem');
				let page_id = $(target).attr('page');
				jQuery.ajax({
					url:     'loadrecalls.php', 
					type:     "GET", 
					dataType: "html", 
					data: {method: 'load_page', page_id: page_id},  
					success: function(response) { 
						$('#recall_page').html(response);
					},
					error: function(response) { 
						$('#recall_page').html('<center style="margin: 50px auto 50px auto;"><b>There was an error loading recalls.</b><br /><button class="otziv" style="width:175px; height:50px;font-size:15px;margin-top: 3px;margin-bottom: 6px; margin-left:11px;" onclick="load_recalls()">Reload.</button></center>');
					}
				});
				$('span[page="'+ page_id +'"]').attr('class', 'swchItemA');
				$('html, body').animate({ scrollTop: $('#recalls').offset().top-5 }, 500);
			}
		}
		$( document ).ready(function() {
			load_recalls();
		});
	</script>
	<div id="recalls">
	</div>
</body>
</html>
