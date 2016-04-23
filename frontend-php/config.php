<?php
		define('DB_HOST', getenv('OPENSHIFT_MYSQL_DB_HOST'));
		define('DB_PORT', getenv('OPENSHIFT_MYSQL_DB_PORT'));
		define('DB_USER', getenv('OPENSHIFT_MYSQL_DB_USERNAME'));
		define('DB_PASS', getenv('OPENSHIFT_MYSQL_DB_PASSWORD'));

		$db_name = "logIn"; // Database name 
			
		$mysqlCon = mysqli_connect(DB_HOST, DB_USER, DB_PASS, $db_name, DB_PORT) or die(mysqli_error($mysqlCon));

			 
?>