<?php
$username = isset($_GET["id"]) ? $_GET['id'] : '';

?>
	<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8" />
		<title>Steam grid test form</title>
		<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
		<script src="js/formReload.js" type="text/javascript"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="css/grid.css">
	</head>
	<body>
	<nav class="navbar navbar-dark bg-dark sticky-top">
		<a class="navbar-brand" href="#">Game Grid</a>
		
		<div class="nav-item" id="searchDiv">
			<form id="searchForm">
				<input type="text" placeholder="Filter games by name" id="searchId" />
			</form>
		</div>
	</nav>
<?php
if(!$username) {
	echo "
		<script type='text/javascript'>
			<!--
			$(document).ready(function() {
				$('#submit').click(function(){
					$(this).attr('disabled', 'true');
					formSender($('#id').val());
				});
		";
}
else {
	echo "
		<style type='text/css'>
			#formHolder {
				display: none;
			}
		</style>
		<script type='text/javascript'>
			<!--
			$(document).ready(function() {
				formSender('" . $username . "');
				
				$('#submit').click(function(){
					$(this).attr('disabled', 'true');
					formSender($('#id').val());
				});
		";
}
?>
				$('form#usernameForm').submit(function(event){
					event.preventDefault();
					$('#submit').click();
				});
				$(window).bind('popstate', function (ev){
					if (!window.history.ready && !ev.originalEvent.state){
					return; // workaround for popstate on load
					}
					window.history.ready = false;
					window.location = 'index.php';
				});
				$('#searchId').keyup(function () {
					var reg = new RegExp($(this).val(), 'i')
					$('.tileHolder').each(function () {
						if ($(this).find('> .appName').text().search(reg) < 0) {
							$(this).addClass('hidden');
						} else {
							$(this).removeClass('hidden');
						}
					});
				});
			});
			-->
		</script>
		<div id='formHolder'>
			<p id='instructions'>Enter a steam id</p>
			<form id='usernameForm'>
				<input id='id' name='id' type='text' />
				<button id='submit' type='button' value='Submit' >Submit</button>
			</form>
			<div id='error'></div>
		</div>
		<div id='loading'></div>
		<div id ='gameHolder'></div>
	";
</body>
</html>