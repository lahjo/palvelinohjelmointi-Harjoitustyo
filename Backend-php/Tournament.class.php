<?php 
class Tournament {
	protected $leagueTitle;
	protected $leagueHashId;
	protected $startDate;
	protected $endDate;
	
	
	// Consructor for Tournament->Schedule->Match->Team->Player
	function __construct($leagueId) {
		$getJson = new JsonUrls;
		$highlanderTournamentsJson = $getJson->getNewV2HighlanderTournaments($leagueId);
		
		for($tournamentIndex = 0; $tournamentIndex < count($highlanderTournamentsJson->highlanderTournaments); $tournamentIndex++) {
			$this->ParseData($highlanderTournamentsJson, $tournamentIndex);
				
			// Debug
			$this->echoDebugConstruct1();

			// Insert tournament data to database
			$RowMysqliInsertId = InsertTournament($this->getLeagueTitle(), $this->getLeagueHashId(), $this->getStartDate(), $this->getEndDate());
			
			$Schedule = new Schedule($tournamentIndex, $highlanderTournamentsJson, $getJson, $leagueId, $RowMysqliInsertId, $this->getLeagueHashId());
		}
	}
	
	// Class destructor
	function __destruct() {
		$this->leagueTitle = null;
		$this->leagueHashId = null;
		$this->startDate = null;
		$this->endDate = null;
	}
	
	// Parse Json file data
	private function ParseData($highlanderTournamentsJson, $tournamentIndex) {
		// Set tournament title
		$this->setLeagueTitle($highlanderTournamentsJson->highlanderTournaments[$tournamentIndex]->title);
		
		// Set tournament Hash
		$this->setLeagueHashId($highlanderTournamentsJson->highlanderTournaments[$tournamentIndex]->id);
		
		// Set tournament starting date if it exsist
		if (array_key_exists('startDate',$highlanderTournamentsJson->highlanderTournaments[$tournamentIndex])) {
			$this->setStartDate(date('Y-m-d', strtotime(str_replace('-', '/', $highlanderTournamentsJson->highlanderTournaments[$tournamentIndex]->startDate))));
		}else {
			$this->setStartDate("NULL");
		}
		
		// Set tournament ending date if it exsist
		if (array_key_exists('endDate',$highlanderTournamentsJson->highlanderTournaments[$tournamentIndex])) {
			$this->setEndDate(date('Y-m-d', strtotime(str_replace('-', '/', $highlanderTournamentsJson->highlanderTournaments[$tournamentIndex]->endDate))));
		}else {
			$this->setEndDate("NULL");
		}
	}
	
	// Sets
	// Set Tournament league title
	function setLeagueTitle($leagueTitle) {
		$leagueTitle = str_replace('_', ' ', $leagueTitle);
		$this->leagueTitle = $leagueTitle;
	}
	
	// Set Tournament league hash id
	function setLeagueHashId($leagueHashId) {
		$this->leagueHashId = $leagueHashId;
	}
	
	// Set Tournament league starting date
	function setStartDate($startDate) {
		$this->startDate = $startDate;
	}
	
	// Set Tournament league ending date
	function setEndDate($endDate) {
		$this->endDate = $endDate;
	}
	
	// Gets
	// Get Tournament league title
	function getLeagueTitle() {
		return $this->leagueTitle;
	}
	
	// Get Tournament league hash id
	function getLeagueHashId() {
		return $this->leagueHashId;
	}
	
	// Get Tournament league starting date
	function getStartDate() {
		return $this->startDate;
	}
	
	// Get Tournament league ending date
	function getEndDate() {
		return $this->endDate;
	}
	
	// Debug	
	private function echoDebugConstruct1() {
		echo "-------------------------------------------------------------------" . '<br>';
		echo "<b>League title:</b> " . $this->getLeagueTitle() . "<br>";
		echo "<b>Tournament ID:</b> " . $this->getLeagueHashId() . "<br>";
		echo "<b>Starting Date:</b> " . $this->getStartDate() . "<br>";
		echo "<b>Ending date:</b> " .$this->getEndDate() . "<br>";
		echo "-------------------------------------------------------------------" . '<br>';
	}
}
//*********************************** Lol tournament link usage **************************************//
// Url consctruct: http://api.lolesports.com/api/v2/highlanderTournaments?league= { Torunament ID }
// Url EU: http://api.lolesports.com/api/v2/highlanderTournaments?league=3
//****************************************************************************************************//
?>