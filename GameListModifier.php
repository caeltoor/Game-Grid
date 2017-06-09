<?php
/*
 * Class created for accessing and modifying the gameList xml doc.
 * For use in the Steam Grid app.
 *
 */
class GameListModifier {

	//Variable to hold XML document
	private $gameListDoc;
	//Root node of the XML
	private $gameListRoot;
	//The array to hold the appIds and picAvailable, definitely needs to be global in the class
	private $gmArray = array();
	private $needSave = 0;
	//
	
	//Create with default path if no args were passed in
	//Else use the arg
	public function __construct($xmlName) {
		//TODO: implement error checking
        $numargs = func_num_args();
		if($numargs == 0) {
			$this->gameListDoc = new DOMDocument();
			$this->gameListDoc->load("htdocs/gg/GameDatabase/gameList.xml");
			$this->gameListRoot = $this->gameListDoc->documentElement;
		}
		elseif($numargs == 1) {
			$this->gameListDoc = new DOMDocument();
			$this->gameListDoc->load($xmlName);
			$this->gameListRoot = $this->gameListDoc->documentElement;
		}
		$this->getGameList();
	}
	
	//Save the xml file
	function __destruct() {
		//TODO: implement constructs args statement
		if ($this->needSave){
			$this->gameListDoc->save("htdocs/gg/GameDatabase/gameList.xml");
		}
   }

	//Gets a list of games as [appId => picAvailabe]
	public function getGameList() {
	/*Array can be deprecated and replaced with realtime access to save memory use*/
		$games = $this->gameListDoc->getElementsByTagName("game");

		foreach($games as $game) {
			$appId = $game->childNodes->item(0)->nodeValue;
			$picAvailable = $game->childNodes->item(1)->nodeValue;
			$this->gmArray[$appId] = $picAvailable;
		}
        //ksort($this->gmArray);
	}

	//Check if a game is available in the xml database
	public function checkOldGame($appId) {
		//TODO
		//print_r($this->gmArray);
		if (empty($this->gmArray[$appId])) {
			$this->addNewGame($appId);
		}
		if	($this->gmArray[$appId] === "0"){ /*No picture found*/
			return ""; 
		}	elseif ($this->gmArray[$appId] === "1"){ /*Picture found*/
			return "http://cdn.edgecast.steamstatic.com/steam/apps/" . $appId ."/header.jpg"; 
		}
	}

	//Adds a new game to the xml file, checking whether it has a pic available on the steam cdn
	//Called whenever a game doesn't exist in the xml already
	public function addNewGame($appId) {
		//create new node for the game, appId, and picAvailable
		$gameNode = $this->gameListDoc->createElement("game");
		$appIdNode = $this->gameListDoc->createElement("appId");
		$picAvailableNode = $this->gameListDoc->createElement("picAvailable");
		//Add contents to the new nodes
		$appIdText = $this->gameListDoc->createTextNode($appId);
		//picAvailable determined by whether picture file on steam servers exists
		$picAvailable = $this->checkRemoteFile($appId);
		$picAvailableText = $this->gameListDoc->createTextNode($picAvailable);
		//Add nodes to document in descending order
		$this->gameListRoot->appendChild($gameNode);
		$gameNode->appendChild($appIdNode);
		$appIdNode->appendChild($appIdText);
		$gameNode->appendChild($picAvailableNode);
		$picAvailableNode->appendChild($picAvailableText);
		$this->gmArray[$appId] = $picAvailable;
		$this->needSave = 1;
	}

	//Checks steam's cdn for the header pic for a game
	function checkRemoteFile($appId)
	{
		$url = "http://cdn.edgecast.steamstatic.com/steam/apps/" . $appId ."/header.jpg";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		// don't download content
		curl_setopt($ch, CURLOPT_NOBODY, 1);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if(curl_exec($ch)!==FALSE)
		{
			curl_close($ch);
			return 1;
		}
		else
		{
			curl_close($ch);
			return 0;
		}	
	}
}
?>