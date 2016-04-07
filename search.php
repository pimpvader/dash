<?PHP
	$path=$_SERVER['DOCUMENT_ROOT'];
	require_once $path.'/php/dbconn.php';
	include $path.'/php/common.php';

	function dcimDB_lookup($lookupString){
		$totalSearchResults=0;
		$db_connection_opendcim = new db_opendcim();
		$result = $db_connection_opendcim -> query("SELECT label,deviceid,cabinet,position,height,serialno,templateid,primaryip,owner,notes,parentdevice FROM fac_Device WHERE serialno LIKE '%".$lookupString."%'"); //perform serialno lookup in opendcim
		echo 'Searching OpenDCIM...<br>';
		echo '<table class="table table-striped" id="searchResultTable" style="width:100%;border-bottom:1px solid #ddd;margin-bottom:5px;" border="0">';
		if( $result->num_rows > 0){ //at least one svc tag match in opendcim
			while ( $row = $result->fetch_assoc() ){
				$totalSearchResults+=1;
				$serverName = explode('.',$row['label']);
				$serverName = $serverName[0];
				$dcim_position = $row['position'];
				$dcim_cabinetID = $row['cabinet'];
				if( trim($row['serialno']) === '' ){
					$row['serialno'] = '0000000';
				}
				
				$dcim_parentDevice = $row['parentdevice'];
				if( $dcim_parentDevice!='0' ){ // if device has parent device (blade)
					$dcim_position_blade = $dcim_position;
					$result5 = $db_connection_opendcim -> query("SELECT cabinet,position,height,serialno,templateid,owner,label FROM fac_Device WHERE deviceid=".$dcim_parentDevice);
					$row5 = $result5->fetch_assoc();
					$dcim_cabinetID = $row5['cabinet'];
				}

				$result2 = $db_connection_opendcim -> query("SELECT Location,DataCenterID,ZoneID FROM fac_Cabinet WHERE CabinetID=".$dcim_cabinetID);
				if($result2->num_rows > 0) {
					$row2 = $result2->fetch_assoc();
					$dcim_location = $row2['Location'];
					$dcim_datacenter = $row2['DataCenterID'];
					$dcim_zoneID = $row2['ZoneID'];
				}
				$result3 = $db_connection_opendcim -> query("SELECT Description FROM fac_Zone WHERE ZoneID=".$dcim_zoneID);
				if($result3->num_rows > 0) {
					$row3 = $result3->fetch_assoc();
					$dcim_cage = $row3['Description'];
				}
				$result4 = $db_connection_opendcim -> query("SELECT name FROM fac_DataCenter WHERE DataCenterID=".$dcim_datacenter);
				if($result4->num_rows > 0) {
					$row4 = $result4->fetch_assoc();
					$dcim_datacenter = $row4['name'];
				}

				echo "<tr><td style='width:25px;vertical-align:middle;'><input name='serverSearchResultEntry_".$row['serialno']."' value='".$row['label']."' hidden><input name='serverSearchResult_DeviceID' value='".$row['deviceid']."' hidden></td><td>Hostname: ".$row['label']."<br>Location: ".$dcim_datacenter." | ".$dcim_cage." | ".$dcim_location." | RU ".$row['position'];
				if ( isset($dcim_position_blade) ){
					echo ' | Slot '.$dcim_position_blade;
				}
				echo "<br>Serial: ".$row['serialno']."<br><a href='http://opendcim.dc.dotomi.net/devices.php?deviceid=".$row['deviceid']."' target='_blank'>View in OpenDCIM</a><br>";
				if($row['serialno']=='0000000'){
					echo "<a href='#' style='pointer-events:none;cursor:default;text-decoration:line-through;'>View in NocDash</a> [Serial Number Missing in OpenDCIM]";
				}else{
					echo "<a href='serverInfo.php?svctag=".$row['serialno']."&deviceid=".$row['deviceid']."'>View in NocDash</a>";
				}
				echo "</td></tr>";
			}
		}
		
		$db_connection_opendcim = new db_opendcim();
		$result = $db_connection_opendcim -> query("SELECT label,deviceid,cabinet,position,height,serialno,templateid,primaryip,owner,notes,parentdevice FROM fac_Device WHERE Label LIKE '%".$lookupString."%'");
		if( $result->num_rows > 0){ //no svc tag match, try hostname match
			while ( $row = $result->fetch_assoc() ){
				$totalSearchResults+=1;
				$serverName = explode('.',$row['label']);
				$serverName = $serverName[0];
				$dcim_position = $row['position'];
				$dcim_cabinetID = $row['cabinet'];
				if( trim($row['serialno']) === '' ){
					$row['serialno'] = '0000000';
				}

				$dcim_parentDevice = $row['parentdevice'];
				if( $dcim_parentDevice!='0' ){ // if device has parent device (blade)
					$dcim_position_blade = $dcim_position;
					$result5 = $db_connection_opendcim -> query("SELECT cabinet,position,height,serialno,templateid,owner,label FROM fac_Device WHERE deviceid=".$dcim_parentDevice);
					$row5 = $result5->fetch_assoc();
					$dcim_cabinetID = $row5['cabinet'];
				}

				$result2 = $db_connection_opendcim -> query("SELECT Location,DataCenterID,ZoneID FROM fac_Cabinet WHERE CabinetID=".$dcim_cabinetID);
				if($result2->num_rows > 0) {
					$row2 = $result2->fetch_assoc();
					$dcim_location = $row2['Location'];
					$dcim_datacenter = $row2['DataCenterID'];
					$dcim_zoneID = $row2['ZoneID'];
				}
				$result3 = $db_connection_opendcim -> query("SELECT Description FROM fac_Zone WHERE ZoneID=".$dcim_zoneID);
				if($result3->num_rows > 0) {
					$row3 = $result3->fetch_assoc();
					$dcim_cage = $row3['Description'];
				}
				$result4 = $db_connection_opendcim -> query("SELECT name FROM fac_DataCenter WHERE DataCenterID=".$dcim_datacenter);
				if($result4->num_rows > 0) {
					$row4 = $result4->fetch_assoc();
					$dcim_datacenter = $row4['name'];
				}

				echo "<tr><td style='width:25px;vertical-align:middle;'><input name='serverSearchResultEntry_".$row['serialno']."' value='".$row['label']."' hidden><input name=serverSearchResult_DeviceID' value='".$row['deviceid']."' hidden></td><td>Hostname: ".$row['label']."<br>Location: ".$dcim_datacenter." | ".$dcim_cage." | ".$dcim_location." | RU ".$row['position'];
				if ( isset($dcim_position_blade) ){
					echo ' | Slot '.$dcim_position_blade;
				}
				echo "<br>Serial: ".$row['serialno']."<br><a href='http://opendcim.dc.dotomi.net/devices.php?deviceid=".$row['deviceid']."' target='_blank'>View in OpenDCIM</a><br>";

				if($row['serialno']=='0000000'){
					echo "<a href='#' style='pointer-events:none;cursor:default;text-decoration:line-through;'>View in NocDash</a> [Serial Number Missing in OpenDCIM]";
				}else{
					echo "<a href='serverInfo.php?svctag=".$row['serialno']."&deviceid=".$row['deviceid']."'>View in NocDash</a>";
				}
				echo "</td></tr>";
			}
			echo '</table>';
			return true;
			
		}
		
		if($totalSearchResults == 0){
			echo '<tr><td>No equipment found in OpenDCIM matching that serial/hostname.</td></tr>';
		}
		echo '</table>';
		return false;
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
				if( isset($_POST['serverInfo_search']) || isset($_REQUEST['lookup']) ){
					if( isset($_REQUEST['lookup']) ) {
						$searchQueryInput = trim($_REQUEST['lookup']);
					}else{
						$searchQueryInput = trim($_POST['serverInfo_search']);
					}
					$searchQueryTmp = explode('.',$searchQueryInput);
					$searchQuery = $searchQueryTmp[0]; //just the short hostname
					echo '<div id="serverInfoHeader" class="container col-lg-12">';
					echo '<div class="panel">';
					echo '<div class="panel-body"><b>Search results for \''.$searchQueryInput.'\':</b><br>';
					echo '<form name="serverSearchResult" id="serverSearchResult" role="form" method="post" onsubmit="return OnSubmitForm();">';
					if( dcimDB_lookup($searchQuery) === false){
					
					}
					echo '</form></div></div></div>';
				}else{
					echo '<div id="serverInfoHeader" class="container col-lg-12">';
					echo '<div class="panel">';
					echo '<div class="panel-body">No search terms provided.<br><b>Use search field above.</b><br>';
					echo '</div></div></div>';
				}
			?>
		</div>

		<script>
			$(document).ready(function () {
				$(document).attr('title', $(document).attr('title')+' - Server Lookup');
				var tz = jstz.determine();

				console.log('result count: '+$('#searchResultTable').children().children().length);
			});

		</script>

		<?PHP addFooter(); ?>
	</body>
</html>
