<html>
	<head>
		<script src="js/jquery.js"></script>
		<script src="js/list.min.js"></script>
		<meta charset=utf-8 />
	</head>

	<body>
		<div id='sortTable'>
<?php
	echo '<form method="post" action="mssql3.php"><input name="runQuery" style="width:600px;" type="text" autofocus><input type="submit" value="Run Query"></form>';

	if(!empty($_POST)){
		$swinds_server = "ord-swdb101.corp.valueclick.com";
		$swinds_server_ip = 10.110.100.66;
		$swinds_server_port = 1433;
		$swinds_db = "SolarWindsOrion";
		$swinds_user = "SolarWindsOrionDatabaseUser";
		$swinds_pass = "+tDJV%EwQs-vC5*(";
		
		$conn = mssql_connect($swinds_server,$swinds_user,$swinds_pass) or die ("Connection Error"); 
		$db = mssql_select_db($swinds_db,$conn) or die("Database Error");
		
		//$query = "SELECT * FROM APM_HardwareCategoryStatusReportData WHERE StatusID!='1'";
		//$query = "SELECT * FROM APM_ApplicationSetting";
		$query = $_POST['runQuery'];
		if(substr($query, -1)!=';'){
			$query.=';';
		}
		$queryPieces = explode(" ",$query);
		for($i=0;$i<sizeof($queryPieces);$i++){
			if(strcasecmp($queryPieces[$i], "from") == 0){
				$table = $queryPieces[$i+1];
			}
		}

		echo '>>> Connection opened:  '.$swinds_user.'@'.$swinds_server.' ('.$swinds_server_ip.':'.$swinds_server_port.')';
		echo '<br><br>Table: '.$swinds_db.'.'.$table;
		echo '<br>Last Query Ran: '.$query;
		echo '<br>Query Sent From: '.$_SERVER['REMOTE_ADDR'];
		echo '<br>Last Message: '.mssql_get_last_message();
		echo '<br><br>Results:<br><br>';

		$rowCounter=0;
		$colCounter=0;
		$col_nodeID=99999;
		$queryTimeStart = microtime(true);
		$result = mssql_query($query);//echo var_export(mssql_num_rows($result));exit();
		$resultTmp = mssql_query($query);//echo var_export(mssql_num_rows($result));exit();
		//echo '<br><br>num rows: '.intval(mssql_num_rows($result)-1).'<br><br>';
		
		if (!mssql_num_rows($result)) {
			echo 'No records found';
		} else {
			echo '<table border="1"><thead><tr><th>ROW</th>';
			$colNames=array_keys(mssql_fetch_array($resultTmp));
			for($i=0;$i<sizeof($colNames);$i+=2){
				echo '<th class="sort">'.$colNames[$i+1].'</th>';
				if(strtolower($colNames[$i+1])=='nodeid'){$col_nodeID=$colCounter;}
				$colCounter++;
			}
			echo '</tr></thead><tbody id="sortBody">';
		
			$sortDataPoints=array();
			while ( $row = mssql_fetch_row($result) ){
				$rowCounter++;
				echo '<tr><td>'.$rowCounter.'</td>';
				for($ii=0;$ii<$colCounter;$ii++){
					echo '<td class="sortDataPoint_'.$ii.'">';
					if( $ii==$col_nodeID ){
						echo '<a href="http://ord-mon105.corp.valueclick.com/Orion/NetPerfMon/NodeDetails.aspx?NetObject=N:'.$row[$ii].'" target="_blank">'.$row[$ii].'</a>';
					}else{
						if($row[$ii]==""){
							echo '&nbsp;';
						}else{
							echo $row[$ii];
						}
					}
					echo '</td>';
					$sortDataPoints[$ii]="sortDataPoint_".$ii;
				}
				echo '</tr>';
			}
			echo '<tr align="center"><td><b>ROW</b></td>';
			for($i=0;$i<sizeof($colNames);$i+=2){
				echo '<td><b>'.$colNames[$i+1].'</b></td>';
			}
			echo '</tr></tbody></table>';
		}
		$queryTimeEnd = microtime(true);
		$queryProcessTime = $queryTimeEnd-$queryTimeStart;
		echo '<br>Rows Returned: '.$rowCounter.' rows.<br>Query Process Time: '.round($queryProcessTime,3).' seconds.';
		mssql_close($conn);
		echo '<br><br>>>> Connection closed:  '.$swinds_user.'@'.$swinds_server.' ('.$swinds_server_ip.':'.$swinds_server_port.')';
	}
?>
		</div>

		<script>
			$(document).ready(function(){
				//var sortOptions = {valueNames: [ 'sort-nodeid', 'sort-nodename', 'sort-interfaceid', 'sort-interfacename', 'sort-status', 'sort-caption' ],page:10000};
				});

				//var sortOptions = {valueNames: sortDataPoints,page:10000};
				//var sortList = new List('sortTable', sortOptions);
			});
		</script>
	</body>
</html>
