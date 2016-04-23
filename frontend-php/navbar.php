<?php
	include('session.php');
?>

<head>
<style>
.dropbtn {
    background-color: #4CAF50;
    color: white;
    padding: 16px;
    font-size: 16px;
    border: none;
    cursor: pointer;
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
}

.dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.dropdown-content a:hover {background-color: #f1f1f1}

.dropdown:hover .dropdown-content {
    display: block;
}

.dropdown:hover .dropbtn {
    background-color: #3e8e41;
}

.user {
	float: right;
	margin-right: 20px;
	height: 55px;
	background-color: #4CAF50;
}

.user h3 {
	width: 180px;
	float: left;
	margin: 0px;
	color: #FFF;
	margin-left: 20px;
	margin-top: 10px;
}

.user p {
	float: left;
	margin: 0px;
	color: #FFF;
	margin-right: 20px;
	margin-top: 10px;
}

</style>
</head>

<div class="navbar">
	<div class="dropdown">
		<a href="frontPage.php"><button class="dropbtn">Home</button></a>
	</div>

	<div class="dropdown">
		<button class="dropbtn">EU LCS</button>
		<div class="dropdown-content">
		  
		<?php 
		$conn = dbconnect();
		$eu = mysqli_query($conn,"SELECT * FROM tournament WHERE tournamentTitle LIKE '%eu%'");

		while($row = mysqli_fetch_assoc($eu)) {
				echo '<a href="tournament.php?id=' . $row["identifier"] . '" >' . $row["tournamentTitle"] . '</a>';
		}

		mysqli_free_result($result);

		mysqli_close($conn);
		?>

		</div>
	</div>

	<div class="dropdown">
		<button class="dropbtn">NA LCS</button>
		<div class="dropdown-content">
		  
		<?php 
		$conn = dbconnect();
		$eu = mysqli_query($conn,"SELECT * FROM tournament WHERE tournamentTitle LIKE 'na%'");

		while($row = mysqli_fetch_assoc($eu)) {
				echo '<a href="tournament.php?id=' . $row["identifier"] . '" >' . $row["tournamentTitle"] . '</a>';
		}

		mysqli_free_result($result);

		mysqli_close($conn);
		?>

		</div>
	</div>

	<div class="dropdown">
		<button class="dropbtn">LCK Korea</button>
		<div class="dropdown-content">
		  
		<?php 
		$conn = dbconnect();
		$eu = mysqli_query($conn,"SELECT * FROM tournament WHERE tournamentTitle LIKE '%lck%'");

		while($row = mysqli_fetch_assoc($eu)) {
				echo '<a href="tournament.php?id=' . $row["identifier"] . '" >' . $row["tournamentTitle"] . '</a>';
		}

		mysqli_free_result($result);

		mysqli_close($conn);
		?>

		</div>
	</div>

	<div class="dropdown">
		<button class="dropbtn">LPL China</button>
		<div class="dropdown-content">
		  
		<?php 
		$conn = dbconnect();
		$eu = mysqli_query($conn,"SELECT * FROM tournament WHERE tournamentTitle LIKE '%lpl%'");

		while($row = mysqli_fetch_assoc($eu)) {
				echo '<a href="tournament.php?id=' . $row["identifier"] . '" >' . $row["tournamentTitle"] . '</a>';
		}

		mysqli_free_result($result);

		mysqli_close($conn);
		?>

		</div>
	</div>
	
<div class="user">	
	<h3>Welcome <?php echo $login_session; ?></h3> 
	  <p><a href = "logout.php">Sign Out</a></p>
 </div>
</div>