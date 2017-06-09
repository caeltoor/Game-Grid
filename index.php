<?php
$username = isset($_GET["id"]) ? $_GET['id'] : '';

?>
	<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8" />
		<title>Steam grid test form</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script src="js/formReload.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="css/grid.css">
	</head>
	<body>
	<div id="aaronBar">
		<a href="index.php" id="homeLink">Test</a>
		<div id="searchDiv">
			<form id="searchForm">
				<input type="text" id="searchId" />
			</form>
		</div>
	</div>
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
echo "
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
	";
?>
</body>
</html>