<?php 
	function InsertTournament($title, $hash, $startingDate, $endingDate) {
		$conn = dbconnect();
		
		$rows = mysqli_query($conn,"SELECT * FROM tournament WHERE tournamentHash = '$hash'");
		if (mysqli_num_rows($rows) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
			$tournamentRow_id = $row["identifier"];
			}
		} else {
			if($startingDate == "NULL") {$startingDate = '0000/00/00';}
			if($endingDate == "NULL") {$endingDate = '0000/00/00';}
			mysqli_query($conn,"INSERT INTO $db_name.tournament (tournamentTitle, tournamentHash, startingDate, endingDate) VALUES ('".$title."', '".$hash."', '".$startingDate."', '".$endingDate."')");
			$tournamentRow_id = mysqli_insert_id($conn);
		}
		
		return $tournamentRow_id;
	}
	
	function InsertTournamentMatch($match, $date, $RowMysqliInsertId, $matchState, $matchRound) {
		$conn = dbconnect();
		$updateMatch = mysqli_query($conn,"SELECT * FROM tournamentMatch WHERE matchHash = '$match' AND state != '$matchState'");
		$playedMatch = mysqli_query($conn,"SELECT * FROM tournamentMatch WHERE matchHash = '$match' AND state = '$matchState' AND round = '$matchRound'");
		if (mysqli_num_rows($updateMatch) > 0) { 
			// Update
			mysqli_query($conn,"UPDATE $db_name.tournamentMatch SET matchHash = '".$match."' , date = '".$date."', round = '".$matchRound."', state = '".$matchState."') WHERE matchHash = '".$match."'");
		}else if(mysqli_num_rows($playedMatch) > 0) {
			// Do nothing
		}
		 // New Match
		else {
			mysqli_query($conn,"INSERT INTO $db_name.tournamentMatch (matchHash, date, round, state) VALUES ('".$match."', '".$date."', '".$matchRound."', '".$matchState."')");
			$matchRow_id = mysqli_insert_id($conn);
			mysqli_query($conn,"INSERT INTO $db_name.tournament_tournamentMatch (tournament_identifier, tournamentMatch_identifier) VALUES ('".$RowMysqliInsertId."', '".$matchRow_id."')");
		}

		mysqli_close($conn);
		
		return $matchRow_id;
	}
	
	function InsertTeam($teamName, $teamArgonym, $RowMysqliInsertId) {
		$conn = dbconnect();
		
		$rows = mysqli_query($conn,"SELECT * FROM team WHERE teamName = '$teamName'");
		if (mysqli_num_rows($rows) > 0) { 
			$row = mysqli_fetch_assoc($rows);
				mysqli_query($conn,"INSERT INTO $db_name.team_tournamentMatch (tournamentMatch_identifier, team_identifier) VALUES ('".$RowMysqliInsertId."', '".$row['identifier']."')");
				$teamRow_id = $row['identifier'];
		} else {
			mysqli_query($conn,"INSERT INTO $db_name.team (teamName, teamArgonym) VALUES ('".$teamName."', '".$teamArgonym."')");
			$teamRow_id = mysqli_insert_id($conn);
			mysqli_query($conn,"INSERT INTO $db_name.team_tournamentMatch (tournamentMatch_identifier, team_identifier) VALUES ('".$RowMysqliInsertId."', '".$teamRow_id."')");
		}

		mysqli_close($conn);
		
		return $teamRow_id;
	}
	
	function InsertPlayer($player, $RowMysqliInsertId) {
		$conn = dbconnect();
		
		// check if player already exsist
		$rows = mysqli_query($conn,"SELECT * FROM player WHERE playerName = '$player'");
		if (mysqli_num_rows($rows) > 0) { 
			// Get player ID
			$row = mysqli_fetch_assoc($rows);
			mysqli_query($conn,"INSERT INTO $db_name.player_team (player_identifier, team_identifier) VALUES ('".$row['identifier']."', '".$RowMysqliInsertId."')");
			$playerRow_id = $row['identifier'];
		} else {
			// new player
			mysqli_query($conn,"INSERT INTO $db_name.player (playerName) VALUES ('".$player."')");
			$playerRow_id = mysqli_insert_id($conn);
			mysqli_query($conn,"INSERT INTO $db_name.player_team (player_identifier, team_identifier) VALUES ('".$playerRow_id."', '".$RowMysqliInsertId."')");
		}

		mysqli_close($conn);
		
		return $playerRow_id;
	}
	
	function InsertTeamPerformance($firstBlood, $firstTower, $firstInhibitor, $firstBaron, $firstDragon, $firstRiftHerald, $towerKills, $inhibitorKills, $baronKills, $dragonKills, $riftHeraldKills, $matchId, $RowMysqliInsertId, $winOrLose) {
		$conn = dbconnect();
		
		// check if player already exsist
		/*$rows = mysqli_query($conn,"SELECT * FROM player WHERE playerName = '$player'");
		if (mysqli_num_rows($rows) > 0) { 
			// Get player ID
			$row = mysqli_fetch_assoc($rows);
			mysqli_query($conn,"INSERT INTO $db_name.player_team (player_identifier, team_identifier) VALUES ('".$row['identifier']."', '".$RowMysqliInsertId."')");
			echo "old player added<br>";
		} else {
			// new player
			mysqli_query($conn,"INSERT INTO $db_name.performanceTeam (firstBlood, firstTower, firstInhibitor, firstBaron, firstDragon, firstRiftHerald, towerTakedowns, inhibitorTakedowns, baronKills, dragonKills, riftHeraldKills, team_identifier) VALUES ('".$firstBlood."', '".$firstTower."', '".$firstInhibitor."', '".$firstBaron."', '".$firstDragon."', '".$firstRiftHerald."', '".$towerKills."', '".$inhibitorKills."', '".$baronKills."', '".$dragonKills."', '".$riftHeraldKills."', '".$matchId."', '".$RowMysqliInsertId."')");
		}*/
		$playedMatch = mysqli_query($conn,"SELECT * FROM performanceTeam WHERE tournamentMatch_identifier = '$matchId' AND team_identifier = '$matchState' AND round = '$RowMysqliInsertId'");
		if(mysqli_num_rows($rows) > 0) {
			
		}
		else {
			// new team performance
			mysqli_query($conn,"INSERT INTO $db_name.performanceTeam (firstBlood, firstTower, firstInhibitor, firstBaron, firstDragon, firstRiftHerald, towerTakedowns, inhibitorTakedowns, baronKills, dragonKills, riftHeraldKills, tournamentMatch_identifier, team_identifier, winOrLose) VALUES ('".$firstBlood."', '".$firstTower."', '".$firstInhibitor."', '".$firstBaron."', '".$firstDragon."', '".$firstRiftHerald."', '".$towerKills."', '".$inhibitorKills."', '".$baronKills."', '".$dragonKills."', '".$riftHeraldKills."', '".$matchId."', '".$RowMysqliInsertId."', '".$winOrLose."')") or die(mysqli_error($conn));
		}
		mysqli_close($conn);
	}
	
	function InsertPlayerPerformance($Kills, $Deaths, $Assists, $playerSQLID, $matchSQLID) {
		$conn = dbconnect();
		
		// check if player already exsist
		/*$rows = mysqli_query($conn,"SELECT * FROM player WHERE playerName = '$player'");
		if (mysqli_num_rows($rows) > 0) { 
			// Get player ID
			$row = mysqli_fetch_assoc($rows);
			mysqli_query($conn,"INSERT INTO $db_name.player_team (player_identifier, team_identifier) VALUES ('".$row['identifier']."', '".$RowMysqliInsertId."')");
			echo "old player added<br>";
		} else {
			// new player
			mysqli_query($conn,"INSERT INTO $db_name.performanceTeam (firstBlood, firstTower, firstInhibitor, firstBaron, firstDragon, firstRiftHerald, towerTakedowns, inhibitorTakedowns, baronKills, dragonKills, riftHeraldKills, team_identifier) VALUES ('".$firstBlood."', '".$firstTower."', '".$firstInhibitor."', '".$firstBaron."', '".$firstDragon."', '".$firstRiftHerald."', '".$towerKills."', '".$inhibitorKills."', '".$baronKills."', '".$dragonKills."', '".$riftHeraldKills."', '".$matchId."', '".$RowMysqliInsertId."')");
		}*/
		mysqli_query($conn,"INSERT INTO $db_name.performancePlayer (kills, deaths, assist, player_identifier, tournamentMatch_identifier) VALUES ('".$Kills."', '".$Deaths."', '".$Assists."', '".$playerSQLID."', '".$matchSQLID."')");
		
		mysqli_close($conn);
	}
	
	function dbconnect() {
		define('DB_HOST', getenv('OPENSHIFT_MYSQL_DB_HOST'));
		define('DB_PORT', getenv('OPENSHIFT_MYSQL_DB_PORT'));
		define('DB_USER', getenv('OPENSHIFT_MYSQL_DB_USERNAME'));
		define('DB_PASS', getenv('OPENSHIFT_MYSQL_DB_PASSWORD'));
		define('DB_NAME', getenv('OPENSHIFT_GEAR_NAME'));
		
		$dbhost = constant("DB_HOST"); // Host name 
		$dbport = constant("DB_PORT"); // Host port
		$dbusername = constant("DB_USER"); // Mysql username 
		$dbpassword = constant("DB_PASS"); // Mysql password 
		$db_name = constant("DB_NAME"); // Database name 
			
			$mysqlCon = mysqli_connect($dbhost, $dbusername, $dbpassword, "", $dbport) or die("Error: " . mysqli_error($mysqlCon));
			mysqli_select_db($mysqlCon, $db_name) or die("Error: " . mysqli_error($mysqlCon));
			 
			return $mysqlCon;
	}
?>