<?PHP
	$path=$_SERVER['DOCUMENT_ROOT'];
	require_once $path.'/php/dbconn.php';
	require 'php/dbconn-swinds.php';
	include $path.'/php/common.php';

	session_write_close();

	function checkJiraSvcTag($svcTag,$jiraUN,$jiraPW){
		$request = new HTTP_Request2('http://jira.cnvrmedia.net/rest/api/latest/search?jql=Status%20NOT%20IN%20(%22Resolved%22,%22Closed%22)%20and%20%22Service%20Tag%22~%22'.$svcTag.'%22%20order%20by%20updated%20asc&maxResults=200', HTTP_Request2::METHOD_GET);
		$request->setAuth($jiraUN,$jiraPW,HTTP_Request2::AUTH_BASIC);
		$request->setConfig(array(
			'ssl_verify_peer'   => FALSE,
			'ssl_verify_host'   => FALSE
		));

		try {
			$response = $request->send();
			if (200 == $response->getStatus()) {
				$json_response = $response->getBody();
				$decode = json_decode($json_response);
				$result='';
				foreach($decode->issues as $issue){
					if($issue->key){
						$result.='<a href="http://jira.cnvrmedia.net/browse/'.$issue->key.'" target="_blank">'.$issue->key.'</a><br>';
					}
				}
			} else {
				echo 'Unexpected HTTP status: ' . $response->getStatus().' '.$response->getReasonPhrase();
			}
		} catch (HTTP_Request2_Exception $e) {
			echo 'Error: ' . $e->getMessage();
			exit();
		}
		return $result;
	}

	function getMaintModeNodes(){
		include $path.'/php/api/jiraCreds.php';
		echo "<table class='table table-striped' style='margin-bottom:0px;'><thead><tr><th>Hostname</th><th></th><th>MaintenanceMode Notes</th><th>Model</th><th>Service Tag</th><th>View Jira Tickets</th></tr></thead><tbody><tr>";

		$request = new HTTP_Request2('http://nocdash.dc.dotomi.net/list.php?getReport=nodes-maintMode', HTTP_Request2::METHOD_GET);
		try {
			$response = $request->send();
			if (200 == $response->getStatus()) {
				$json_response = $response->getBody();
				$decode = json_decode($json_response);

				if( $decode->length=='0' ){
					echo "<tr><td style='text-align:center;padding-top:10px;' colspan=\"6\">No nodes in SolarWinds are currently in MaintenanceMode.</td></tr>";
				}else{
					if($decode->length!=''){
						echo "<tr><td style='text-align:center;padding-top:10px;' colspan=\"6\">No nodes in SolarWinds are currently in MaintenanceMode.</td></tr>";
					}else{
						foreach($decode as $node){
							$serverName = explode('.',$node->sysname);
							$serverName = $serverName[0];

							echo "<td><a href='search.php?lookup=".$serverName."'>".$node->sysname."</a></td><td>[<a href='http://ord-mon102.corp.valueclick.com/Orion/NetPerfMon/NodeDetails.aspx?NetObject=N:".$node->nodeid."' target='_blank'>View in SolarWinds</a>]</td>";
							echo "<td>".$node->maintenancemodenotes."</td><td style='min-width:140px;'>".$node->model."</td><td style='min-width:90px;'>".$node->servicetag."</td>";
							echo "<td><a href='http://jira.cnvrmedia.net/issues/?jql=%22Service%20Tag%22%20~%20%22".$node->servicetag."%22' target='_blank'>History</a></td></tr>";
						}
						echo "</tbody></table>";
					}
				}
			} else {
				echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
				$response->getReasonPhrase();
			}
		} catch (HTTP_Request2_Exception $e) {
			echo 'Error: ' . $e->getMessage();
			exit();
		}
	}
?>

<!DOCTYPE HTML>
<html>
	<head>
		<?PHP getHtmlHead(); ?>
	</head>

	<body>
		<?PHP getHeader(); ?>
		<div id="pageContainer" class="container">
			<div id="maintModePanel" class="panel panel-default">
				<div class="panel-heading">SolarWinds Nodes in MaintenanceMode</div>
				<div class="panel-body">
					<?PHP getMaintModeNodes(); ?>
				</div>
			</div>
		</div>
	</body>
</html>
