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
.topPlayers {
	margin-top: 50px;
	width: 920px;
	height: 500px;
	margin-left: auto;
	margin-right: auto;
}

.upcoming {
	width: 920px;
	height: 150px;
	margin-top: 100px;
	margin-left: auto;
	margin-right: auto;
}

.match {
	width: 200px;
	height: 150px;
	margin-left: 20px;
	background-color: #4CAF50;
	float: left;
}

.match p {
	text-align: center;
	margin-top: 40px;
}

.match h3 {
	text-align: center;
}
</style>

 <?php
			$conn = dbconnect();
		

			$gamecount = mysqli_query($conn,"SELECT COUNT(IF(state = 'resolved',1,NULL)) AS result FROM tournamentMatch JOIN tournament_tournamentMatch JOIN tournament ON tournamentMatch.identifier = tournament_tournamentMatch.tournamentMatch_identifier AND tournament.identifier = tournament_tournamentMatch.tournament_identifier WHERE tournament_identifier = 7");
			$gameResult = mysqli_fetch_row($gamecount);
			
			$games = $gameResult[0];
			
			mysqli_free_result($gamecount);
			
			$gamecount = mysqli_query($conn,"SELECT COUNT(IF(state = 'unresolved',1,NULL)) AS result FROM tournamentMatch JOIN tournament_tournamentMatch JOIN tournament ON tournamentMatch.identifier = tournament_tournamentMatch.tournamentMatch_identifier AND tournament.identifier = tournament_tournamentMatch.tournament_identifier WHERE tournament_identifier = 7");
			$gameResult = mysqli_fetch_row($gamecount);
			
			$played = $gameResult[0];
			
			mysqli_free_result($gamecount);
		
?>

	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawVisualization);
function drawVisualization() {
  // Create and populate the data table.
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['played', <?php echo $games; ?>],
          ['upcoming', <?php echo $played; ?>]
        ]);
		
		var options = {
          title: 'na 2016 spring'
        };

  // Create and draw the visualization.
  new google.visualization.PieChart(document.getElementById('chart_div')).
      draw(data, options);
}
</script>

<?php
			$conn = dbconnect();
		
			$gamecount = mysqli_query($conn,"SELECT COUNT(IF(state = 'resolved',1,NULL)) AS result FROM tournamentMatch JOIN tournament_tournamentMatch JOIN tournament ON tournamentMatch.identifier = tournament_tournamentMatch.tournamentMatch_identifier AND tournament.identifier = tournament_tournamentMatch.tournament_identifier WHERE tournament_identifier = 9");
			$gameResult = mysqli_fetch_row($gamecount);
			
			$games = $gameResult[0];
			
			mysqli_free_result($gamecount);
			
			$gamecount = mysqli_query($conn,"SELECT COUNT(IF(state = 'unresolved',1,NULL)) AS result FROM tournamentMatch JOIN tournament_tournamentMatch JOIN tournament ON tournamentMatch.identifier = tournament_tournamentMatch.tournamentMatch_identifier AND tournament.identifier = tournament_tournamentMatch.tournament_identifier WHERE tournament_identifier = 9");
			$gameResult = mysqli_fetch_row($gamecount);
			
			$played = $gameResult[0];
			
			mysqli_free_result($gamecount);
		
?>

	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawVisualization);
function drawVisualization() {
  // Create and populate the data table.
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['played', <?php echo $games; ?>],
          ['upcoming', <?php echo $played; ?>]
        ]);
		
		var options = {
          title: 'lpl 2016 spring'
        };

  // Create and draw the visualization.
  new google.visualization.PieChart(document.getElementById('chart_div2')).
      draw(data, options);
}
</script>

</head>
<body>

<?php
$conn = dbconnect();
echo "<div class='upcoming'>";
$id = mysqli_query($conn,"SELECT identifier, tournamentTitle FROM tournament");
$matchs = array();
$index = 0;
while($row = mysqli_fetch_assoc($id)) {
	if($index < 4) {
	$tournamentID = $row['identifier'];
	$upcomingMatch = mysqli_query($conn,"select * from tournamentMatch inner join tournament_tournamentMatch on tournament_tournamentMatch.tournamentMatch_identifier = tournamentMatch.identifier where tournament_tournamentMatch.tournament_identifier = $tournamentID AND tournamentMatch.state = 'unresolved' order by tournamentMatch.date");
	if (mysqli_num_rows($upcomingMatch) > 0) {
		$upComingMatchInfo = mysqli_fetch_row($upcomingMatch);
		echo "<div class='match'>". "<p>" .$upComingMatchInfo[1] . "</p> <h3>" . $row['tournamentTitle'] . "</h3>" . "</div>";
		$index++;
	}
 }
}

for($leftIndex = $index; $leftIndex < 4; $leftIndex++) {
	echo "<div class='match'></div>";
} 
echo "</div>";

echo "<div class='topPlayers'>";


echo "<div class='grid-container'> 
		<div class='grid-100 grid-parent'>
			<div id='chart_div' style='width: 100%; height: auto'></div>
		</div>   
	</div>";

echo "<div class='grid-container'> 
		<div class='grid-100 grid-parent'>
			<div id='chart_div2' style='width: 100%; height: auto'></div>
		</div>   
	</div>";

echo "</div>";

?>
</body>
</html> 