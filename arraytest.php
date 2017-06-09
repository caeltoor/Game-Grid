<?php header('Content-type: text/html; charset=utf-8');
#eventually fill from a form i guess
$username = "Adaghar";


#Curl pulls profile page
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'http://steamcommunity.com/id/' . $username . '/games?tab=all&sort=name');
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$gameList = curl_exec($ch);

curl_close($ch);

#Extract var rgGames
$pattern = "/var rgGames = .*];/";

preg_match($pattern, $gameList, $matches);

#creates 2d array containing all appIDs in $secondmatches[1]
preg_match_all("/\"appid\":\"?([0-9]+)\"?,/" ,$matches[0],$secondmatches);

$appIds = $secondmatches[1];


#Actual HTML and stuff goes here

print_r($appIds);


?>