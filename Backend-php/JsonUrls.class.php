<?php
class JsonUrls {
	protected $apiV2HighlanderTournaments;
	protected $apiV1Leagues;
	protected $apiV1ScheduleItemsID;
	protected $apiV2HighlanderMatchDetailsLeagueHashIdMatchId;
	protected $apiV1MatchStatsDetails;
	protected $apiV1Teams;
	
	function getNewV2HighlanderTournaments($leagueId) {
		$json = file_get_contents('http://api.lolesports.com/api/v2/highlanderTournaments?league=' . $leagueId);
		$this->apiV2HighlanderTournaments = json_decode($json);
		
		return $this->apiV2HighlanderTournaments;
	}
	
	function getNewV1Leagues() {
		$json = file_get_contents('http://api.lolesports.com/api/v1/leagues');
		$this->apiV1Leagues = json_decode($json);
		
		return $this->apiV1Leagues;
	}
	
	function getNewV1ScheduleItemsID($leagueId) {
		$json = file_get_contents('http://api.lolesports.com/api/v1/scheduleItems?leagueId=' . $leagueId);
		$this->apiV1ScheduleItemsID = json_decode($json);
		
		return $this->apiV1ScheduleItemsID;
	}
	
	function getNewV2HighlanderMatchDetailsLeagueHashIdMatchId($leagueHashId, $matchId) {
		$json = file_get_contents('http://api.lolesports.com/api/v2/highlanderMatchDetails?tournamentId=' . $leagueHashId . '&matchId=' . $matchId);
		$this->apiV2HighlanderMatchDetailsLeagueHashIdMatchId = json_decode($json);
		
		return $this->apiV2HighlanderMatchDetailsLeagueHashIdMatchId;
	}
	
	function getNewV1Teams($teamSlug, $tournamentId) {
		$json = file_get_contents('http://api.lolesports.com/api/v1/teams?slug=' . $teamSlug . '&tournament=' . $tournamentId);
		$this->apiV1Teams = json_decode($json);
		
		return $this->apiV1Teams;
	}
	
	function getNewV1MatchStatsDetails($gameRealm, $gameId, $gameHash) {
		$json = file_get_contents('https://acs.leagueoflegends.com/v1/stats/game/' . $gameRealm . '/' . $gameId . '?gameHash=' . $gameHash);
		$this->apiV1MatchStatsDetails = json_decode($json);
		
		return $this->apiV1MatchStatsDetails;
	}
	
	function getNewV2TournamentPlayers($leagueHashId) {
		$json = file_get_contents('http://api.lolesports.com/api/v2/tournamentPlayerStats?tournamentId=' . $gameHash);
		$this->apiV1MatchStatsDetails = json_decode($json);
		
		return $this->apiV1MatchStatsDetails;
	}
}
?>