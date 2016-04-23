<?php
include ('sql.php');

function __autoload($class_name) {
	include ($class_name . '.class.php');
}

// Torunament->Schedule->Match->Team
// REGIONS
$Tournament = new Tournament(3);
$Tournament = new Tournament(2);
$Tournament = new Tournament(6);
$Tournament = new Tournament(7);

//---------------------------------------------- SQL ----------------------------------------------//
/*define('DB_HOST', getenv('OPENSHIFT_MYSQL_DB_HOST'));
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

mysqli_query($conn,"INSERT INTO $db_name.tournament (tuornamentID,tuornamentTitle) VALUES ('".$this->getLeagueHashId()."','".$this->getLeagueTitle()."')");
$tournamentRow_id = mysql_insert_id();

mysqli_query($conn,"INSERT INTO $db_name.tournamentMatch (matchID) VALUES ($matchId->id)");
$matchRow_id = mysql_insert_id();

mysql_query("INSERT INTO $db_name.tournament_tournamentMatch (tuornament_identifier, tournamentMatch_identifier) VALUES ('".$tournamentRow_id."', '".$matchRow_id."')");
*///---------------------------------------------- /SQL ----------------------------------------------//
?>