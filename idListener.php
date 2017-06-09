<?php
$username = $_POST["id"];

include "GameListModifier.php";

$gameListModifier = new GameListModifier("GameDatabase/gameList.xml");

#Curl pulls profile page
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'http://steamcommunity.com/id/' . $username . '/games?tab=all&sort=name');
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$gameList = curl_exec($ch);

curl_close($ch);

$prepattern = "/<title>Steam Community :: Error<\/title>/";

if(preg_match($prepattern, $gameList)) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://steamcommunity.com/profiles/' . $username . '/games?tab=all&sort=name');
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	$gameList = curl_exec($ch);
	#echo $gameList;
	curl_close($ch);
}

if(preg_match($prepattern, $gameList)) {
	//BREAK
	//Upon failure we return a 0 to signify failure rather than forcing the user to reload everything, simple ne?
	echo "0";

} else {
	//REAL CODE
	#Extract var rgGames
		$pattern = "/var rgGames = .*];/";

		preg_match($pattern, $gameList, $matches);

		#creates 2d array that contains all appIDs in $secondmatches[1] and all names in $secondmatches[2]
		preg_match_all("/\"appid\":\"?([0-9]+)\"?,\"name\":\"(.+?)\",\"lo.+?(?:\"hours_forever\":\"?([0-9.]+)\"?|}}|\"last_played\")/" ,$matches[0],$secondmatches);

		$appIds = $secondmatches[1];
		$appNames = $secondmatches[2];
		$appHours = $secondmatches[3];

		$namePattern = "/<title>Steam Community :: (.+) :: Games<\/title>/";

		preg_match_all($namePattern, $gameList, $idHolder);
		
		echo "<h2 id='header'>Eyo son, it's ".$idHolder[1][0]."'s games.</h2><br />";
		echo "<div id='gameHolder'>";
		for($i = 0; $i < count($appIds); $i++){
			$imgurl = $gameListModifier->checkOldGame($appIds[$i]);
			//checks for a valid image url
			if ($imgurl == "") {
			#echo "Bad End. "; 
			}
			//Print the image etc.
			else {
				
				echo "<div class='tileHolder'><div class='appName' >" . $appNames[$i] . "</div><img src='" . $imgurl . "' alt ='" . $appNames[$i] . "' class='tile' /><div class='stats'>Hours played: " . $appHours[$i] . "</div></div>";
			
			}
			//}
		}
		echo "</div>";
}

?>