function load_recalls(password) {
	$.get('recalls_functions.php', {
		password: password,
		method: 'load'
	}, function(response) {
		if (response.indexOf('error') != -1) $('body').html('<h1>Access denied</h1>');
		else $('#content').html(response);
		if (response.indexOf('Recalls not found') != -1) $('#content').append('<br /><br /><button class="submit" onclick="add_recall_action()">Add</button>');
	});
}

function add_recall_action() {
	$('.form input:not([name="date"]').val('');
	$('.form textarea').val('');
	let date = new Date();
	let options = {
		year: 'numeric',
		month: 'numeric',
		day: 'numeric'
	};
	date = date.toLocaleString("ru", options);
	$('.form input[name="date"]').val(date);
	$('.form h2').text('Add recall');
	$('.form button').text('Add');
	$('.form input[name="date"]').parent().show();
	$('.form input[name="email"]').parent().show();
	$('#popup_area').show();
}

function publish_recall(target) {
	if(!confirm('Post a recall?')) return;
	id = target.getAttribute('target');
	$.post('recalls_functions.php', {
		password: password,
		id: id,
		method: 'publish'
	}, function(response) {
		if(response.indexOf('success') == -1) {
			alert('An error occurred while posting a recall');
		} else {
			alert('Recall successfully posted');
			load_recalls(password);
		}
	});
}

function edit_recall_action(target) {
	id = target.getAttribute('target');
	$('.form input[name="date"]').val($('div#' + id + ' .date .data').text());
	$('.form input[name="date"]').parent().hide();
	$('.form input[name="name"]').val($('div#' + id + ' .name .data').text());
	$('.form input[name="email"]').val($('div#' + id + ' .email .data').text());
	$('.form input[name="email"]').parent().hide();
	$('.form textarea[name="text"]').val($('div#' + id + ' .text .data').text());
	$('.form h2').text('Edit recall');
	$('.form button').text('Edit').attr('target', id);
	$('#popup_area').show();
}

function remove_recall(target) {
	if(!confirm('Remove a recall?')) return;
	id = target.getAttribute('target');
	$.post('recalls_functions.php', {
		password: password,
		id: id,
		method: 'remove'
	}, function(response) {
		if(response.indexOf('success') == -1) {
			alert('An error occurred while removing a recall');
		} else {
			alert('Recall successfully deleted');
			load_recalls(password);
		}
	});
}

function add_and_edit(button)
{
	if (button.textContent == 'Add') {
		if(!confirm('Add a recall?')) return;
		let date = $('.form input[name="date"]').val();
		let name = $('.form input[name="name"]').val();
		let email = $('.form input[name="email"]').val();
		let text = $('.form textarea[name="text"]').val();
		$.post('recalls_functions.php', {
			password: password,
			date: date,
			name: name,
			email: email,
			text: text,
			method: 'add'
		}, function(response) {
			if(response.indexOf('success') == -1) {
				alert('An error occurred while adding a recall');
			} else {
				alert('Recall successfully added');
				$('#popup_area').hide();
				load_recalls(password);
			}
		});
	}
	else if (button.textContent == 'Edit') {
		if(!confirm('Edit a recall?')) return;
		let name = $('.form input[name="name"]').val();
		let text = $('.form textarea[name="text"]').val();
		let id = button.getAttribute('target');
		$.post('recalls_functions.php', {
			password: password,
			id: id,
			name: name,
			text: text,
			method: 'edit'
		}, function(response) {
			if(response.indexOf('success') == -1) {
				alert('An error occurred while editing a recall');
			} else {
				alert('Recall successfully edited');
				load_recalls(password);
				button.removeAttribute('target');
				$('#popup_area').hide();
			}
		});
	}
}

let password = prompt('Enter access key:');

$(document).ready(function() {
	load_recalls(password);
	$('.overlay').click(function() {
		$('#popup_area').hide();
	});
});