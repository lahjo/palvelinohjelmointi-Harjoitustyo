<?php
include('session.php');
include ('navbar.php');

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
$id = $_GET['id'];
$conn = dbconnect();
$result = mysqli_query($conn,"SELECT * FROM tournamentMatch INNER JOIN tournament_tournamentMatch ON tournament_tournamentMatch.tournamentMatch_identifier = tournamentMatch.identifier WHERE tournament_tournamentMatch.tournament_identifier =  '$id' AND round = 'G1' ORDER BY tournamentMatch.date DESC");
if (mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
	$matchHash = $row["matchHash"];
	$result2 = mysqli_query($conn,"SELECT * FROM team INNER JOIN team_tournamentMatch ON team.identifier = team_identifier INNER JOIN tournamentMatch ON team_tournamentMatch.tournamentMatch_identifier = tournamentMatch.identifier WHERE tournamentMatch.matchHash in ('$matchHash') AND round = 'G1';");
	
	$i = 0;
	while($row2 = mysqli_fetch_assoc($result2)) {
		if($i == 0) {
			$teamId = $row2["team_identifier"];
			$matchHash = $row2["matchHash"];
			$winOrLoseCount = mysqli_query($conn,"select COUNT(IF(winOrLose = 'Win',1,NULL)) AS result FROM performanceTeam inner join team on performanceTeam.team_identifier = team.identifier and performanceTeam.tournamentMatch_identifier in (select identifier from tournamentMatch where matchHash = '$matchHash') AND team.identifier =  '$teamId'");
			$gameResult = mysqli_fetch_row($winOrLoseCount);
			
			echo "<div class='matchContainer'>";
			echo '<a href="matchDetail.php?id=' . $row2["matchHash"] . '" >';
			echo "<div class='matchTeamBlue'>" . $row2["teamName"] . "</div>";
			echo "<div class='matchResultDateContainer'>";
			echo "<div class='matchInfoBlue'>" . $gameResult['0'] . ":" . "</div>";
			$i = 1;
			
			mysqli_free_result($winOrLoseCount);
		}else if($i == 1) {
			$teamId = $row2["team_identifier"];
			$matchHash = $row2["matchHash"];
			$winOrLoseCount = mysqli_query($conn,"select COUNT(IF(winOrLose = 'Win',1,NULL)) AS result FROM performanceTeam inner join team on performanceTeam.team_identifier = team.identifier and performanceTeam.tournamentMatch_identifier in (select identifier from tournamentMatch where matchHash = '$matchHash') AND team.identifier =  '$teamId'");
			$gameResult = mysqli_fetch_row($winOrLoseCount);
			
			echo "<div class='matchInfoRed'>" . $gameResult['0'] . "</div>";
			echo "<div class='matchDate'>" . $row2["date"] . "</div>";
			echo "</div>";
			echo "<div class='matchTeamRed'>" . $row2["teamName"] . "</div>";
			echo "</a>";
			echo "</div>";
			
			$i = 0;
		}
	}
	
	mysqli_free_result($result2);

}

mysqli_free_result($result);
}

else {
	$upcomingMatch = mysqli_query($conn,"select * from tournamentMatch inner join tournament_tournamentMatch on tournament_tournamentMatch.tournamentMatch_identifier = tournamentMatch.identifier where tournament_tournamentMatch.tournament_identifier = $id AND tournamentMatch.state = 'unresolved' order by tournamentMatch.date");
		if (mysqli_num_rows($upcomingMatch) > 0) {
			while($upcomingMatchRow = mysqli_fetch_assoc($upcomingMatch)) {
			echo "<div class='matchContainer'>";
			echo "<div class='matchTeamBlue'></div>";
			echo "<div class='matchResultDateContainer'>";
			echo "<div class='matchInfoBlue'>upco</div>";	
			echo "<div class='matchInfoRed'>ming</div>";
			echo "<div class='matchDate'>" . $upcomingMatchRow["date"] . "</div>";
			echo "</div>";
			echo "<div class='matchTeamRed'></div>";
			echo "</div>";
		}
	} else {
		echo "<div class='matchContainer'>";
			echo "<div class='matchTeamBlue'></div>";
			echo "<div class='matchResultDateContainer'>";
			echo "<div class='matchInfoBlue'></div>";	
			echo "<div class='matchInfoRed'></div>";
			echo "<div class='matchDate'>No matchs</div>";
			echo "</div>";
			echo "<div class='matchTeamRed'></div>";
			echo "</div>";
	}
}


mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
<title>Page Title</title>

<style>
body {
	background-image: url("image/bg.jpg");
	background-repeat:no-repeat;
    background-size:cover;
}

.matchContainer a {
	color: black;
}

.matchContainer {
	margin-right: auto;
	margin-left: auto;
	width: 500px;
	height: 80px;
	border: 1px solid black;
	margin-top: 10px;
	background-color: #FFF;
}

.matchTeamBlue {
	float: left;
	width: 80px;
	height: 50px;
	text-align: center;
	padding-top: 30px;
}

.matchInfoBlue {
	float: left;
	width: 165px;
	height: 50px;
	text-align: right;
}

.matchInfoRed {
	float: left;
	width: 165px;
	height: 50px;
}

.matchDate {
	float: left;
	width: 330px;
	height: 27px;
	text-align: center;
}

.matchTeamRed {
	float: Right;
	width: 80px;
	height: 50px;
	padding-top: 30px;
}

.matchResultDateContainer {
	height: 80px;
	float: left;
	width: 330px;
}
</style>

</head>
<body>
</body>
</html> 