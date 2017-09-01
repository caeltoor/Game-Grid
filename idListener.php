<?php
$username = $_POST["id"];

include "apiKey.php";

# Convert username to steamID
$convert = file_get_contents('http://api.steampowered.com/ISteamUser/ResolveVanityURL/v0001/?key=' . $key . '&vanityurl=' . $username);
$success = json_decode($convert,true)["response"];

if ($success["success"] == 1) {
	$steamID = $success["steamid"];
	# Fetch json formatted profile info and game list
	$profileInfo = json_decode(file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key='. $key . '&steamids=' . $steamID), true)["response"]["players"][0];
	$gameList = json_decode(file_get_contents('http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=' . $key . '&steamid=' . $steamID . '&format=json&include_appinfo=1'), true)["response"];

	$response = json_encode(array_merge($success, $profileInfo, $gameList));

} elseif ($success["success"] == 42) {
	# Return a failure
	$response = json_encode($success);
}


echo $response;

?>