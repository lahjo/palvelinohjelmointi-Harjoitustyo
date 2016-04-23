<?php 
class Match {
	protected $team1Slug;
	protected $team2Slug;
	protected $team1Name;
	protected $team2Name;
	protected $team1Acronym;
	protected $team2Acronym;
	protected $matchHash;
	
	function __construct() {
        $get_arguments       = func_get_args();
        $number_of_arguments = func_num_args();

        if (method_exists($this, $method_name = '__construct'.$number_of_arguments)) {
            call_user_func_array(array($this, $method_name), $get_arguments);
        }
    }
	
	// Regular season
	// Consructor for Match->Team->Player
	function __construct8($tournamentIndex, $leagueHashId, $matchId, $getJson, $leagueId, $gameRealm, $gameId, $RowMysqliInsertId) {
		$matchJson = $getJson->getNewV2HighlanderMatchDetailsLeagueHashIdMatchId($leagueHashId, $matchId);
		
		foreach($matchJson->gameIdMappings as $gameMapping) {
			$this->setMatchHash($gameMapping->gameHash);
		}
		
		if($gameRealm == '' || $gameId == '' || $this->getMatchHash() == '') {
			// upcoming?
		}else if($gameRealm != '' || $gameId != '' || $this->getMatchHash() != '') {
			$matchSQLID;
			foreach($matchJson->scheduleItems as $date) {
				$date = explode("T", $date->scheduledTime);
				$matchSQLID = InsertTournamentMatch($matchId, $date[0], $RowMysqliInsertId);
			}
			
			
			
			$team1Players = array();
			$team2Players = array();
			$roundIndex = 0;
			foreach($matchJson->teams as $roster) {
				
				foreach($roster->players as $players) {
					if($roundIndex == 0) {
						array_push($team1Players, $players);
					}else {
						array_push($team2Players, $players);
					}
				}
				
				if($roundIndex == 0) {	
					$this->setTeam1Name($roster->name);
					$this->setTeam1Slug($roster->slug);
					$this->setTeam1Acronym($roster->acronym);
				}else {
					$this->setTeam2Name($roster->name);
					$this->setTeam2Slug($roster->slug);
					$this->setTeam2Acronym($roster->acronym);
				}
				$roundIndex++;
			}
			
			$team1MebmerNames = $getJson->getNewV1Teams($this->getTeam1Slug(), $leagueId);
			$team1PlayerNames = array();
			foreach($team1MebmerNames->players as $player) {
				// Team members
				if(in_array($player->id, $team1Players)) {
					array_push($team1PlayerNames, $player->name);
				}
			}
			
			$team2MebmerNames = $getJson->getNewV1Teams($this->getTeam2Slug(), $leagueId);
			$team2PlayerNames = array();
			foreach($team2MebmerNames->players as $player) {
				if(in_array($player->id, $team2Players)) {
					array_push($team2PlayerNames, $player->name);
				}
			}

			$this->echoDebugMatchInfo($gameRealm, $gameId);
			
			$Team = new Team($tournamentIndex, $matchJson ,$getJson, $leagueId, $gameRealm, $gameId, $this->getMatchHash(), $team1PlayerNames, $team2PlayerNames, $this->getTeam1Slug(), $this->getTeam2Slug(), $this->getTeam1Arconym(), $this->getTeam2Arconym(), $matchSQLID);
		}
	}
	
	// Playoffs
	// Consructor for Match->Team->Player
	function __construct11($tournamentIndex, $leagueHashId, $matchId, $getJson, $leagueId, $gameRealm, $gameId, $matchRoundId, $RowMysqliInsertId, $matchRound, $MatchState) {
		$matchJson = $getJson->getNewV2HighlanderMatchDetailsLeagueHashIdMatchId($leagueHashId, $matchId);		
		if($gameRealm == '' || $gameId == '') {
				// Do nothing
			}else {
			$matchSQLID;
			foreach($matchJson->scheduleItems as $date) {
				$date = explode("T", $date->scheduledTime);
				$matchSQLID = InsertTournamentMatch($matchId, $date[0], $RowMysqliInsertId, $MatchState, $matchRound);
			}
			
			foreach($matchJson->gameIdMappings as $gameMapping) {
				if($gameMapping->id == $matchRoundId) {
					$this->setMatchHash($gameMapping->gameHash);
				}
			}
			
			$team1Players = array();
			$team2Players = array();
			$roundIndex = 0;
			foreach($matchJson->teams as $roster) {
				
				foreach($roster->players as $players) {
					if($roundIndex == 0) {
						array_push($team1Players, $players);
					}else {
						array_push($team2Players, $players);
					}
				}
				
				if($roundIndex == 0) {	
					$this->setTeam1Name($roster->name);
					$this->setTeam1Slug($roster->slug);
					$this->setTeam1Acronym($roster->acronym);
				}else {
					$this->setTeam2Name($roster->name);
					$this->setTeam2Slug($roster->slug);
					$this->setTeam2Acronym($roster->acronym);
				}
				$roundIndex++;
			}
			
			$team1MebmerNames = $getJson->getNewV1Teams($this->getTeam1Slug(), $leagueId);
			$team1PlayerNames = array();
			foreach($team1MebmerNames->players as $player) {
				// Team members
				if(in_array($player->id, $team1Players)) {
					array_push($team1PlayerNames, $player->name);
				}
			}
			
			$team2MebmerNames = $getJson->getNewV1Teams($this->getTeam2Slug(), $leagueId);
			$team2PlayerNames = array();
			foreach($team2MebmerNames->players as $player) {
				if(in_array($player->id, $team2Players)) {
					array_push($team2PlayerNames, $player->name);
				}
			}

			$this->echoDebugMatchInfo($gameRealm, $gameId);
			
			$Team = new Team($tournamentIndex, $matchJson ,$getJson, $leagueId, $gameRealm, $gameId, $this->getMatchHash(), $team1PlayerNames, $team2PlayerNames, $this->getTeam1Slug(), $this->getTeam2Slug(), $this->getTeam1Arconym(), $this->getTeam2Arconym(), $matchSQLID);
		}
	}
	
	// Playoffs Upcoming
	// Consructor for Match->Team->Player
	function __construct5($leagueHashId, $matchId, $getJson, $RowMysqliInsertId, $MatchState) {
		$matchJson = $getJson->getNewV2HighlanderMatchDetailsLeagueHashIdMatchId($leagueHashId, $matchId);
			$matchSQLID;
			foreach($matchJson->scheduleItems as $date) {
				$date = explode("T", $date->scheduledTime);
				$matchSQLID = InsertTournamentMatch($matchId, $date[0], $RowMysqliInsertId, $MatchState);
			}
	}
	
	// Sets
	// Set Team name
	function setTeam1Name($teamName) {
		$this->team1Name = $teamName;
	}
	
	// Set Team name
	function setTeam2Name($teamName) {
		$this->team2Name = $teamName;
	}
	
	// Set Team slug name
	function setTeam1Slug($slug) {
		$this->team1Slug = $slug;
	}
	
	// Set Team slug name
	function setTeam1Acronym($acronym) {
		$this->team1Acronym = $acronym;
	}
	
	// Set Team slug name
	function setTeam2Slug($slug) {
		$this->team2Slug = $slug;
	}
	
	// Set Team slug name
	function setTeam2Acronym($acronym) {
		$this->team2Acronym = $acronym;
	}
	
	// Set Match HashId
	function setMatchHash($hash) {
		$this->matchHash = $hash;
	}
	
	// Gets
	// Get Team name
	function getTeam1Name() {
		return $this->team1Name;
	}
	
	// Get Team name
	function getTeam2Name() {
		return $this->team2Name;
	}
	
	// Get Team slug name
	function getTeam1Slug() {
		return $this->team1Slug;
	}
	
	// Get Team slug name
	function getTeam2Slug() {
		return $this->team2Slug;
	}
	
	// Get Team argonym
	function getTeam1Arconym() {
		return $this->team1Acronym;
	}
	
	// Get Team argonym
	function getTeam2Arconym() {
		return $this->team2Acronym;
	}
	
	// Get Match HashId
	function getMatchHash() {
		return $this->matchHash;
	}
	
		// Debug	
	private function echoDebugMatchInfo($gameRealm, $gameId) {
		echo "GameRealm: " . $gameRealm . ", GameID: " . $gameId . ", GameHash: ". $this->getMatchHash() . "<br>";
		echo "Team name: " . $this->getTeam1Name() . ' | ';
		echo $this->getTeam1Slug() . '<br>';
		echo "Team name: " . $this->getTeam2Name() . ' | ';
		echo $this->getTeam2Slug() . '<br>';
	}
}
?>