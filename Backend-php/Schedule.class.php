<?php 
class Schedule {
	protected $gameRealm;
	protected $gameId;
	protected $matchId;
	protected $playoffMatchRoundId;
	protected $roster1Hash;
	protected $roster2Hash;
	protected $matchRound;
	protected $matchState;
	
	// Consructor for Schedule->Match->Team->Player
	function __construct($tournamentIndex, $highlanderTournamentsJson, $getJson, $leagueId, $RowMysqliInsertId, $hash) {	
		foreach($highlanderTournamentsJson->highlanderTournaments[$tournamentIndex]->brackets as $match) {
			foreach($match->matches as $matchId) {
				
				// Playoff match
				if(count((array)$matchId->games) > 1) {
					$this->setMatchState($matchId->state);
					
					if($this->getMatchState() == 'resolved') {
						$this->setMatchId($matchId->id);
						$this->setRoster1Hash($matchId->input[0]->roster);
						$this->setRoster2Hash($matchId->input[1]->roster);
						
						foreach($matchId->games as $playoffs) {
							$this->setGameId($playoffs->gameId);
							$this->setGameRealm($playoffs->gameRealm);
							$this->setPlayoffMatchRoundId($playoffs->id);
							$this->setMatchRound($playoffs->name);
						
						// Debug
						$this->echoDebugScheduleInfo();
						
						$Match = new Match($tournamentIndex, $highlanderTournamentsJson->highlanderTournaments[$tournamentIndex]->id, $matchId->id, $getJson, $leagueId, $this->getGameRealm(), $this->getGameId(), $this->getPlayoffMatchRoundId(), $RowMysqliInsertId, $this->getMatchRound(), $this->getMatchState());
						}
					} else if($this->getMatchState() == 'unresolved') {
						$this->setMatchId($matchId->id);
						$Match = new Match($highlanderTournamentsJson->highlanderTournaments[$tournamentIndex]->id, $matchId->id, $getJson, $RowMysqliInsertId, $this->getMatchState());
					}
				}
				// Regular match
				else {
					$this->setMatchState($matchId->state);
					if($this->getMatchState() == 'resolved') {
						$this->setMatchId($matchId->id);
						
						$this->setRoster1Hash($matchId->input[0]->roster);
						$this->setRoster2Hash($matchId->input[1]->roster);
						
							foreach($matchId->games as $matchInfo) {
								$this->setGameId($matchInfo->gameId);
								$this->setGameRealm($matchInfo->gameRealm);
							}
						
					// Debug
					$this->echoDebugScheduleInfo();
					
					$Match = new Match($tournamentIndex, $highlanderTournamentsJson->highlanderTournaments[$tournamentIndex]->id, $matchId->id, $getJson, $leagueId, $this->getGameRealm(), $this->getGameId(), $RowMysqliInsertId);
					}else if($this->getMatchState() == 'unresolved') {
						$this->setMatchId($matchId->id);
						$Match = new Match($highlanderTournamentsJson->highlanderTournaments[$tournamentIndex]->id, $matchId->id, $getJson, $RowMysqliInsertId, $this->getMatchState());
				}
			  }
			}
		}
	}
	
	private function setGameRealm($realm) {
		$this->gameRealm = $realm;
	}
	
	private function setGameId($id) {
		$this->gameId = $id;
	}
	
	private function setMatchId($id) {
		$this->matchId = $id;
	}
	
	private function setRoster1Hash($rosterHash) {
		$this->roster1Hash = $rosterHash;
	}
	
	private function setRoster2Hash($rosterHash) {
		$this->roster2Hash = $rosterHash;
	}
	
	private function setPlayoffMatchRoundId($id) {
		$this->playoffMatchRoundId = $id;
	}
	
	function setMatchRound($round) {
		$this->matchRound = $round;
	}
	
	function setMatchState($state) {
		$this->matchState = $state;
	}
	
	function getGameRealm() {
		return $this->gameRealm;
	}
	
	function getGameId() {
		return $this->gameId;
	}
	
	function getMatchId() {
		return $this->matchId;
	}
	
	function getRoster1Hash() {
		return $this->roster1Hash;
	}
	
	function getRoster2Hash() {
		return $this->roster2Hash;
	}
	
	private function getPlayoffMatchRoundId() {
		return $this->playoffMatchRoundId;
	}
	
	// Get Match round
	function getMatchRound() {
		return $this->matchRound;
	}
	
	// Get Match state
	function getMatchState() {
		return $this->matchState;
	}
	
	
	
	
		// Debug	
	private function echoDebugScheduleInfo() {
		echo '<b>' . "Match ID: " . $this->getmatchId() . '</b>' . '</br>';
		echo $this->getRoster1Hash() . " vs " . $this->getRoster2Hash() . "<br>";
		echo "Map round: " . $this->getMatchRound() . "<br>";
	}
}
?>