function formSender(steamId) {
	$("div#loading").html("<img src='img/loading.gif' style='margin-top:100px;'/>");
	$.post("./idListener.php", { id: steamId }, function(data) {
		if (data == "0") {
			//0 means Steam ID is invalid
			$("div#error").html(steamId + " is not a valid Steam ID.");
			$("div#formHolder").css("display", "block");
		} else {
			//Steam ID exists, hide the form and load the pictures
			$("div#formHolder").css("display", "none");
			$("body").append(data);
			
			//After gameHolder is loaded in, bind the mouse over events to each game tile
			//These events will control the mouseover behavior of the tiles, causing them to fade out and show the stats hidden behind them
			$('#gameHolder').on('mouseover', '.tileHolder', 
				function(e) {
					hoverTimerSingleton.setTimer($(this).children('.tile'));
				}
			);
			$('#gameHolder').on('mouseout', '.tileHolder', 
				function(e) {
					hoverTimerSingleton.clearTimer();
					$(this).children('.tile').animate({opacity: 1},500);
				}
			);
			
			function hoverFadeOut(elem) {
				$(elem).animate({opacity: .1},500);
			}
			
			//Singleton controls the access to the timers, allowing the mouseout function to clear the correct setTimeout
			var hoverTimerSingleton = {
				//initialize as empty
				clearTimer: function() { },
				
				//sets a timer until mouseover affect takes place
				//sets clearTimer to reference that timer
				setTimer: function(action) {
					var timer = setTimeout(function() { hoverFadeOut(action); },400);
					this.clearTimer = function() {
						clearTimeout(timer);
					};
				}
			};
			$("#searchDiv").show();
			
			//pushes an artificial state to window histrory, allowing the back button to move between ajax-loaded portions of the site
			window.history.ready = true;
			window.history.pushState("","","index.php?id=" + steamId);			
		}
		$("button#submit").removeAttr("disabled");
		$("div#loading").html("");
	}, "html");
}