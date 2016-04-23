<?php 
class Player {
	// Consructor for Schedule->Match->Team
	function __construct($playerMatchDataJson, $team, $startingIndex, $playerSQLID, $matchSQLID) {
			InsertPlayerPerformance($playerMatchDataJson->participants[$startingIndex]->stats->kills, $playerMatchDataJson->participants[$startingIndex]->stats->deaths, $playerMatchDataJson->participants[$startingIndex]->stats->assists, $playerSQLID, $matchSQLID);
	}
}
?>