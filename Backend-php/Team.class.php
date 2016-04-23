<?php
class Team {
	protected $teamBlue;
	protected $teamRed;
	protected $teamBlueArgonym;
	protected $teamRedArgonym;
	protected $teamBlueTotalKills = 0;
	protected $teamBlueTotalDeaths = 0;
	protected $teamBlueFirstBlood;
	protected $teamBlueFirstTower;
	protected $teamBlueFirstInhibitor;
	protected $teamBlueFirstBaron;
	protected $teamBlueFirstDragon;
	protected $teamBlueFirstRiftHerald;
	protected $teamBlueTowerKills;
	protected $teamBlueInhibitorKills;
	protected $teamBlueBaronKills;
	protected $teamBlueDragonKills;
	protected $teamBlueRiftHeraldKills;
	protected $teamBlueWinOrLose;
	protected $teamRedTotalKills = 0;
	protected $teamRedTotalDeaths = 0;
	protected $teamRedFirstBlood;
	protected $teamRedFirstTower;
	protected $teamRedFirstInhibitor;
	protected $teamRedFirstBaron;
	protected $teamRedFirstDragon;
	protected $teamRedFirstRiftHerald;
	protected $teamRedTowerKills;
	protected $teamRedInhibitorKills;
	protected $teamRedBaronKills;
	protected $teamRedDragonKills;
	protected $teamRedRiftHeraldKills;
	protected $teamRedWinOrLose;
	
	function __construct($tournamentIndex, $matchJson, $getJson, $leagueId, $gameRealm, $gameId, $matchHash, $team1Players, $team2Players, $team1Name, $team2Name, $team1Argonym, $team2Argonym, $matchSQLID) {
		$teamMatchDataJson = $getJson->getNewV1MatchStatsDetails($gameRealm, $gameId, $matchHash);
		if($gameRealm == '' || $gameId == '' || $matchHash == '') {
			// Do nothing
		}else {
			$matchRosterPlayerBlue = array();
			$matchRosterPlayerRed = array();
			$playerIndex = 0;
			foreach($teamMatchDataJson->participantIdentities as $players) {
				if($playerIndex <= 4) {
					// split team name and player name, [ "team - player" ] example [ "FNC Febiven" ]
					$name = explode(" ", $players->player->summonerName);
					array_push($matchRosterPlayerBlue, $name[1]);
					$this->setTeamBlueArgonym($name[0]);
				}else {
					// split team name and player name, [ "team - player" ] example [ "H2K Jankos" ]
					$name = explode(" ", $players->player->summonerName);
					array_push($matchRosterPlayerRed, $name[1]);
					$this->setTeamRedArgonym($name[0]);
				}
				$playerIndex++;
			}
			
			if($team1Argonym == $this->getTeamBlueArgonym() & $team2Argonym == $this->getTeamRedArgonym()) {
				$this->setTeamBlue($team1Name);
				$this->setTeamRed($team2Name);
			}else {
				$this->setTeamRed($team1Name);
				$this->setTeamBlue($team2Name);
			}
			
			$teamIndex = 0;
			foreach($teamMatchDataJson->participants as $teamMember) {
				if($teamIndex <= 4) {
					$this->setTeamBlueTotalKills($teamMember->stats->kills);
					$this->setTeamBlueTotalDeaths($teamMember->stats->deaths);
				}else {
					$this->setTeamRedTotalKills($teamMember->stats->kills);
					$this->setTeamRedTotalDeaths($teamMember->stats->deaths);
				}
				$teamIndex++;
			}
			
			$teamIndex = 0;
			foreach($teamMatchDataJson->teams as $team) {
				if($teamIndex == 0) {
					$this->setTeamBlueFirstBlood($team->firstBlood);
					$this->setTeamBlueFirstTower($team->firstTower);
					$this->setTeamBlueFirstInhibitor($team->firstInhibitor);
					$this->setTeamBlueFirstBaron($team->firstBaron);
					$this->setTeamBlueFirstDragon($team->firstDragon);
					$this->setTeamBlueFirstRiftHerald($team->firstRiftHerald);
					
					$this->setTeamBlueTowerKills($team->towerKills);
					$this->setTeamBlueInhibitorKills($team->inhibitorKills);
					$this->setTeamBlueBaronKills($team->baronKills);
					$this->setTeamBlueDragonKills($team->dragonKills);
					$this->setTeamBlueRiftHeraldKills($team->riftHeraldKills);
					
					$this->setTeamBlueWinOrLose($team->win);
				}else {
					$this->setTeamRedFirstBlood($team->firstBlood);
					$this->setTeamRedFirstTower($team->firstTower);
					$this->setTeamRedFirstInhibitor($team->firstInhibitor);
					$this->setTeamRedFirstBaron($team->firstBaron);
					$this->setTeamRedFirstDragon($team->firstDragon);
					$this->setTeamRedFirstRiftHerald($team->firstRiftHerald);
					
					$this->setTeamRedTowerKills($team->towerKills);
					$this->setTeamRedInhibitorKills($team->inhibitorKills);
					$this->setTeamRedBaronKills($team->baronKills);
					$this->setTeamRedDragonKills($team->dragonKills);
					$this->setTeamRedRiftHeraldKills($team->riftHeraldKills);
					
					$this->setTeamRedWinOrLose($team->win);
				}
				$teamIndex++;
			}
			
			$this->echoDebugTeamInfo($matchRosterPlayerBlue, $matchRosterPlayerRed);
			
			
			$teamSQLID = InsertTeam($this->getTeamBlue(), $this->getTeamBlueArgonym(), $matchSQLID);
			
			$startingIndex = 0;
			for($RosterArrayIndex = 0; $RosterArrayIndex < count($matchRosterPlayerBlue); $RosterArrayIndex++) {
				$playerSQLID = InsertPlayer($matchRosterPlayerBlue[$RosterArrayIndex], $teamSQLID);
				$team = $matchRosterPlayerBlue;
				
				$Player = new Player($teamMatchDataJson, $team, $startingIndex, $playerSQLID, $matchSQLID);
				$startingIndex++;
			}
			InsertTeamPerformance($this->getTeamBlueFirstBlood(), $this->getTeamBlueFirstTower(), $this->getTeamBlueFirstInhibitor(), $this->getTeamBlueFirstBaron(), $this->getTeamBlueFirstDragon(), $this->getTeamBlueFirstRiftHerald(), $this->getTeamBlueTowerKills(), $this->getTeamBlueInhibitorKills(), $this->getTeamBlueBaronKills(), $this->getTeamBlueDragonKills(), $this->getTeamBlueRiftHeraldKills(), $matchSQLID, $teamSQLID, $this->getTeamBlueWinOrLose());
			
			$teamSQLID = InsertTeam($this->getTeamRed(), $this->getTeamRedArgonym(), $matchSQLID);
			for($RosterArrayIndex = 0; $RosterArrayIndex < count($matchRosterPlayerRed); $RosterArrayIndex++) {
				$playerSQLID = InsertPlayer($matchRosterPlayerRed[$RosterArrayIndex], $teamSQLID);
				$team = $matchRosterPlayerRed;
				
				$Player = new Player($teamMatchDataJson, $team, $startingIndex, $playerSQLID, $matchSQLID);
				$startingIndex++;
			}
			InsertTeamPerformance($this->getTeamRedFirstBlood(), $this->getTeamRedFirstTower(), $this->getTeamRedFirstInhibitor(), $this->getTeamRedFirstBaron(), $this->getTeamRedFirstDragon(), $this->getTeamRedFirstRiftHerald(), $this->getTeamRedTowerKills(), $this->getTeamRedInhibitorKills(), $this->getTeamRedBaronKills(), $this->getTeamRedDragonKills(), $this->getTeamRedRiftHeraldKills(), $matchSQLID, $teamSQLID, $this->getTeamRedWinOrLose());
		}
	}
	
	
	/*function __destruct() {
		$this->teamName = null;
		$this->teamId = null;
	}*/
	
	// Sets
	// Set Team tournament id
	function setTeamId($teamId) {
		$this->teamId = $teamId;
	}
	
	function setTeamBlueTotalKills($kills) {
		$this->teamBlueTotalKills += $kills;
	}
	
	function setTeamBlueTotalDeaths($deaths) {
		$this->teamBlueTotalDeaths += $deaths;
	}
	
	function setTeamBlueFirstBlood($firstBlood) {
		if($firstBlood == "") {
			$this->teamBlueFirstBlood = 0;
		}else {
			$this->teamBlueFirstBlood = $firstBlood;
		}
	}
	
	function setTeamBlueFirstTower($firstTower) {
		if($firstTower == "") {
			$this->teamBlueFirstTower = 0;
		}else {
			$this->teamBlueFirstTower = $firstTower;
		}
	}
	
	function setTeamBlueFirstInhibitor($firstInhibitor) {
		if($firstInhibitor == "") {
			$this->teamBlueFirstInhibitor = 0;
		}else {
			$this->teamBlueFirstInhibitor = $firstInhibitor;
		}
	}
	
	function setTeamBlueFirstBaron($firstBaron) {
		if($firstBaron == "") {
			$this->teamBlueFirstBaron = 0;
		}else {
			$this->teamBlueFirstBaron = $firstBaron;
		}
	}
	
	function setTeamBlueFirstDragon($firstDragon) {
		if($firstDragon == "") {
			$this->teamBlueFirstDragon = 0;
		}else {
			$this->teamBlueFirstDragon = $firstDragon;
		}
	}
	
	function setTeamBlueFirstRiftHerald($firstRiftHerald) {
		if($firstRiftHerald == "") {
			$this->teamBlueFirstRiftHerald = 0;
		}else {
			$this->teamBlueFirstRiftHerald = $firstRiftHerald;
		}
	}
	
	function setTeamBlueTowerKills($TowerKills) {
		$this->teamBlueTowerKills = $TowerKills;
	}
	
	function setTeamBlueInhibitorKills($InhibitorKills) {
		$this->teamBlueInhibitorKills = $InhibitorKills;
	}
	
	function setTeamBlueBaronKills($BaronKills) {
		$this->teamBlueBaronKills = $BaronKills;
	}
	
	function setTeamBlueDragonKills($DragonKills) {
		$this->teamBlueDragonKills = $DragonKills;
	}
	
	function setTeamBlueRiftHeraldKills($RiftHeraldKills) {
		if($RiftHeraldKills == "") {
			$this->teamBlueRiftHeraldKills = 0;
		}else {
			$this->teamBlueRiftHeraldKills = $RiftHeraldKills;
		}
	}
	
	function setTeamBlueWinOrLose($WinOrLose) {
		$this->teamBlueWinOrLose = $WinOrLose;
	}
	
	function setTeamRedTotalKills($kills) {
		$this->teamRedTotalKills += $kills;
	}
	
	function setTeamRedTotalDeaths($deaths) {
		$this->teamRedTotalDeaths += $deaths;
	}
	
	function setTeamRedFirstBlood($firstBlood) {
		if($firstBlood == "") {
			$this->teamRedFirstBlood = 0;
		}else {
			$this->teamRedFirstBlood = $firstBlood;
		}
	}
	
	function setTeamRedFirstTower($firstTower) {
		if($firstTower == "") {
			$this->teamRedFirstTower = 0;
		}else {
			$this->teamRedFirstTower = $firstTower;
		}
	}
	
	function setTeamRedFirstInhibitor($firstInhibitor) {
		if($firstInhibitor == "") {
			$this->teamRedFirstInhibitor = 0;
		}else {
			$this->teamRedFirstInhibitor = $firstInhibitor;
		}
	}
	
	function setTeamRedFirstBaron($firstBaron) {
		if($firstBaron == "") {
			$this->teamRedFirstBaron = 0;
		}else {
			$this->teamRedFirstBaron = $firstBaron;
		}
	}
	
	function setTeamRedFirstDragon($firstDragon) {
		if($firstDragon == "") {
			$this->teamRedFirstDragon = 0;
		}else {
			$this->teamRedFirstDragon = $firstDragon;
		}
	}
	
	function setTeamRedFirstRiftHerald($firstRiftHerald) {
		if($firstRiftHerald == "") {
			$this->teamRedFirstRiftHerald = 0;
		}else {
			$this->teamRedFirstRiftHerald = $firstRiftHerald;
		}
	}
	
	function setTeamRedTowerKills($TowerKills) {
		$this->teamRedTowerKills = $TowerKills;
	}
	
	function setTeamRedInhibitorKills($InhibitorKills) {
		$this->teamRedInhibitorKills = $InhibitorKills;
	}
	
	function setTeamRedBaronKills($BaronKills) {
		$this->teamRedBaronKills = $BaronKills;
	}
	
	function setTeamRedDragonKills($DragonKills) {
		$this->teamRedDragonKills = $DragonKills;
	}
	
	function setTeamRedRiftHeraldKills($RiftHeraldKills) {
		if($RiftHeraldKills == "") {
			$this->teamRedRiftHeraldKills = 0;
		}else {
			$this->teamRedRiftHeraldKills = $RiftHeraldKills;
		}
	}
	
	function setTeamRedWinOrLose($WinOrLose) {
		$this->teamRedWinOrLose = $WinOrLose;
	}
	
	function setTeamBlue($name) {
		$this->teamBlue = $name;
	}
	
	function setTeamRed($name) {
		$this->teamRed = $name;
	}
	
	function setTeamBlueArgonym($argonym) {
		$this->teamBlueArgonym = $argonym;
	}
	
	function setTeamRedArgonym($argonym) {
		$this->teamRedArgonym = $argonym;
	}
	
	// Gets
	// Get team tournament id
	function getTeamId() {
		return $this->teamId;
	}
	
	function getTeamBlueTotalKills() {
		return $this->teamBlueTotalKills;
	}
	
	function getTeamBlueTotalDeaths() {
		return $this->teamBlueTotalDeaths;
	}
	
	function getTeamBlueFirstBlood() {
		return $this->teamBlueFirstBlood;
	}
	
	function getTeamBlueFirstTower() {
		return $this->teamBlueFirstTower;
	}
	
	function getTeamBlueFirstInhibitor() {
		return $this->teamBlueFirstInhibitor;
	}
	
	function getTeamBlueFirstBaron() {
		return $this->teamBlueFirstBaron;
	}
	
	function getTeamBlueFirstDragon() {
		return $this->teamBlueFirstDragon;
	}
	
	function getTeamBlueFirstRiftHerald() {
		return $this->teamBlueFirstRiftHerald;
	}
	
	function getTeamBlueTowerKills() {
		return $this->teamBlueTowerKills;
	}
	
	function getTeamBlueInhibitorKills() {
		return $this->teamBlueInhibitorKills;
	}
	
	function getTeamBlueBaronKills() {
		return $this->teamBlueBaronKills;
	}
	
	function getTeamBlueDragonKills() {
		return $this->teamBlueDragonKills;
	}
	
	function getTeamBlueRiftHeraldKills() {
		return $this->teamBlueRiftHeraldKills;
	}
	
	function getTeamBlueWinOrLose() {
		return $this->teamBlueWinOrLose;
	}
	
	function getTeamRedTotalKills() {
		return $this->teamRedTotalKills;
	}
	
	function getTeamRedTotalDeaths() {
		return $this->teamRedTotalDeaths;
	}
	
	function getTeamRedFirstBlood() {
		return $this->teamRedFirstBlood;
	}
	
	function getTeamRedFirstTower() {
		return $this->teamRedFirstTower;
	}
	
	function getTeamRedFirstInhibitor() {
		return $this->teamRedFirstInhibitor;
	}
	
	function getTeamRedFirstBaron() {
		return $this->teamRedFirstBaron;
	}
	
	function getTeamRedFirstDragon() {
		return $this->teamRedFirstDragon;
	}
	
	function getTeamRedFirstRiftHerald() {
		return $this->teamRedFirstRiftHerald;
	}
	
	function getTeamRedTowerKills() {
		return $this->teamRedTowerKills;
	}
	
	function getTeamRedInhibitorKills() {
		return $this->teamRedInhibitorKills;
	}
	
	function getTeamRedBaronKills() {
		return $this->teamRedBaronKills;
	}
	
	function getTeamRedDragonKills() {
		return $this->teamRedDragonKills;
	}
	
	function getTeamRedRiftHeraldKills() {
		return $this->teamRedRiftHeraldKills;
	}
	
	function getTeamRedWinOrLose() {
		return $this->teamRedWinOrLose;
	}
	
	function getTeamBlue() {
		return $this->teamBlue;
	}
	
	function getTeamRed() {
		return $this->teamRed;
	}
	
	function getTeamBlueArgonym() {
		return $this->teamBlueArgonym;
	}
	
	function getTeamRedArgonym() {
		return $this->teamRedArgonym;
	}
	
		// Debug	
	private function echoDebugTeamInfo($matchRosterPlayerBlue, $matchRosterPlayerRed) {
		var_dump($matchRosterPlayerBlue);
		echo '<br>';
		var_dump($matchRosterPlayerRed);
		echo '<br>';
		
		echo "---------------------- TeamBlue [ " . $this->getTeamBlue() . " ] ----------------------<br>";
		echo "First Blood: " . $this->getTeamBlueFirstBlood() . '<br>' . "First Tower: " . $this->getTeamBlueFirstTower() . '<br>' . "First Inhibitor: " . $this->getTeamBlueFirstInhibitor() . '<br>' . "First Baron: " . $this->getTeamBlueFirstBaron() . '<br>' . "First Dragon: " . $this->getTeamBlueFirstDragon() . '<br>' . "First RiftHerald: " . $this->getTeamBlueFirstRiftHerald() . '<br>';
		echo "Tower takedowns: " . $this->getTeamBlueTowerKills() . '<br>' . "Inhibitor takedowns: " . $this->getTeamBlueInhibitorKills() . '<br>' . "Baron kills: " . $this->getTeamBlueBaronKills() . '<br>' . "Dragon kills: " . $this->getTeamBlueDragonKills() . '<br>' . "RiftHerald kills: " . $this->getTeamBlueRiftHeraldKills() . '<br>' . "TeamKills: " . $this->getTeamBlueTotalKills() . '<br>' . "Team Deaths: " . $this->getTeamBlueTotalDeaths() . '<br>';
		echo "Team win: " . $this->getTeamBlueWinOrLose() . '<br>';
		echo "----------------------------------------------------<br>";
		
		echo "---------------------- TeamRed [ " . $this->getTeamRed() . " ] ----------------------<br>";
		echo "First Blood: " . $this->getTeamRedFirstBlood() . '<br>' . "First Tower: " . $this->getTeamRedFirstTower() . '<br>' . "First Inhibitor: " . $this->getTeamRedFirstInhibitor() . '<br>' . "First Baron: " . $this->getTeamRedFirstBaron() . '<br>' . "First Dragon: " . $this->getTeamRedFirstDragon() . '<br>' . "First RiftHerald: " . $this->getTeamRedFirstRiftHerald() . '<br>';
		echo "Tower takedowns: " . $this->getTeamRedTowerKills() . '<br>' . "Inhibitor takedowns: " . $this->getTeamRedInhibitorKills() . '<br>' . "Baron kills: " . $this->getTeamRedBaronKills() . '<br>' . "Dragon kills: " . $this->getTeamRedDragonKills() . '<br>' . "RiftHerald kills: " . $this->getTeamRedRiftHeraldKills() . '<br>' . "TeamKills: " . $this->getTeamRedTotalKills() . '<br>' . "Team Deaths: " . $this->getTeamRedTotalDeaths() . '<br>';
		echo "Team win: " . $this->getTeamRedWinOrLose() . '<br>';
		echo "----------------------------------------------------<br>";
	}
}
?>