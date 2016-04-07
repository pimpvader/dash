<?php
	$swinds_server = "ord-swdb101.corp.valueclick.com";
	$swinds_server_ip = 10.110.100.66;
	$swinds_server_port = 1433;
	$swinds_db = "SolarWindsOrion";
	$swinds_user = "SolarWindsOrionDatabaseUser";
	$swinds_pass = "+tDJV%EwQs-vC5*(";
	
	$conn = mssql_connect($swinds_server,$swinds_user,$swinds_pass) or die ("Connection Error"); 
	$db = mssql_select_db($swinds_db,$conn) or die("Database Error");
	$query = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = \"BASE TABLE\" ORDER BY TABLE_NAME";
	$queryTimeStart = microtime(true);
	$result = mssql_query($query);

	echo '>>> Connection opened:  '.$swinds_user.'@'.$swinds_server.' ('.$swinds_server_ip.':'.$swinds_server_port.')';
	echo '<br><br>Table: '.$swinds_db.'.'.$table;
	echo '<br>Query: '.$query;
	echo '<br>Query Entered By: '.$_SERVER['REMOTE_ADDR'];
	echo '<br>Last Message: '.mssql_get_last_message();
	echo '<br><br>Results:<br><br>';

	$rowCounter=0;
	if (!mssql_num_rows($result)) {
		echo 'No records found';
	} else {
		while ($row = mssql_fetch_array($result)) {
			$rowCounter++;
			echo $row[0].'.'.$row[1].'.'.$row[2].'<br>', PHP_EOL;
		}
	}
	$queryTimeEnd = microtime(true);
        $queryProcessTime = $queryTimeEnd-$queryTimeStart;
	echo '<br><br>Rows Returned: '.$rowCounter.' rows.<br>Query Process Time: '.round($queryProcessTime,3).' seconds.';
	
	mssql_close($conn);
	echo '<br><br>>>> Connection closed:  '.$swinds_user.'@'.$swinds_server.' ('.$swinds_server_ip.':'.$swinds_server_port.')';
?>
