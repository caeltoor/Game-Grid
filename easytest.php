<?php

/*function url_exists($url) {
if (!$fp = curl_init($url)) return false;
return true;
}*/
function checkRemoteFile($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    // don't download content
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if(curl_exec($ch)!==FALSE)
    {
        return 1;
    }
    else
    {
        return 0;
    }
}

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'http://steamcommunity.com/profiles/76561198012538799/games?tab=all&sort=name');
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$gameList = curl_exec($ch);

curl_close($ch);

#$prepattern = "/<title>Steam Community \:\: Error<\/title>/";


$namePattern = "/<title>Steam Community :: (.+) :: Games<\/title>/";

preg_match_all($namePattern, $gameList, $idHolder);

print_r($idHolder[1]);

?>