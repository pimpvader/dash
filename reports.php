<?PHP
	$path=$_SERVER['DOCUMENT_ROOT'];
	require_once $path.'/php/dbconn.php';
	include $path.'/php/common.php';
	require_once 'HTTP/Request2.php';
	require_once $path.'/php/api/jiraCreds.php';

	function getReport($reportType,$jiraUN,$jiraPW){
		if($reportType=="jira-daily-esgcm"){
			$previewEmailForm_From='';
			$previewEmailForm_To='';
			$previewEmailForm_CC='';
			$previewEmailForm_BCC='';
			$previewEmailForm_Subject='';
			$previewEmailForm_Body.="<table class='table table-striped' style='margin-bottom:0px;'><thead><tr><th>Key</th><th>Summary</th><th>Assignee</th><th>Reporter</th><th>Deployment Date</th><th>Last Updated</th><th>Status</th></tr></thead><tbody>";
			$request = new HTTP_Request2('http://jira.cnvrmedia.net/rest/api/latest/search?jql=project%20%3D%20ESGCM%20AND%20status%20in%20(%22Pending%20Approval%22,Approved,%22In%20Progress%22)%20AND%20(%22Date%20%26%20Time%22%20%3E=%20startOfDay()%20and%20%22Date%20%26%20Time%22%20%3C=%20endOfDay())%20order%20by%20%22Date%20%26%20Time%22%20DESC&maxResults=150', HTTP_Request2::METHOD_GET); //PROD
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
					
					foreach($decode->issues as $issue){
						$previewEmailForm_Body.="<tr><td><a href='http://jira.cnvrmedia.net/browse/".$issue->key."' target='_blank'>".$issue->key."</a></td><td><a href='http://jira.cnvrmedia.net/browse/".$issue->key."' target='_blank'>".$issue->fields->summary."</a></td><td>".$issue->fields->assignee->displayName."</td><td>".$issue->fields->reporter->displayName."</td><td id='deployDate'>".$issue->fields->customfield_13728."</td><td id='modifiedDate'>".$issue->fields->updated."</td><td>".$issue->fields->status->name."</td></tr>";
					}
					if($decode->total==0){
						$previewEmailForm_Body.="<tr><td colspan=7>No ESGCM tickets scheduled for deployment today.</td></tr>";
                                        }
					$previewEmailForm_Body.="</tbody></table>";
				} else {
					echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
        		                $response->getReasonPhrase();
				}
			} catch (HTTP_Request2_Exception $e) {
				echo 'Error: ' . $e->getMessage();
				exit();
			}

			echo $previewEmailForm_Body;
		}

		if($reportType=="jira-aging-esgcm"){
			$previewEmailForm_From='';
			$previewEmailForm_To='';
			$previewEmailForm_CC='';
			$previewEmailForm_BCC='';
			$previewEmailForm_Subject='';
			$previewEmailForm_Body.="<table class='table table-striped' style='margin-bottom:0px;'><thead><tr><th>Key</th><th>Summary</th><th>Assignee</th><th>Reporter</th><th>Deployment Date</th><th>Last Updated</th><th>Status</th></tr></thead><tbody>";
			$request = new HTTP_Request2('http://jira.cnvrmedia.net/rest/api/latest/search?jql=project%20%3D%20ESGCM%20AND%20Status%20NOT%20IN%20(%22Closed%22,%22Pass%22,%22Rollback%22)%20AND%20%22Date%20%26%20Time%22%20%3C%20startOfDay(-3)%20order%20by%20%22Date%20%26%20Time%22%20DESC&maxResults=150&fields=summary,reporter,assignee,created,updated,status,issuetype,customfield_13728', HTTP_Request2::METHOD_GET); //PROD
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
					
					foreach($decode->issues as $issue){
						$issue_displayName = $issue->fields->assignee->displayName;
						if( is_null($issue->fields->assignee) ){
							$issue_displayName = 'None';
						}
						$previewEmailForm_Body.="<tr><td><a href='http://jira.cnvrmedia.net/browse/".$issue->key."' target='_blank'>".$issue->key."</a></td><td><a href='http://jira.cnvrmedia.net/browse/".$issue->key."' target='_blank'>".$issue->fields->summary."</a></td><td>".$issue_displayName."</td><td>".$issue->fields->reporter->displayName."</td><td id='deployDate'>".$issue->fields->customfield_13728."</td><td id='modifiedDate'>".$issue->fields->updated."</td><td>".$issue->fields->status->name."</td></tr>";
					}
					if($decode->total==0){
						$previewEmailForm_Body.="<tr><td colspan=7>No aging ESGCM tickets found.</td></tr>";
                                        }
					$previewEmailForm_Body.="</tbody></table>";
				} else {
					echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
        		                $response->getReasonPhrase();
				}
			} catch (HTTP_Request2_Exception $e) {
				echo 'Error: ' . $e->getMessage();
				exit();
			}

			echo $previewEmailForm_Body;
		}

		if($reportType=="jira-weekly-noc"){
			$previewEmailForm_From='';
                        $previewEmailForm_To='';
                        $previewEmailForm_CC='';
                        $previewEmailForm_BCC='';
                        $previewEmailForm_Subject='';
			$previewEmailForm_Body.="<div><div class='col-lg-12' id='netopsWeeklyTicketsContainer'></div></div>";
                        $previewEmailForm_Body.="<table class='table table-striped' style='margin-bottom:0px;'><thead><tr><th>Key</th><th>Summary</th><th>Hostname</th><th>Assignee</th><th>Reporter</th><th>Created</th><th>Last Updated</th><th>Status</th></tr></thead><tbody><tr>";
                        $request = new HTTP_Request2('http://jira.cnvrmedia.net/rest/api/latest/search?jql=project%20in%20(NOC,MONITOR)%20and%20(%20createdDate%20%3E%3D%20startOfWeek()%20and%20createdDate%20%3C%3DendOfWeek()%20)%20order%20by%20Created%20desc&maxResults=200', HTTP_Request2::METHOD_GET);
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

                                        foreach($decode->issues as $issue){
                                                $previewEmailForm_Body.="<td><a href='http://jira.cnvrmedia.net/browse/".$issue->key."' target='_blank'>".$issue->key."</a></td><td><a href='http://jira.cnvrmedia.net/browse/".$issue->key."' target='_blank'>".$issue->fields->summary."</a></td><td id='hostname'>".$issue->fields->customfield_11667."</td><td id='jiraAssignee'>".$issue->fields->assignee->displayName."</td><td id='jiraReporter'>".$issue->fields->reporter->displayName."</td><td id='createdDate'>".$issue->fields->created."</td><td id='modifiedDate'>".$issue->fields->updated."</td><td id='jiraStatus'>".$issue->fields->status->name."</td></tr>";
                                        }
                                        $previewEmailForm_Body.="</tbody></table>";
                                } else {
                                        echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
                                        $response->getReasonPhrase();
                                }
                        } catch (HTTP_Request2_Exception $e) {
                                echo 'Error: ' . $e->getMessage();
                                exit();
                        }

                        echo $previewEmailForm_Body;

		}

		if($reportType=="jira-aging-noc"){
                        $previewEmailForm_From='';
                        $previewEmailForm_To='';
                        $previewEmailForm_CC='';
                        $previewEmailForm_BCC='';
                        $previewEmailForm_Subject='';
			$previewEmailForm_Body.="<div><div class='col-lg-12' id='netopsAgingTicketsContainer'></div></div>";
                        $previewEmailForm_Body.="<table class='table table-striped' style='margin-bottom:0px;'><thead><tr><th>Key</th><th>Summary</th><th>Hostname</th><th>Assignee</th><th>Reporter</th><th>Created</th><th>Last Updated</th><th>Status</th></tr></thead><tbody><tr>";
			$request = new HTTP_Request2('http://jira.cnvrmedia.net/rest/api/latest/search?jql=project%20in%20(NOC,MONITOR)%20AND%20Status%20NOT%20IN%20(Resolved,Closed)%20AND%20%22updated%22%20%3C%20startOfDay(-30)%20order%20by%20%22Created%22%20asc&maxResults=200', HTTP_Request2::METHOD_GET);
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

                                        foreach($decode->issues as $issue){
                                                $previewEmailForm_Body.="<td><a href='http://jira.cnvrmedia.net/browse/".$issue->key."' target='_blank'>".$issue->key."</a></td><td><a href='http://jira.cnvrmedia.net/browse/".$issue->key."' target='_blank'>".$issue->fields->summary."</a></td><td>".$issue->fields->customfield_11667."</td><td id='jiraAssignee'>".$issue->fields->assignee->displayName."</td><td id='jiraReporter'>".$issue->fields->reporter->displayName."</td><td id='createdDate'>".$issue->fields->created."</td><td id='modifiedDate'>".$issue->fields->updated."</td><td id='jiraStatus'>".$issue->fields->status->name."</td></tr>";
                                        }
                                        $previewEmailForm_Body.="</tbody></table>";
                                } else {
                                        echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
                                        $response->getReasonPhrase();
                                }
                        } catch (HTTP_Request2_Exception $e) {
                                echo 'Error: ' . $e->getMessage();
                                exit();
                        }

                        echo $previewEmailForm_Body;

                }

		if($reportType=="hw-issues-24hr"){

		}
		
		if($reportType=="hw-outofbounds"){

		}

		function checkJiraSvcTag($svcTag,$jiraUN,$jiraPW){
                        $request = new HTTP_Request2('http://jira.cnvrmedia.net/rest/api/latest/search?jql=project%20in%20(NOC)%20AND%20Status%20NOT%20IN%20(%22Resolved%22,%22Closed%22)%20and%20%22Service%20Tag%22~%22'.$svcTag.'%22%20order%20by%20updated%20asc&maxResults=200', HTTP_Request2::METHOD_GET);
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
                                        echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
                                        $response->getReasonPhrase();
                                }
                        } catch (HTTP_Request2_Exception $e) {
                                echo 'Error: ' . $e->getMessage();
                                exit();
			}
			return $result;
                }

		if($reportType=="hw-health-critical"){
                        $previewEmailForm_Body.="<table class='table table-striped' style='margin-bottom:0px;'><thead><tr><th>Hostname</th><th>Hardware Issues</th><th>Model</th><th>Service Tag</th><th>Open Jira Tickets</th><th>Past Tickets</th></tr></thead><tbody><tr>";
                        $request = new HTTP_Request2('http://nocdash.dc.dotomi.net/list.php?getReport=hw-health-critical', HTTP_Request2::METHOD_GET);
                        try {
                                $response = $request->send();
                                if (200 == $response->getStatus()) {
                                        $json_response = $response->getBody();
                                        $decode = json_decode($json_response);

                                        foreach($decode as $node){
						$serverName = explode('.',$node->hardwarename);
						$serverName = $serverName[0];

                                                $previewEmailForm_Body.="<td><a href='search.php?lookup=".$serverName."'>".$node->hardwarename."</a></td>";
						$node->sensorswithproblems=str_replace("\n","<br>",$node->sensorswithproblems);
						$previewEmailForm_Body.="<td>".$node->sensorswithproblems."</td><td style='min-width:140px;'>".$node->model."</td><td style='min-width:90px;'>".$node->servicetag."</td><td style='min-width:110px;'>";
						if($node->servicetag!=''){$previewEmailForm_Body.=checkJiraSvcTag($node->servicetag,$jiraUN,$jiraPW);}
						$previewEmailForm_Body.="</td><td><a href='http://jira.cnvrmedia.net/issues/?jql=%22Service%20Tag%22%20~%20%22".$node->servicetag."%22' target='_blank'>History</a></td></tr>";
                                        }
                                        $previewEmailForm_Body.="</tbody></table>";
                                } else {
                                        echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
                                        $response->getReasonPhrase();
                                }
                        } catch (HTTP_Request2_Exception $e) {
                                echo 'Error: ' . $e->getMessage();
                                exit();
                        }
                        echo $previewEmailForm_Body;
		}

		if($reportType=="hw-health-undefined"){
                        $previewEmailForm_Body.="<table class='table table-striped' style='margin-bottom:0px;'><thead><tr><th>Hostname</th><th>Hardware Status</th><th>Model</th><th>Service Tag</th><th>Open Jira Tickets</th><th>Past Tickets</th></tr></thead><tbody><tr>";
                        $request = new HTTP_Request2('http://nocdash.dc.dotomi.net/list.php?getReport=hw-health-undefined', HTTP_Request2::METHOD_GET);
                        try {
                                $response = $request->send();
                                if (200 == $response->getStatus()) {
                                        $json_response = $response->getBody();
                                        $decode = json_decode($json_response);

					if( $decode->length=='0' ){
						$previewEmailForm_Body.="<tr><td style='text-align:center;padding-top:10px;' colspan=\"6\">No equipment is currently exhibiting a critical hardware issue.</td></tr>";
					}else{
						if($decode->length!=''){
							$previewEmailForm_Body.="<tr><td style='text-align:center;padding-top:10px;' colspan=\"6\">No equipment is currently exhibiting a critical hardware issue.</td></tr>";
						}else{
                                        		foreach($decode as $node){
								$serverName = explode('.',$node->hardwarename);
								$serverName = $serverName[0];

                                                		$previewEmailForm_Body.="<td><a href='search.php?lookup=".$serverName."'>".$node->hardwarename."</a></td>";
                                                		$previewEmailForm_Body.="<td>".$node->hardwarestatusdescription."</td><td style='min-width:140px;'>".$node->model."</td><td style='min-width:90px;'>".$node->servicetag."</td><td style='min-width:110px;'>";
                                                		if($node->servicetag!=''){$previewEmailForm_Body.=checkJiraSvcTag($node->servicetag,$jiraUN,$jiraPW);}
                                                		$previewEmailForm_Body.="</td><td><a href='http://jira.cnvrmedia.net/issues/?jql=%22Service%20Tag%22%20~%20%22".$node->servicetag."%22' target='_blank'>History</a></td></tr>";
							}
						}
                                        }
                                        $previewEmailForm_Body.="</tbody></table>";
                                } else {
                                        echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
                                        $response->getReasonPhrase();
                                }
                        } catch (HTTP_Request2_Exception $e) {
                                echo 'Error: ' . $e->getMessage();
                                exit();
                        }
                        echo $previewEmailForm_Body;
                }


		if($reportType=="hw-disks-warning"){
                        $previewEmailForm_Body.="<table class='table table-striped' style='margin-bottom:0px;'><thead><tr><th>Hostname</th><th>Hardware Issues</th><th>Model</th><th>Service Tag</th><th>Open Jira Tickets</th><th>Past Tickets</th></tr></thead><tbody><tr>";
                        $request = new HTTP_Request2('http://nocdash.dc.dotomi.net/list.php?getReport=hw-disks-warning', HTTP_Request2::METHOD_GET);
                        try {
                                $response = $request->send();
                                if (200 == $response->getStatus()) {
                                        $json_response = $response->getBody();
                                        $decode = json_decode($json_response);

					if( $decode->length=='0' ){
                                		$previewEmailForm_Body.="<tr><td style='text-align:center;padding-top:10px;' colspan=\"6\">No physical disks are currently exhibiting issues.</td></tr>";
					}else{
						if($decode->length!=''){
                                			$previewEmailForm_Body.="<tr><td style='text-align:center;padding-top:10px;' colspan=\"6\">No physical disks are currently exhibiting issues.</td></tr>";
						}else{
                                        		foreach($decode as $node){
								$serverName = explode('.',$node->hardwarename);
								$serverName = $serverName[0];

                                        		        $previewEmailForm_Body.="<td><a href='search.php?lookup=".$serverName."'>".$node->hardwarename."</a></td>";
                                        		        $node->sensorswithproblems=str_replace("\n","<br>",$node->sensorswithproblems);
                                        		        $previewEmailForm_Body.="<td>".$node->sensorswithproblems."</td><td style='min-width:140px;'>".$node->model."</td><td style='min-width:90px;'>".$node->servicetag."</td><td style='min-width:110px;'>";
                                        		        if($node->servicetag!=''){$previewEmailForm_Body.=checkJiraSvcTag($node->servicetag,$jiraUN,$jiraPW);}
                                        		        $previewEmailForm_Body.="</td><td><a href='http://jira.cnvrmedia.net/issues/?jql=%22Service%20Tag%22%20~%20%22".$node->servicetag."%22' target='_blank'>History</a></td></tr>";
                                        		}
						}
					}
                                        $previewEmailForm_Body.="</tbody></table>";
                                } else {
                                        echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
                                        $response->getReasonPhrase();
                                }
                        } catch (HTTP_Request2_Exception $e) {
                                echo 'Error: ' . $e->getMessage();
                                exit();
                        }
                        echo $previewEmailForm_Body;
                }

		if($reportType=="hw-dimm-warning"){
                        $previewEmailForm_Body.="<table class='table table-striped' style='margin-bottom:0px;'><thead><tr><th>Hostname</th><th>Hardware Issues</th><th>Model</th><th>Service Tag</th><th>Open Jira Tickets</th><th>Past Tickets</th></tr></thead><tbody><tr>";
                        $request = new HTTP_Request2('http://nocdash.dc.dotomi.net/list.php?getReport=hw-dimm-warning', HTTP_Request2::METHOD_GET);
                        try {
                                $response = $request->send();
                                if (200 == $response->getStatus()) {
                                        $json_response = $response->getBody();
                                        $decode = json_decode($json_response);

					if( $decode->length=='0' ){
                                		$previewEmailForm_Body.="<tr><td style='text-align:center;padding-top:10px;' colspan=\"6\">No memory DIMMs are currently exhibiting issues.</td></tr>";
					}else{
						if($decode->length!=''){
                                			$previewEmailForm_Body.="<tr><td style='text-align:center;padding-top:10px;' colspan=\"6\">No memory DIMMs are currently exhibiting issues.</td></tr>";
						}else{
                                        		foreach($decode as $node){
								$serverName = explode('.',$node->hardwarename);
								$serverName = $serverName[0];

                                        		        $previewEmailForm_Body.="<td><a href='search.php?lookup=".$serverName."'>".$node->hardwarename."</a></td>";
                                        		        $node->sensorswithproblems=str_replace("\n","<br>",$node->sensorswithproblems);
                                        		        $previewEmailForm_Body.="<td>".$node->sensorswithproblems."</td><td style='min-width:140px;'>".$node->model."</td><td style='min-width:90px;'>".$node->servicetag."</td><td style='min-width:110px;'>";
                                        		        if($node->servicetag!=''){$previewEmailForm_Body.=checkJiraSvcTag($node->servicetag,$jiraUN,$jiraPW);}
                                        		        $previewEmailForm_Body.="</td><td><a href='http://jira.cnvrmedia.net/issues/?jql=%22Service%20Tag%22%20~%20%22".$node->servicetag."%22' target='_blank'>History</a></td></tr>";
                                        		}
						}
					}
                                        $previewEmailForm_Body.="</tbody></table>";
                                } else {
                                        echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
                                        $response->getReasonPhrase();
                                }
                        } catch (HTTP_Request2_Exception $e) {
                                echo 'Error: ' . $e->getMessage();
                                exit();
                        }
                        echo $previewEmailForm_Body;
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
		<div id="reportContainer" style="min-width:970px;" class="container">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?PHP
						if($_REQUEST['report']=='jira-daily-esgcm'){
							echo 'ESGCM Tickets for Deployments Today';
						}
						if($_REQUEST['report']=='jira-aging-esgcm'){
							echo 'Aging ESGCM Tickets Not Yet Closed';
						}
						if($_REQUEST['report']=='jira-weekly-noc'){
							echo 'NOC Tickets for This Week';
						}
						if($_REQUEST['report']=='jira-aging-noc'){
							echo 'Aging NOC Tickets (30+ days since last update)';
						}
						if($_REQUEST['report']=='hw-health-critical'){
							echo 'Nodes with Critical Hardware';
						}
						if($_REQUEST['report']=='hw-health-undefined'){
							echo 'Nodes Missing Hardware Information';
						}
						if($_REQUEST['report']=='hw-disks-warning'){
							echo 'Physical Disks: Warning & Critical Status';
						}
						if($_REQUEST['report']=='hw-dimm-warning'){
							echo 'Memory DIMMs: Warning & Critical Status';
						}
					?>
				</div>
				<div class="panel-body">
					<?PHP getReport($_REQUEST['report'],$jiraUN,$jiraPW); ?>
				</div>
			</div>
		</div>

                <script>
                        $(document).ready(function () {
				var tz = jstz.determine();
				$(' #loading-modal ').modal('hide');
				$(document).attr('title', $(document).attr('title')+' - Reports');
				$('#nav-active-reports').addClass('active');

				$(' #deployDate,#createdDate,#modifiedDate ').each(function(){
					var b = moment.utc( $(this).text() ).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss');
					$(this).text(b);
				});

				$(' #jiraAssignee ').each(function(){
					if( $(this).text() == '' ){
						$(this).text('None');
					}
				});

				if( $(' #netopsWeeklyTicketsContainer ').length != 0 ){
					console.log('netopsWeeklyTicketsContainer EXISTS');
					var netopsOpen = new Array();
					netopsOpen[moment().day(0).format('MM/DD/YYYY')] = 0;
					netopsOpen[moment().day(1).format('MM/DD/YYYY')] = 0;
					netopsOpen[moment().day(2).format('MM/DD/YYYY')] = 0;
					netopsOpen[moment().day(3).format('MM/DD/YYYY')] = 0;
					netopsOpen[moment().day(4).format('MM/DD/YYYY')] = 0;
					netopsOpen[moment().day(5).format('MM/DD/YYYY')] = 0;
					netopsOpen[moment().day(6).format('MM/DD/YYYY')] = 0;

					var netopsResolved = new Array();
					netopsResolved[moment().day(0).format('MM/DD/YYYY')] = 0;
					netopsResolved[moment().day(1).format('MM/DD/YYYY')] = 0;
					netopsResolved[moment().day(2).format('MM/DD/YYYY')] = 0;
					netopsResolved[moment().day(3).format('MM/DD/YYYY')] = 0;
					netopsResolved[moment().day(4).format('MM/DD/YYYY')] = 0;
					netopsResolved[moment().day(5).format('MM/DD/YYYY')] = 0;
					netopsResolved[moment().day(6).format('MM/DD/YYYY')] = 0;

					var netopsClosed = new Array();
					netopsClosed[moment().day(0).format('MM/DD/YYYY')] = 0;
					netopsClosed[moment().day(1).format('MM/DD/YYYY')] = 0;
					netopsClosed[moment().day(2).format('MM/DD/YYYY')] = 0;
					netopsClosed[moment().day(3).format('MM/DD/YYYY')] = 0;
					netopsClosed[moment().day(4).format('MM/DD/YYYY')] = 0;
					netopsClosed[moment().day(5).format('MM/DD/YYYY')] = 0;
					netopsClosed[moment().day(6).format('MM/DD/YYYY')] = 0;

					var netopsReopened = new Array();
					netopsReopened[moment().day(0).format('MM/DD/YYYY')] = 0;
					netopsReopened[moment().day(1).format('MM/DD/YYYY')] = 0;
					netopsReopened[moment().day(2).format('MM/DD/YYYY')] = 0;
					netopsReopened[moment().day(3).format('MM/DD/YYYY')] = 0;
					netopsReopened[moment().day(4).format('MM/DD/YYYY')] = 0;
					netopsReopened[moment().day(5).format('MM/DD/YYYY')] = 0;
					netopsReopened[moment().day(6).format('MM/DD/YYYY')] = 0;

					var netopsInProgress = new Array();
					netopsInProgress[moment().day(0).format('MM/DD/YYYY')] = 0;
					netopsInProgress[moment().day(1).format('MM/DD/YYYY')] = 0;
					netopsInProgress[moment().day(2).format('MM/DD/YYYY')] = 0;
					netopsInProgress[moment().day(3).format('MM/DD/YYYY')] = 0;
					netopsInProgress[moment().day(4).format('MM/DD/YYYY')] = 0;
					netopsInProgress[moment().day(5).format('MM/DD/YYYY')] = 0;
					netopsInProgress[moment().day(6).format('MM/DD/YYYY')] = 0;

					var netopsOnHold = new Array();
					netopsOnHold[moment().day(0).format('MM/DD/YYYY')] = 0;
					netopsOnHold[moment().day(1).format('MM/DD/YYYY')] = 0;
					netopsOnHold[moment().day(2).format('MM/DD/YYYY')] = 0;
					netopsOnHold[moment().day(3).format('MM/DD/YYYY')] = 0;
					netopsOnHold[moment().day(4).format('MM/DD/YYYY')] = 0;
					netopsOnHold[moment().day(5).format('MM/DD/YYYY')] = 0;
					netopsOnHold[moment().day(6).format('MM/DD/YYYY')] = 0;

					$(' #createdDate ').each(function(){
						parentRow = $(this).parent();
						dateSplit = $(this).text().split(' ');

						if( parentRow[0].childNodes[7].innerHTML == 'Open' ){
							tmpCount = netopsOpen[dateSplit[0]];
							console.log(tmpCount);
							tmpCount++;
							netopsOpen[dateSplit[0]] = tmpCount;
						}else if( parentRow[0].childNodes[7].innerHTML == 'Resolved' ){
							tmpCount = netopsResolved[dateSplit[0]];
							tmpCount++;
							netopsResolved[dateSplit[0]] = tmpCount;
						}else if( parentRow[0].childNodes[7].innerHTML == 'Closed' ){
							tmpCount = netopsClosed[dateSplit[0]];
							tmpCount++;
							netopsClosed[dateSplit[0]] = tmpCount;
						}else if( parentRow[0].childNodes[7].innerHTML == 'Reopened' ){
							tmpCount = netopsReopened[dateSplit[0]];
							tmpCount++;
							netopsReopened[dateSplit[0]] = tmpCount;
						}else if( parentRow[0].childNodes[7].innerHTML == 'In Progress' ){
							tmpCount = netopsInProgress[dateSplit[0]];
							tmpCount++;
							netopsInProgress[dateSplit[0]] = tmpCount;
						}else if( parentRow[0].childNodes[7].innerHTML == 'On Hold' ){
							tmpCount = netopsOnHold[dateSplit[0]];
							tmpCount++;
							netopsOnHold[dateSplit[0]] = tmpCount;
						}else {
							console.log('jira-weekly-noc ticket count FAILURE!!!');
						}
					});
				
					$('#netopsWeeklyTicketsContainer').highcharts({
                                                chart: {
                                                    type: 'column'
                                                },
                                                title: {
                                                    text: 'NOC Tickets This Week'
                                                },
						credits: false,
                                                xAxis: {
                                                    categories: [
                                                        'Sunday',
                                                        'Monday',
                                                        'Tuesday',
                                                        'Wednesday',
                                                        'Thursday',
                                                        'Friday',
                                                        'Saturday'
                                                    ]
                                                },
                                                yAxis: {
                                                    min: 0,
                                                    title: {
                                                        text: 'Tickets'
                                                    },
                                                    minTickInterval: 1
                                                },
                                                tooltip: {
                                                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                                                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                                        '<td style="padding:0"><b>{point.y} tickets</b></td></tr>',
                                                    footerFormat: '</table>',
                                                    shared: true,
                                                    useHTML: true
                                                },
                                                plotOptions: {
                                                    column: {
                                                        pointPadding: 0.2,
                                                        borderWidth: 0
                                                    }
                                                },
                                                series: [{
                                                    name: 'Open',
                                                    data: [netopsOpen[moment().day(0).format('MM/DD/YYYY')],netopsOpen[moment().day(1).format('MM/DD/YYYY')],netopsOpen[moment().day(2).format('MM/DD/YYYY')],netopsOpen[moment().day(3).format('MM/DD/YYYY')],netopsOpen[moment().day(4).format('MM/DD/YYYY')],netopsOpen[moment().day(5).format('MM/DD/YYYY')],netopsOpen[moment().day(6).format('MM/DD/YYYY')]]
                                                }, {
                                                    name: 'Resolved',
                                                    data: [netopsResolved[moment().day(0).format('MM/DD/YYYY')],netopsResolved[moment().day(1).format('MM/DD/YYYY')],netopsResolved[moment().day(2).format('MM/DD/YYYY')],netopsResolved[moment().day(3).format('MM/DD/YYYY')],netopsResolved[moment().day(4).format('MM/DD/YYYY')],netopsResolved[moment().day(5).format('MM/DD/YYYY')],netopsResolved[moment().day(6).format('MM/DD/YYYY')]]
                                                }, {
                                                    name: 'Closed',
                                                    data: [netopsClosed[moment().day(0).format('MM/DD/YYYY')],netopsClosed[moment().day(1).format('MM/DD/YYYY')],netopsClosed[moment().day(2).format('MM/DD/YYYY')],netopsClosed[moment().day(3).format('MM/DD/YYYY')],netopsClosed[moment().day(4).format('MM/DD/YYYY')],netopsClosed[moment().day(5).format('MM/DD/YYYY')],netopsClosed[moment().day(6).format('MM/DD/YYYY')]]
                                                }, {
                                                    name: 'Reopened',
                                                    data: [netopsReopened[moment().day(0).format('MM/DD/YYYY')],netopsReopened[moment().day(1).format('MM/DD/YYYY')],netopsReopened[moment().day(2).format('MM/DD/YYYY')],netopsReopened[moment().day(3).format('MM/DD/YYYY')],netopsReopened[moment().day(4).format('MM/DD/YYYY')],netopsReopened[moment().day(5).format('MM/DD/YYYY')],netopsReopened[moment().day(6).format('MM/DD/YYYY')]]
                                                }, {
                                                    name: 'On Hold',
                                                    data: [netopsOnHold[moment().day(0).format('MM/DD/YYYY')],netopsOnHold[moment().day(1).format('MM/DD/YYYY')],netopsOnHold[moment().day(2).format('MM/DD/YYYY')],netopsOnHold[moment().day(3).format('MM/DD/YYYY')],netopsOnHold[moment().day(4).format('MM/DD/YYYY')],netopsOnHold[moment().day(5).format('MM/DD/YYYY')],netopsOnHold[moment().day(6).format('MM/DD/YYYY')]]
                                                }, {
                                                    name: 'In Progress',
                                                    data: [netopsInProgress[moment().day(0).format('MM/DD/YYYY')],netopsInProgress[moment().day(1).format('MM/DD/YYYY')],netopsInProgress[moment().day(2).format('MM/DD/YYYY')],netopsInProgress[moment().day(3).format('MM/DD/YYYY')],netopsInProgress[moment().day(4).format('MM/DD/YYYY')],netopsInProgress[moment().day(5).format('MM/DD/YYYY')],netopsInProgress[moment().day(6).format('MM/DD/YYYY')]]
                                                }]
                                        });
				}

				if( $(' #netopsAgingTicketsContainer ').length != 0 ){
					console.log('netopsAgingTicketsContainer EXISTS');
					var netopsOpen=[0,0,0,0,0,0,0,0,0,0,0,0];
					var netopsResolved=[0,0,0,0,0,0,0,0,0,0,0,0];
					var netopsReopened=[0,0,0,0,0,0,0,0,0,0,0,0];
					var netopsInProgress=[0,0,0,0,0,0,0,0,0,0,0,0];
					var netopsOnHold=[0,0,0,0,0,0,0,0,0,0,0,0];

					$(' #createdDate ').each(function(){
						parentRow = $(this).parent();
						dateSplit = $(this).text().split(' ');
						dateSplitMonth = dateSplit[0].split('/');

						if( parentRow[0].childNodes[7].innerHTML == 'Open' ){
							tmpCount = netopsOpen[parseInt(dateSplitMonth[0],10)-1];
							tmpCount++;
							netopsOpen[parseInt(dateSplitMonth[0],10)-1] = tmpCount;
						}else if( parentRow[0].childNodes[7].innerHTML == 'Resolved' ){
							tmpCount = netopsResolved[parseInt(dateSplitMonth[0],10)-1];
							tmpCount++;
							netopsResolved[parseInt(dateSplitMonth[0],10)-1] = tmpCount;
						}else if( parentRow[0].childNodes[7].innerHTML == 'Reopened' ){
							tmpCount = netopsReopened[parseInt(dateSplitMonth,10)-1];
							tmpCount++;
							netopsReopened[parseInt(dateSplitMonth,10)-1] = tmpCount;
						}else if( parentRow[0].childNodes[7].innerHTML == 'In Progress' ){
							tmpCount = netopsInProgress[parseInt(dateSplitMonth,10)-1];
							tmpCount++;
							netopsInProgress[parseInt(dateSplitMonth,10)-1] = tmpCount;
						}else if( parentRow[0].childNodes[7].innerHTML == 'On Hold' ){
							tmpCount = netopsOnHold[parseInt(dateSplitMonth,10)-1];
							tmpCount++;
							netopsOnHold[parseInt(dateSplitMonth,10)-1] = tmpCount;
						}else {
							console.log('jira-aging-noc ticket count FAILURE!!!');
						}
					});
					
					$('#netopsAgingTicketsContainer').highcharts({
						chart: {
            					    type: 'column'
            					},
            					title: {
            					    text: 'Aging NOC Tickets'
            					},
						credits: false,
            					xAxis: {
            					    categories: [
            					        'Jan',
            					        'Feb',
            					        'Mar',
            					        'Apr',
            					        'May',
            					        'Jun',
            					        'Jul',
            					        'Aug',
            					        'Sep',
            					        'Oct',
            					        'Nov',
            					        'Dec'
            					    ]
            					},
            					yAxis: {
            					    min: 0,
            					    title: {
            					        text: 'Tickets'
            					    },
						    minTickInterval: 1
            					},
            					tooltip: {
            					    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            					    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            					        '<td style="padding:0"><b>{point.y} tickets</b></td></tr>',
            					    footerFormat: '</table>',
            					    shared: true,
            					    useHTML: true
            					},
            					plotOptions: {
            					    column: {
            					        pointPadding: 0.2,
            					        borderWidth: 0
            					    }
            					},
            					series: [{
            					    name: 'Open',
            					    data: netopsOpen
            					}, {
            					    name: 'Resolved',
            					    data: netopsResolved
            					}, {
            					    name: 'Reopened',
            					    data: netopsReopened
            					}, {
            					    name: 'On Hold',
            					    data: netopsOnHold
            					}, {
            					    name: 'In Progress',
            					    data: netopsInProgress
            					}]
					});
				}

                                setInterval(function(){
                                
				}, 30000);
                        });
                </script>
		
		<?PHP addFooter(); ?>
	</body>
</html>
