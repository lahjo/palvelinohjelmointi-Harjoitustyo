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
$result = mysqli_query($conn,"select * from team inner join performanceTeam on performanceTeam.team_identifier = team.identifier and performanceTeam.tournamentMatch_identifier in (select identifier from tournamentMatch where matchHash = '$id')order by performanceTeam.identifier");
$roundIndex = 0;
$i = 0;
while($row = mysqli_fetch_assoc($result)) {
	if($i == 0) {
	echo "<div class='GameDatailsContainer'>";
	echo "<div class='TeamBlue'>";
		echo "<div class='TeamStats'>$row[teamName]</div>";
		echo "<div class='TeamStats'>";
		if($row[firstBlood] == 1) {
			echo '<img src="image/firstcheck.png" >';
		}  
		echo "</div>";
		
		echo "<div class='TeamStats'>";
		if($row[firstTower] == 1) {
			echo '<img src="image/firstcheck.png" >';
		}  
		echo "</div>";
		
		echo "<div class='TeamStats'>";
		if($row[firstInhibitor] == 1) {
			echo '<img src="image/firstcheck.png" >';
		}  
		echo "</div>";
		
		echo "<div class='TeamStats'>";
		if($row[firstBaron] == 1) {
			echo '<img src="image/firstcheck.png" >';
		}  
		echo "</div>";
		
		echo "<div class='TeamStats'>";
		if($row[firstDragon] == 1) {
			echo '<img src="image/firstcheck.png" >';
		}  
		echo "</div>";
		
		echo "<div class='TeamStats'>";
		if($row[firstRiftHerald] == 1) {
			echo '<img src="image/firstcheck.png" >';
		}  
		echo "</div>";
		
		echo "<div class='TeamStats'>$row[towerTakedowns]</div>";
		echo "<div class='TeamStats'>$row[inhibitorTakedowns]</div>";
		echo "<div class='TeamStats'>$row[baronKills]</div>";
		echo "<div class='TeamStats'>$row[dragonKills]</div>";
		echo "<div class='TeamStats'>$row[riftHeraldKills]</div>";
		echo "<div class='TeamStats'>$row[winOrLose]</div>";
	echo "</div>";
	
	echo "<div class='GameInfo'>";
		echo "<div class='info'>VS</div>";
		echo "<div class='info'>First Blood</div>";
		echo "<div class='info'>First Tower</div>";
		echo "<div class='info'>First Inhibitor</div>";
		echo "<div class='info'>First Baron</div>";
		echo "<div class='info'>First Dragon</div>";
		echo "<div class='info'>First Rift herald</div>";
		echo "<div class='info'>Towers</div>";
		echo "<div class='info'>Inhibitors</div>";
		echo "<div class='info'>Barons</div>";
		echo "<div class='info'>Dragons</div>";
		echo "<div class='info'>Rift heralds</div>";
	echo "</div>";
	$i = 1;
	}else if($i == 1) {
		echo "<div class='TeamRed'>";
				echo "<div class='TeamStats'>$row[teamName]</div>";
		echo "<div class='TeamStats'>";
		if($row[firstBlood] == 1) {
			echo '<img src="image/firstcheck.png" >';
		}  
		echo "</div>";
		
		echo "<div class='TeamStats'>";
		if($row[firstTower] == 1) {
			echo '<img src="image/firstcheck.png" >';
		}  
		echo "</div>";
		
		echo "<div class='TeamStats'>";
		if($row[firstInhibitor] == 1) {
			echo '<img src="image/firstcheck.png" >';
		}  
		echo "</div>";
		
		echo "<div class='TeamStats'>";
		if($row[firstBaron] == 1) {
			echo '<img src="image/firstcheck.png" >';
		}  
		echo "</div>";
		
		echo "<div class='TeamStats'>";
		if($row[firstDragon] == 1) {
			echo '<img src="image/firstcheck.png" >';
		}  
		echo "</div>";
		
		echo "<div class='TeamStats'>";
		if($row[firstRiftHerald] == 1) {
			echo '<img src="image/firstcheck.png" >';
		}  
		echo "</div>";
		
		echo "<div class='TeamStats'>$row[towerTakedowns]</div>";
		echo "<div class='TeamStats'>$row[inhibitorTakedowns]</div>";
		echo "<div class='TeamStats'>$row[baronKills]</div>";
		echo "<div class='TeamStats'>$row[dragonKills]</div>";
		echo "<div class='TeamStats'>$row[riftHeraldKills]</div>";
		echo "<div class='TeamStats'>$row[winOrLose]</div>";
	echo "</div>";
	
	
	/*  Teams Stats  */
	$result3 = mysqli_query($conn,"select * from player inner join performancePlayer on performancePlayer.player_identifier = player.identifier and performancePlayer.tournamentMatch_identifier in (select identifier from tournamentMatch where matchHash = '$id') order by performancePlayer.identifier");
		$teamIndex = 0;
		$matchIndex = 0;
		$j = 0;
		while($playerData = mysqli_fetch_assoc($result3)) {
		if($roundIndex == $matchIndex) {
			$matchIndex = 0;
			if($teamIndex == 0) {
				if($j == 0) {
					echo "<div class='TeamBlueRosterStatic'>
						  <div class='playerStatic'>
							<div class='playerInfo'>Player</div>
							<div class='playerInfo'>Kills</div>
							<div class='playerInfo'>Deaths</div>
							<div class='playerInfo'>Assists</div>
						  </div>";
						
						echo "<div class='playerStatic'>
							<div class='playerInfo'>" .$playerData['playerName'] . "</div>
							<div class='playerInfo'>" .$playerData['kills'] . "</div>
							<div class='playerInfo'>" .$playerData['deaths'] . "</div>
							<div class='playerInfo'>" .$playerData['assist'] . "</div>
						  </div>";
						
						$j++;
					}else if($j == 1) {
						echo "<div class='playerStatic'>
							<div class='playerInfo'>" .$playerData['playerName'] . "</div>
							<div class='playerInfo'>" .$playerData['kills'] . "</div>
							<div class='playerInfo'>" .$playerData['deaths'] . "</div>
							<div class='playerInfo'>" .$playerData['assist'] . "</div>
						  </div>";
						
						$j++;
					}else if($j == 2) {
						echo "<div class='playerStatic'>
							<div class='playerInfo'>" .$playerData['playerName'] . "</div>
							<div class='playerInfo'>" .$playerData['kills'] . "</div>
							<div class='playerInfo'>" .$playerData['deaths'] . "</div>
							<div class='playerInfo'>" .$playerData['assist'] . "</div>
						  </div>";
						
						$j++;
					}else if($j == 3) {
						echo "<div class='playerStatic'>
							<div class='playerInfo'>" .$playerData['playerName'] . "</div>
							<div class='playerInfo'>" .$playerData['kills'] . "</div>
							<div class='playerInfo'>" .$playerData['deaths'] . "</div>
							<div class='playerInfo'>" .$playerData['assist'] . "</div>
						  </div>";
						
						$j++;
					}else if($j == 4) {
						echo "<div class='playerStatic'>
							<div class='playerInfo'>" .$playerData['playerName'] . "</div>
							<div class='playerInfo'>" .$playerData['kills'] . "</div>
							<div class='playerInfo'>" .$playerData['deaths'] . "</div>
							<div class='playerInfo'>" .$playerData['assist'] . "</div>
						  </div>
						  </div>";
							
						$j = 0;
						$teamIndex = 1;
				}
					
			}else if($teamIndex == 1) {
				if($j == 0) {
					echo "<div class='TeamRedRosterStatic'>
						  <div class='playerStatic'>
							<div class='playerInfo'>Player</div>
							<div class='playerInfo'>Kills</div>
							<div class='playerInfo'>Deaths</div>
							<div class='playerInfo'>Assists</div>
						  </div>";
						
						echo "<div class='playerStatic'>
							<div class='playerInfo'>" .$playerData['playerName'] . "</div>
							<div class='playerInfo'>" .$playerData['kills'] . "</div>
							<div class='playerInfo'>" .$playerData['deaths'] . "</div>
							<div class='playerInfo'>" .$playerData['assist'] . "</div>
						  </div>";
						
						$j++;
					}else if($j == 1) {
						echo "<div class='playerStatic'>
							<div class='playerInfo'>" .$playerData['playerName'] . "</div>
							<div class='playerInfo'>" .$playerData['kills'] . "</div>
							<div class='playerInfo'>" .$playerData['deaths'] . "</div>
							<div class='playerInfo'>" .$playerData['assist'] . "</div>
						  </div>";
						
						$j++;
					}else if($j == 2) {
						echo "<div class='playerStatic'>
							<div class='playerInfo'>" .$playerData['playerName'] . "</div>
							<div class='playerInfo'>" .$playerData['kills'] . "</div>
							<div class='playerInfo'>" .$playerData['deaths'] . "</div>
							<div class='playerInfo'>" .$playerData['assist'] . "</div>
						  </div>";
						
						$j++;
					}else if($j == 3) {
						echo "<div class='playerStatic'>
							<div class='playerInfo'>" .$playerData['playerName'] . "</div>
							<div class='playerInfo'>" .$playerData['kills'] . "</div>
							<div class='playerInfo'>" .$playerData['deaths'] . "</div>
							<div class='playerInfo'>" .$playerData['assist'] . "</div>
						  </div>";
						
						$j++;
					}else if($j == 4) {
						echo "<div class='playerStatic'>
							<div class='playerInfo'>" .$playerData['playerName'] . "</div>
							<div class='playerInfo'>" .$playerData['kills'] . "</div>
							<div class='playerInfo'>" .$playerData['deaths'] . "</div>
							<div class='playerInfo'>" .$playerData['assist'] . "</div>
						  </div>
						  </div>";
						
						$j = 0;
						$teamIndex = 0;
						$roundIndex++;
						break;
				}
			}
		  }
		  if($roundIndex == 0) {
			  
		  }else {
			$matchIndex++;
		  }
		}
	echo "</div>";
	
	$i = 0;
	}
}
mysqli_free_result($result);

mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
<title>Page Title</title>

<style>
body {
	background-image: url("image/bg2.png");
	background-repeat:no-repeat;
    background-size: 100% 100%;
}

.GameDatailsContainer {
	margin-right: auto;
	margin-left: auto;
	width: 1000px;
	height: 950px;
	margin-top: 10px;
	background-color: #d2d2d2;
}

.TeamBlue {
	float: left;
	width: 350px;
	height: 700px;
}

.TeamRed {
	float: Right;
	width: 350px;
	height: 700px;
}

.GameInfo {
	float: left;
	width: 300px;
	height: 700px;
}

.TeamStats {
	height: 40px;
	widht: 100px;
	margin-left: auto;
	margin-right: auto;
	margin-top: 10px;
	text-align: center;
}
.info {
	height: 40px;
	widht: 100px;
	margin-left: auto;
	margin-right: auto;
	margin-top: 10px;
	text-align: center;
}

.playerInfo {
	float: left;
	width: 100px;
}

.playerStatic {
	float: left;
	width: 100%;
}

.TeamBlueRosterStatic {
	width: 400px;
	float: left;
	margin-left: 30px;
}

.TeamRedRosterStatic {
	width: 400px;
	float: Right;
}
</style>

</head>
<body>
</body>
</html> 