## Game Grid's Purpose

Game Grid was originally written by myself and Ben Tapio in Fall 2012, before the Steam API existed, to be able to display your steam library using the grid display from the steam client online.

## How v1 worked

V1 Worked by comparing all games on your games list page against an XML file which contained game IDs, names, and the url of the grid image. If the ID was not in the XML file, it would attempt to get the information by scraping the store page of that ID, and guessing the location of the grid image. If the grid image existed (checked via curl request), it would set a flag in the XML to 1, 0 if not. It also checked (arbitrarily) to see if the game was a free to play game, then removed it from the display.

In order to create the original XML file, Gabe Newell's steam profile was read from, since it has all games released on steam.