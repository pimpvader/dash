<?PHP
	$path=$_SERVER['DOCUMENT_ROOT'];
	require_once $path.'/php/dbconn.php';
	require 'php/dbconn-swinds.php';
	include $path.'/php/common.php';
	require_once $path.'/php/api/jiraCreds.php';

	function checkJiraSvcTag($svcTag,$jiraUN,$jiraPW){
		$return = curl_request('http://jira.cnvrmedia.net/rest/api/latest/search?jql=%22Service%20Tag%22~%22'.$svcTag.'%22%20AND%20%22updated%22%20>%20startOfDay(-180)%20order%20by%20created%20desc&maxResults=200',2,5,2,$jiraUN,$jiraPW);
		
		$errorConnMessage = '{"total":1,"issues":[{"key":"FAIL","fields":{"summary":"Error connecting to JIRA."}}]}';
		if($return !== false){
			$decode = json_decode($return);
                }else{
			$decode = json_decode($errorConnMessage);
                }
                return $decode;
        }

	function searchIrisAlerts($serverHostname){
		$return = curl_request('https://iris.vclk.net/ticket/results_json?host_name='.$serverHostname.'&relative_start=previous-month&relative_end=',2,5,1);

		$errorConnMessage = '{"total":1,"issues":[{"key":"FAIL","fields":{"summary":"Error connecting to JIRA."}}]}';
		if($return !== false){
			$decode = json_decode($return);
                }else{
			$decode = json_decode($errorConnMessage);
                }
                return $decode;
        }

	function curl_request($url,$connTimeout,$curlTimeout,$numTries=1,$curlUN='',$curlPW='',$curlAuth=CURLAUTH_BASIC){
                $curl = curl_init();
                $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
                curl_setopt($curl,CURLOPT_URL,$url); //The URL to fetch. This can also be set when initializing a session with curl_init().
                curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
                curl_setopt($curl,CURLOPT_POST, FALSE);
                curl_setopt($curl,CURLOPT_HTTPAUTH, $curlAuth);
                curl_setopt($curl,CURLOPT_USERPWD, "$curlUN:$curlPW");
                curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,$connTimeout); //The number of seconds to wait while trying to connect.   
                curl_setopt($curl,CURLOPT_USERAGENT, $userAgent); //The contents of the "User-Agent: " header to be used in a HTTP request.
                curl_setopt($curl,CURLOPT_FAILONERROR, TRUE); //To fail silently if the HTTP code returned is greater than or equal to 400.
                curl_setopt($curl,CURLOPT_FOLLOWLOCATION, TRUE); //To follow any "Location: " header that the server sends as part of the HTTP header.
                curl_setopt($curl,CURLOPT_AUTOREFERER, TRUE); //To automatically set the Referer: field in requests where it follows a Location: redirect.
                curl_setopt($curl,CURLOPT_TIMEOUT,$curlTimeout); //The maximum number of seconds to allow cURL functions to execute.
                curl_setopt($curl,CURLOPT_ENCODING,  '');
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

		for($jj=1;$jj<=$numTries;$jj++){
                	$return = curl_exec($curl);
                	$result = curl_getinfo($curl);

			if($return !== false){
                        	break;
			}
		}
		
                curl_close($curl);

                return $return;
        }

?>

<!DOCTYPE HTML>
<html>
	<head>
		<?PHP getHtmlHead(); ?>
	</head>

	<body>
		<?PHP getHeader(); ?>
		<div id="pageContainer" class="container" style="min-width:615px;">
			<?PHP
				if( isset($_REQUEST['svctag']) ){
				$queryStartTime = microtime(true);
				if( isset($_REQUEST['deviceid'])){$dcim_deviceID = $_REQUEST['deviceid'];}
				if( isset($_REQUEST['svctag']) ){
					$swindsSerialNumber = trim($_REQUEST['svctag']);
					$db_connection_opendcim = new db_opendcim();
					//$result = $db_connection_opendcim -> query("SELECT label,deviceid,cabinet,position,height,serialno,templateid,primaryip,owner,notes,parentdevice FROM fac_Device WHERE serialno='".$swindsSerialNumber."'");
					$result = $db_connection_opendcim -> query("SELECT label,deviceid,cabinet,position,height,serialno,templateid,primaryip,owner,notes,parentdevice FROM fac_Device WHERE deviceid='".$dcim_deviceID."'");
					if( $swindsSerialNumber === '0000000' ){
						$serverName = 'Hostname_missing';
					}else{
						$row = $result->fetch_assoc();
						$serverNameShort = explode('.',$row['label']);
						$serverNameShort = $serverNameShort[0];
						$serverName = $row['label'];
					}
				}
				
				echo '<div class="container col-12 col-sm-12" style="text-align:center;padding-bottom:15px;">Information for <b>'.$serverName.'</b>&nbsp;[<a href="serverInfo.php?svctag='.$swindsSerialNumber.'&deviceid='.$dcim_deviceID.'">Reload Data</a>]</div>';
				echo '<div id="serverInfoLeftPane" class="container col-6 col-sm-6 col-lg-6">';

				$result = mssql_query("SELECT nodeid,model,servicetag FROM APM_HardwareInfo WHERE servicetag='".$swindsSerialNumber."'");//echo var_export(mssql_num_rows($result));exit();
				if(mssql_num_rows($result) > 0 ){
					$row = mssql_fetch_row($result);
					$swinds_nodeID = $row[0];
					$result2 = mssql_query("SELECT sysname,status,lastboot,maintenancemode FROM nodes WHERE nodeid='".$swinds_nodeID."'");//echo var_export(mssql_num_rows($result));exit();
					$row2 = mssql_fetch_row($result2);
					if($row2[3]=='1'){
						$swindsMaintMode='panel-info';
						$swindsMaintModeText='MaintenanceMode Enabled';
					}else{
						$swindsMaintMode='panel-default';
						$swindsMaintModeText='';
					}
					echo '<div id="swindsPanel" class="col-12 col-sm-12 col-lg-12 panel '.$swindsMaintMode.'" style="padding-left:0px;padding-right:0px;">';
					echo '<div class="panel-heading"><a href="http://ord-mon102.corp.valueclick.com/Orion/NetPerfMon/NodeDetails.aspx?NetObject=N:'.$swinds_nodeID.'" target="_blank" title="View in SolarWinds">SolarWinds - '.$serverName.'</a><div style="float:right;">'.$swindsMaintModeText.'</div></div>';
					echo '<div class="panel-body">';
					$serverNameShort = explode('.',$row2[0]);
					$serverNameShort = $serverNameShort[0];
					$nodeStatus = $row2[1];
					$nodeLastBoot = str_replace(":000","",$row2[2]);

					if( $nodeStatus == 0 ){ $nodeStatus = 'Unknown';$nodeStatusColor='red';}
					if( $nodeStatus == 1 ){ $nodeStatus = 'Up';$nodeStatusColor='green';}
					if( $nodeStatus == 2 ){ $nodeStatus = 'Down';$nodeStatusColor='red';}
					if( $nodeStatus == 3 ){ $nodeStatus = 'Warning';$nodeStatusColor='yellow';}
					if( $nodeStatus == 9 ){ $nodeStatus = 'Unmanaged';$nodeStatusColor='blue';}
					if( $nodeStatus == 11 ){ $nodeStatus = 'Unknown';$nodeStatusColor='red';}
				//populate solarwinds data
				$result = mssql_query("SELECT nodeid,hardwarename,hardwarestatus,sensorswithproblems,manufacturer,model,servicetag FROM APM_HardwareAlertData where nodeid=".$swinds_nodeID." and hardwarestatus!='up'"); //check for any hardware status that is not up
				$result2 = mssql_query("SELECT nodeid,hardwarename,hardwarestatus,sensorswithproblems,manufacturer,model,servicetag FROM APM_HardwareAlertData where nodeid=".$swinds_nodeID." and hardwarestatus='up'"); //check that there is at least 1 hardware up status (hardware health sensor data IS being monitored)

				echo '<table border="0" style="width:100%;"><tr><th>Node Status</th><th>Last Boot</th></tr>';
				echo '<tr><td style="color:'.$nodeStatusColor.';">'.$nodeStatus.' ';
				echo '</td><td id="nodeStatusLastBoot">'.$nodeLastBoot.'</td></tr>';
				echo '</table><br>';
				echo '<table border="0" style="width:100%;"><tr><th>Hardware Alerts</th></tr>';
				if (mssql_num_rows($result) > 0) {
					while ( $row = mssql_fetch_row($result) ){
						//echo '<li>'.$row[0].': '.$row[1].'</li>';
						if($row[6]!=''){$swindsSerialNumber = $row[6];}
						$row[3]=str_replace("\n","<br>",$row[3]);
						if( $row[2] != "Undefined" ){
							echo "<tr><td style='padding-right:20px;width:50%;'><a href='http://ord-mon102.corp.valueclick.com/Orion/NetPerfMon/NodeDetails.aspx?NetObject=N:".$swinds_nodeID."&ViewID=79' target='_blank' title='View Hardware Details'>".$row[3]."</a></td></tr>";
						}else{
							echo '<tr><td style="color:red;">Error: SolarWinds is missing hardware health sensor data.</td></tr>';
						}
					}
				}else{
					if (mssql_num_rows($result2) > 0) {
						$row2 = mssql_fetch_row($result2);
						if($row2[6]!=''){$swindsSerialNumber = $row2[6];}
						echo "<tr><td><a href='http://ord-mon102.corp.valueclick.com/Orion/NetPerfMon/NodeDetails.aspx?NetObject=N:".$swinds_nodeID."&ViewID=79' target='_blank' title='View Hardware Details'>No hardware alerts found.</a></td></tr>";
					}else{
						echo '<tr><td style="color:red;">Error: SolarWinds is missing hardware health sensor data.</td></tr>';
						if($row2[6]!=''){$swindsSerialNumber = $row2[6];}
					}
				}
				echo '</table><br>';
				$result = mssql_query("SELECT * FROM APM_ApplicationAlertsData WHERE nodeid=".$swinds_nodeID);//echo var_export(mssql_num_rows($result));exit();
				echo '<table border="0" style="width:100%;"><tr><th>Application Check</th><th>Status</th></tr>';
				if (mssql_num_rows($result) > 0) {
					while ( $row = mssql_fetch_row($result) ){
						//echo '<li>'.$row[0].': '.$row[1].'</li>';
						echo "<tr><td style='padding-right:20px;width:50%;'><a href='http://ord-mon102.corp.valueclick.com/Orion/APM/ApplicationDetails.aspx?NetObject=AA:".$row[1]."' target='_blank' title='View Application Details'>".$row[2]."</a></td>";
						if( $row[5] == 'Up'){
							echo "<td style='color:green;'>".$row[5]."</td>";
						}else if( $row[5] == 'Down' || $row[5] == 'Critical' || $row[5] == 'Unknown' ){
							echo "<td style='color:red;'>".$row[5]."</td>";
						}else if( $row[5] == 'Warning' ){
							echo "<td style='color:orange;'>".$row[5]."</td>";
						}else if( $row[5] == 'Unmanaged' ){
							echo "<td><span style='color:blue;'>".$row[5]."</td>";
						}else{
							echo "<td>".$row[5]."</td>";
						}
						echo "</tr>";
					}
				}else{
					echo '<tr><td>No application alerts found.</td><td>No application alerts found.</td></tr>';
				}
				$result2 = mssql_query("SELECT WarrantyDate FROM AssetInventory_ServerInformation WHERE NodeID=".$swinds_nodeID);
				$serialWarranty_EndDate = '';
				//echo 'debug:  '.mssql_num_rows($result2);
				if (mssql_num_rows($result2) > 0) {
					while ( $row2 = mssql_fetch_row($result2) ){
						$serialWarranty_EndDate = $row2[0];
					}
				}
				echo '</table>';
				$serverInSolarwinds = true;
			}else{
				echo '<div id="swindsPanel" class="col-12 col-sm-12 col-lg-12 panel panel-danger" style="padding-left:0px;padding-right:0px;">';
				echo '<div class="panel-heading"><a href="http://ord-mon102.corp.valueclick.com/" target="_blank" title="View in SolarWinds">SolarWinds</a></div>';
				echo '<div class="panel-body">';
				echo '<div style="color:red;">Error: Cannot locate serial number in SolarWinds.</div>';
				$serverInSolarwinds = false;
			}
			$queryEndTime = microtime(true);
			$queryProcessTime = $queryEndTime-$queryStartTime;
			echo '<span style="font-size:10px;"><br>Process time: '.round($queryProcessTime,4).' seconds</span>';
			echo '</div></div>';
			
			if( stripos($serverName,'dcs')!==FALSE ){$appStatsUrl = 'http://'.$serverName.':6060/statistics_view.do';}
			if( stripos($serverName,'dma')!==FALSE ){$appStatsUrl = 'http://'.$serverName.':7070/statistics_view.do';}
			if( stripos($serverName,'rtb')!==FALSE ){$appStatsUrl = 'http://'.$serverName.':8081/statistics_view.do';}
			if( stripos($serverName,'nsy')!==FALSE ){$appStatsUrl = 'http://'.$serverName.':7089/statistics_view.do';}

			if( isset($appStatsUrl) ){
				echo '<div id="appStatsPanel" class="col-12 col-sm-12 col-lg-12 panel panel-default" style="padding-left:0px;padding-right:0px;">';
				$queryStartTime = microtime(true);
				echo '<div class="panel-heading"><a href="'.$appStatsUrl.'" target="_blank">Raw App Stats</a></div>';
				echo '<div class="panel-body">';
				$rawFeed = curl_request($appStatsUrl,2,5);
				if( !empty($rawFeed) ){
					$new_array = json_decode(json_encode((array) simplexml_load_string($rawFeed)), 1);
					echo 'Status: '.$new_array['@attributes']['STATE'].'<br>';
					echo 'Since: <span id="appStatsStartTime">'.$new_array['@attributes']['START'].'</span><br>';
					if( stripos($new_array['@attributes']['Build-Label'],'SNAPSHOT')!==FALSE ){
						echo '<span style="background-color:yellow;">';
						echo 'Build: '.$new_array['@attributes']['Build-Label'];
						echo '</span>';
					}else{
						echo 'Build: '.$new_array['@attributes']['Build-Label'];
					}
				}else{
					echo 'App stats could not be retrieved.';
				}
				$queryEndTime = microtime(true);
				$queryProcessTime = $queryEndTime-$queryStartTime;
				echo '<table><tr><td colspan=2 style="font-size:10px;"><br>Process time: '.round($queryProcessTime,4).' seconds</td><tr></table>';
				echo '</div></div>';
			}

			echo '<div id="irisAlertsPanel" class="col-12 col-sm-12 col-lg-12 panel panel-default" style="padding-left:0px;padding-right:0px;">';
			$queryStartTime = microtime(true);
			echo '<div class="panel-heading"><a href="https://iris.vclk.net/ticket/results?host_name='.$serverName.'&relative_start=previous-month&relative_end=" target="_blank">Iris Alerts - Since Last Month</a></div>';
			echo '<div class="panel-body">';
			$irisAlertsResult = searchIrisAlerts($serverName);
			$irisAlertsCount = count($irisAlertsResult->data);
			if( $irisAlertsCount !== 0 ){
				echo '<table style="width:100%;" border="0"><tr><th>Ticket #</th><th>Status</th><th>Error</th><th>Owner</th><th>Last Updated</th></tr>';
                        	foreach($irisAlertsResult->data as $irisAlert){
					echo '<tr';
					if ($irisAlert->ticket_status!=='CLOSED'){
						echo ' style="color:red;"';
					}
					echo '><td style="min-width:90px;"><a href="https://iris.vclk.net/ticket/number/'.$irisAlert->ticket_number.'" target="_blank">'.$irisAlert->ticket_number.'</a></td><td style="min-width:80px;">'.$irisAlert->ticket_status.'</td><td style="padding-right:15px;">'.$irisAlert->last_error.'</td><td style="min-width:100px;">'.$irisAlert->owner_name.'</td><td style="min-width:100px;">'.$irisAlert->update_date.'</td></tr>';
                        	}
				echo '</table>';
			}else{
				echo 'No Iris alerts since <span id="irisAlertsStartDate"></span> for '.$serverName.'.';
			}
			$queryEndTime = microtime(true);
			$queryProcessTime = $queryEndTime-$queryStartTime;
			echo '<table><tr><td colspan=2 style="font-size:10px;"><br>Process time: '.round($queryProcessTime,4).' seconds</td><tr></table>';
			echo '</div></div>';

			echo '<div id="nagiosPanel" class="col-12 col-sm-12 col-lg-12 panel panel-default" style="padding-left:0px;padding-right:0px;">';
			$queryStartTime = microtime(true);
			echo '<div class="panel-heading">Nagios</div>';
			echo '<div class="panel-body">';
			echo '[<a href="http://monitor.vclk.net/thruk/cgi-bin/status.cgi?hidesearch=2&s0_op=~&s0_type=search&s0_value='.$serverName.'" target="_blank">Open Nagios status page</a>]';
			$queryEndTime = microtime(true);
			$queryProcessTime = $queryEndTime-$queryStartTime;
			echo '<table><tr><td colspan=2 style="font-size:10px;"><br>Process time: '.round($queryProcessTime,4).' seconds</td><tr></table>';
			echo '</div></div>';

			echo '<div id="gangliaPanel" class="col-12 col-sm-12 col-lg-12 panel panel-default" style="padding-left:0px;padding-right:0px;">';
				$queryStartTime = microtime(true);
				if( stripos($serverName,'iad')!==FALSE ){
					$gangliaUrl='http://dtiad00gng01p.dc.dotomi.net/';
				}else if( stripos($serverName,'sjc')!==FALSE ){
					$gangliaUrl='http://dtsjc00gng01p.dc.dotomi.net/';
				}else if( stripos($serverName,'ord')!==FALSE ){
					$gangliaUrl='http://trend.dc.dotomi.net/gweb/';
				}else if( stripos($serverName,'ams')!==FALSE ){
					$gangliaUrl='http://dtams00gng01p.dc.dotomi.net/';
				}else{
				
				}

			echo '
					<div class="panel-heading"><a href="'.$gangliaUrl.'" target="_blank">Ganglia Alerts</a></div>
					<div class="panel-body">';
			//populate ganglia alerts/metrics
			if( $serverInSolarwinds == true ){
				$db_connection_nocdash = new db_nocdash();
				$result = $db_connection_nocdash -> query("SELECT entry,highLow,checkType,hostname,threshold,absolute,alertTimestamp,alertID,alertActive,mailID,entryLink,snoozeUntil FROM Noc.gangliaAlerts WHERE hostname LIKE '%".$serverNameShort."%' ORDER BY alertActive DESC");
				echo '<table style="width:100%;" border="0"><tr><th>Alert</th><th>Threshold</th><th>Absolute</th><th>Last Alert</th><th>Active?</th></tr>';
				if( $result->num_rows > 0) {
					while( $row = $result->fetch_assoc() ) {
						if($row['alertActive']){
							echo "<tr style='color:red;'>";
						}else{
							echo "<tr>";
						}
						
						echo "<td style='padding-right:20px;word-break:break-all;'>".$row['checkType']."</td><td style='padding-right:20px;'>".$row['threshold']."</td><td style='padding-right:20px;'>".$row['absolute']."</td><td id='gangliaTimestamp' style='padding-right:20px;'>".$row['alertTimestamp']."</td>";
						if($row['alertActive']){
							echo '<td>Yes';
						}else{
							echo '<td>No';
						}
						echo "</td></tr>";
					}
				}else{
					echo '<tr><td>No Ganglia alerts found.<br></td></tr>';
				}
				echo '</table>';
			}else{
				echo 'No Ganglia alerts found.<br>';
			}
			$queryEndTime = microtime(true);
			$queryProcessTime = $queryEndTime-$queryStartTime;
			echo '<span style="font-size:10px;"><br>Process time: '.round($queryProcessTime,4).' seconds</span>';
			echo '</div></div></div>';

			//populate opendcim data
			$queryStartTime = microtime(true);
			$dcim_serial = '0000000';
			$db_connection_opendcim = new db_opendcim();
			//$result = $db_connection_opendcim -> query("SELECT deviceid,cabinet,position,height,serialno,templateid,primaryip,owner,notes,parentdevice,label FROM fac_Device WHERE serialno='".$swindsSerialNumber."'");
			$result = $db_connection_opendcim -> query("SELECT deviceid,cabinet,position,height,serialno,templateid,primaryip,owner,notes,parentdevice,label FROM fac_Device WHERE deviceid='".$dcim_deviceID."'");
			if($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				$dcim_deviceID = $row['deviceid'];
				$dcim_cabinetID = $row['cabinet'];
				$dcim_position = $row['position'];
				$dcim_height = $row['height'];
				if($row['serialno']!=''){$dcim_serial = $row['serialno'];}
				$dcim_templateID = $row['templateid'];
				$dcim_primaryIP = $row['primaryip'];
				$dcim_ownerID = $row['owner'];
				$dcim_notes = $row['notes'];
				if( trim($dcim_notes) == ''){
					$dcim_notes = '(Empty)';
				}
				$dcim_parentDevice = $row['parentdevice'];
				$dcim_label = $row['label'];
				if( $dcim_parentDevice!='0' ){ // if device has parent device (blade)
					$dcim_position_blade = $dcim_position;
					$db_connection_opendcim = new db_opendcim();
					$result = $db_connection_opendcim -> query("SELECT cabinet,position,height,serialno,templateid,owner,label FROM fac_Device WHERE deviceid=".$dcim_parentDevice);
					$row = $result->fetch_assoc();
					$dcim_cabinetID = $row['cabinet'];
					$dcim_position = $row['position'];
					$dcim_height = $row['height'];
					//$dcim_serial = $row['serialno'];
					//$dcim_templateID = $row['templateid'];
					$dcim_ownerID = $row['owner'];
				}
				
				if( $dcim_primaryIP == ''){
					$dcim_primaryIP='Missing from OpenDCIM';
				}

				$serverNameShort = explode('.',$dcim_label);
				$serverNameShort = $serverNameShort[0];

				if(stristr($dcim_label,'dotomi.com') !== false){
					$dracIP = strtolower($serverNameShort)."r.dotomi.com";
				}else if(stristr($dcim_label,'dtord-homes2') !== false){
					$dracIP = strtolower($serverNameShort)."r.dc.dotomi.net";
				}else if(stristr($dcim_label,'dtmextranet.corp.valueclick.com') !== false){
					$dracIP = strtolower($serverNameShort).".drac.dtmextranet.corp.valueclick.com";
				}else if(stristr($dcim_label,'-mon') !== false){
					$dracIP = strtolower($serverNameShort).".drac.corp.valueclick.com";
				}else if(stristr($dcim_label,'ord-') !== false){
					$dracIP = strtolower($serverNameShort).".drac.corp.valueclick.com";
				}else if(stristr($dcim_label,'corp.valueclick.com') !== false){
					$dracIP = strtolower($serverNameShort).".drac.corp.valueclick.com";
				}else{
					$dracIP = strtolower($serverNameShort)."r.dc.dotomi.net";
				}

				if( stristr($dcim_label,'.dc6.') || stristr($dcim_label,'.sj2.') || stristr($dcim_label,'.ams5.') || stristr($dcim_label,'.sh5.') || stristr($dcim_label,'.wl.') || stristr($dcim_label,'.vclk.') ){
					if( stristr($dcim_label,'blade') ){
						$dracIP = $dcim_label;
					}else{
						$dracIP = 'console.'.$dcim_label;
					}
				}
			}
			$db_connection_opendcim = new db_opendcim();
			$result = $db_connection_opendcim -> query("SELECT Location,DataCenterID,ZoneID FROM fac_Cabinet WHERE CabinetID=".$dcim_cabinetID);
			if($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				$dcim_location = $row['Location'];
				$dcim_datacenter = $row['DataCenterID'];
				$dcim_zoneID = $row['ZoneID'];
			}
			$db_connection_opendcim = new db_opendcim();
			$result = $db_connection_opendcim -> query("SELECT Description FROM fac_Zone WHERE ZoneID=".$dcim_zoneID);
			if($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				$dcim_cage = $row['Description'];
			}
			$db_connection_opendcim = new db_opendcim();
			$result = $db_connection_opendcim -> query("SELECT name FROM fac_DataCenter WHERE DataCenterID=".$dcim_datacenter);
			if($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				$dcim_datacenter = $row['name'];
			}
			$db_connection_opendcim = new db_opendcim();
			$result = $db_connection_opendcim -> query("SELECT model FROM fac_DeviceTemplate WHERE TemplateID=".$dcim_templateID);
			if($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				$dcim_model = $row['model'];
			}
			$db_connection_opendcim = new db_opendcim();
			$result = $db_connection_opendcim -> query("SELECT name FROM fac_Department WHERE DeptID=".$dcim_ownerID);
			$dcim_deptOwner = 'Missing from OpenDCIM';
			if($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				$dcim_deptOwner = $row['name'];
			}
			echo '
			<div id="serverInfoRightPane" class="container col-6 col-sm-6 col-lg-6">
				<div id="dcimPanel" class="col-12 col-sm-12 col-lg-12 panel panel-default" style="padding-left:0px;padding-right:0px;">
					<div class="panel-heading"><a href="http://opendcim.dc.dotomi.net/devices.php?deviceid='.$dcim_deviceID.'" target="_blank" title="View in OpenDCIM">OpenDCIM - '.$dcim_label.'</a></div>
					<div class="panel-body">';
			if(trim($dcim_label)!=='') {
				echo '<table style="width:100%;" border="0"><tr><th>Data Center</th><th>';
				if ( $dcim_parentDevice!='0' ) {
					echo 'Blade ';
				}else{
					echo 'Equipment ';
				}
				echo 'Location</th></tr><tr><td style="padding-right:20px;width:50%;"><a href="http://wiki.cnvrmedia.net/pages/viewpage.action?pageId=54139225" target="_blank">'.$dcim_datacenter.'</a></td><td>'.$dcim_cage.' | '.$dcim_location.' | RU '.$dcim_position;
				if ( isset($dcim_position_blade) ){
					echo ' | Slot '.$dcim_position_blade;
				}
				echo '</td></tr></table><br>';
				echo '<table style="width:100%;" border="0"><tr><th>Model</th><th>Serial Number</th></tr><tr><td style="padding-right:20px;width:50%;">'.$dcim_model.'</td><td>'.$dcim_serial.'</td></tr></table><br>';
				echo '<table style="width:100%;" border="0"><tr><th>IP Address</th><th>Server Owner</th></tr><tr><td style="padding-right:20px;width:50%;">'.$dcim_primaryIP.'</td><td>'.$dcim_deptOwner.'</td></tr></table><br>';
				echo '<table style="width:100%;" border="0"><tr><th>Open DRAC</th><th>Warranty Expiration Date</th></tr><tr><td style="padding-right:20px;width:50%;"><a href="https://'.$dracIP.'" target="_blank" title="Open DRAC in Browser">Launch DRAC</a></td><td style="padding-right:20px;width:50%;vertical-align:top;"><a href="http://www.dell.com/support/home/us/en/04/product-support/servicetag/'.$swindsSerialNumber.'" target="_blank">';
				if ($serialWarranty_EndDate == ''){ 
					echo 'Dell Product Support Site'; 
				}else{
					echo gmdate("m-d-Y",strtotime($serialWarranty_EndDate));
				}
				echo '</a></tr></table><br>';
				echo '<table style="width:100%;" border="0"><tr><th>Notes</th></tr><tr><td style="padding-right:20px;width:50%;">'.$dcim_notes.'</td></tr></table>';
			}else{
				echo '<table style="width:100%;"><tr><td>Error: Cannot match serial number "'.$swindsSerialNumber.'" to equipment in inventory. Verify that this equipment (and serial number) has been correctly entered into <a href="http://opendcim.dc.dotomi.net" target="_blank">OpenDCIM</a>.</td></tr></table>';
			}
			$queryEndTime = microtime(true);
			$queryProcessTime = $queryEndTime-$queryStartTime;
			echo '<table><tr><td colspan=2 style="font-size:10px;"><br>Process time: '.round($queryProcessTime,4).' seconds</td><tr></table>
					</div>
				</div> 

				<div id="jiraPanel" class="col-12 col-sm-12 col-lg-12 panel panel-default" style="padding-left:0px;padding-right:0px;">
					<div class="panel-heading">Jira Tickets | Updated in Last 180 Days
					<a style="float:right;" href="http://jira.cnvrmedia.net/secure/CreateIssue!default.jspa" target="_blank">New Issue</a>
					</div>
					<div class="panel-body">
					<table style="width:100%;margin-bottom:0px;" border="0"><tr><th>Ticket #</th><th>Summary</th><th>Updated</th><th>Status</th></tr>';
			$queryStartTime = microtime(true);
			if( $dcim_serial!='0000000' ){
				$foundJiraIssues = checkJiraSvcTag($dcim_serial,$jiraUN,$jiraPW);
			}else if( $swindsSerialNumber!='0000000' ){
				$foundJiraIssues = checkJiraSvcTag($swindsSerialNumber,$jiraUN,$jiraPW);
			}
			if( $foundJiraIssues->total=='0' || $swindsSerialNumber=='0000000' ){
				if( $swindsSerialNumber=='0000000' ){
					echo '<tr><td colspan=4>Error: Jira tickets unable to be pulled with incomplete OpenDCIM data.</td></tr>';
				}else{
					echo '<tr><td colspan=4>No Jira issues found matching serial number "'.$swindsSerialNumber.'".</td></tr>';
				}
			}else{
                        	foreach($foundJiraIssues->issues as $issue){
                        	        if($issue->key !== "FAIL"){
						echo '<tr><td style="padding-right:20px;"><a href="http://jira.cnvrmedia.net/browse/'.$issue->key.'" target="_blank" title="View Ticket">'.$issue->key.'</a></td><td style="padding-right:20px;">'.$issue->fields->summary.'</td><td id="jiraCreatedTimestamp">'.$issue->fields->updated.'</td><td>'.$issue->fields->status->name.'</td></tr>';
                        	        }else{
						echo '<tr><td colspan=4 style="padding-right:20px;">'.$issue->fields->summary.'</td></tr>';
					}
                        	}
                        }
			$queryEndTime = microtime(true);
			$queryProcessTime = $queryEndTime-$queryStartTime;
			echo '<tr><td colspan=2 style="font-size:10px;"><br>Process time: '.round($queryProcessTime,4).' seconds</td><tr>
				</table>
					</div>
				</div>
			</div>
			';
			
			}
			mssql_close();
			?>
		</div>

		<script>
			$(document).ready(function () {
				$(document).attr('title', $(document).attr('title')+' - Server Lookup');
				var tz = jstz.determine();

				$(' #nodeStatusLastBoot ').text( moment( $(' #nodeStatusLastBoot ').text(), "MMM D YYYY h:m:sa" ) );
				$(' #gangliaTimestamp,#jiraCreatedTimestamp,#appStatsStartTime,#nodeStatusLastBoot ').each(function(){
					var b = moment.utc( $(this).text() ).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss');
					$(this).text(b);
				});
				$(' #irisAlertsStartDate ').text( moment().subtract(1, 'months').startOf('month').format('MMMM D, YYYY') );
			});
		</script>

		<?PHP addFooter(); ?>
	</body>
</html>
