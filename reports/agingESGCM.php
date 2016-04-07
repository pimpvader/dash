<?PHP
	//include '../php/common.php';
	$path=$_SERVER['DOCUMENT_ROOT'];
	require_once 'HTTP/Request2.php';
	require_once $path.'/php/api/jiraCreds.php';

	function getJiraTickets($jiraUN,$jiraPW,$queryType,$esgcmReporter="",$esgcmReporterTimeZone=""){
		switch($queryType){
			case 'esgcm-all':
				$request = new HTTP_Request2('http://jira.cnvrmedia.net/rest/api/latest/search?jql=project%20%3D%20ESGCM%20AND%20Status%20NOT%20IN%20(%22Closed%22,%22Pass%22,%22Rollback%22)%20AND%20%22Date%20%26%20Time%22%20%3C%20startOfDay(-3)%20order%20by%20%22Date%20%26%20Time%22%20ASC&maxResults=150&fields=summary,reporter,assignee,created,updated,status,issuetype,customfield_13728', HTTP_Request2::METHOD_GET); //PROD
				break;
			case 'esgcm-user':
				$request = new HTTP_Request2('http://jira.cnvrmedia.net/rest/api/latest/search?jql=project%20%3D%20ESGCM%20AND%20Status%20NOT%20IN%20(%22Closed%22,%22Pass%22,%22Rollback%22)%20AND%20%22Date%20%26%20Time%22%20%3C%20startOfDay(-3)%20and%20reporter=%27'.$esgcmReporter.'%27%20order%20by%20%22Date%20%26%20Time%22%20ASC&maxResults=150&fields=summary,reporter,assignee,created,updated,status,issuetype,customfield_13728', HTTP_Request2::METHOD_GET); //PROD
				break;
			case 'user-info':
				$request = new HTTP_Request2('http://jira.cnvrmedia.net/rest/api/latest/user?username='.$esgcmReporter.'&maxResults=1', HTTP_Request2::METHOD_GET); //PROD
				break;
		}
		
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
				
				if($queryType=='esgcm-user'){
					$result = "";
					foreach($decode->issues as $issue){
						$result.="<tr><td style='padding-top:5px;padding-bottom:5px;'><a href='http://jira.cnvrmedia.net/browse/".$issue->key."'>".$issue->key."</a></td><td style='padding-top:5px;padding-bottom:5px;'><a href='http://jira.cnvrmedia.net/browse/".$issue->key."'>".$issue->fields->summary."</a></td><td style='padding-top:5px;padding-bottom:5px;'>".$issue->fields->assignee->displayName."</td><td style='padding-top:5px;padding-bottom:5px;'>".$issue->fields->reporter->displayName."</td><td style='padding-top:5px;padding-bottom:5px;'>";
						$date = new DateTime($issue->fields->customfield_13728);
						$date->setTimezone(new DateTimeZone($esgcmReporterTimeZone));
						$result.=$date->format('m/d/Y H:i:s')."</td><td style='padding-top:5px;padding-bottom:5px;'>";
						$date = new DateTime($issue->fields->updated);
						$date->setTimezone(new DateTimeZone($esgcmReporterTimeZone));
						$result.=$date->format('m/d/Y H:i:s')."</td><td style='padding-top:5px;padding-bottom:5px;'>".$issue->fields->status->name."</td></tr>";
					}
				}
				if($queryType=='user-info'){
					$result = array();
					//foreach($decode->users as $user){
						//$result=$user->displayName;
						$result[0]=$decode->displayName;
						$result[1]=$decode->timeZone;
					//}
				}
				if($queryType=='esgcm-all'){
					$firstResult = TRUE;
					$i=0;
					$result = array();
					foreach($decode->issues as $issue){
						if($firstResult == TRUE){
							//echo 'First pass >>><br>';
							$result[$i] = $issue->fields->reporter->name;
							//echo '$result['.$i.']='.$result[$i].'<br>';
							$firstResult = FALSE;
							$i+=1;
						}else{
							//echo 'Next pass >>><br>';
							$matchFound = FALSE;
							for($j=0;$j<$i;$j++){
								//echo '$result['.$j.']='.$result[$j].'<br>';
								if($issue->fields->reporter->name == $result[$j]){
									$matchFound = TRUE;
								}
							}
							if($matchFound == FALSE){
								$result[$i] = $issue->fields->reporter->name;
								//echo '$result['.$i.']='.$result[$i].'<br>';
								//echo 'Found ticket for '.$result[$i].'<br>';
							}else{
								$result[$i] = 'none';
								//echo '$result['.$i.']=DUPLICATE<br>';
							}
							$i+=1;
						}
						//echo '<<< End pass<br><br>';
					}
					if($decode->total==0){
						echo 'No open ESGCM tickets.';
					}
					//var_dump($result);
					//echo '<br>';
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

	$esgcmUserList = getJiraTickets($jiraUN,$jiraPW,'esgcm-all');
	//var_dump($esgcmUserList);

	for($jj=0;$jj<sizeof($esgcmUserList);$jj++){
		if( $esgcmUserList[$jj]!='none' ){
			$esgcmUserDisplayName=getJiraTickets($jiraUN,$jiraPW,'user-info',$esgcmUserList[$jj]);
			$previewEmailForm_Body="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\"><head><style>body{font-family:Gibson-Regular,Helvetica,Arial,sans-serif;font-size:14px;min-width:1100px;margin:0;padding:0;}table{border-spacing:0px;}.oddrow{background-color:#eee;}.evenrow{background-color:#fff;}.table-container{border:1px solid #aaa;border-radius:5px;background-color:#fff;width:100%;}.table-issue{width:100%;padding:10px;background-color:#fff;}.table-issue-head{width:25%;text-decoration:bold;}.table-issue-body{width:75%;}.border-bottom{border-bottom:1px solid #aaa;}.item-header{font-size:20px;color:#fff;text-align:center;background-color:#101820;border-radius:5px;padding:5px 0px;}.hotItems-issue-head{background-color:#e1523d !important;}.deployments-issue-head{background-color:#00af66 !important;}.vendorMaint-issue-head{background-color:#34657f !important;}.header-img{border:0px;margin:0px;padding:0px;}</style></head><body><table style='width:100%;padding-left:10px;padding-right:10px;'><tr><td style='background-color:#101820;border-bottom:3px solid #ffb500;padding:5px;width:100%;text-align:right;'><img class='header-img' src='http://www.conversantmedia.com/sites/all/themes/my_theme/images/conversant_logo.png' /></td></tr></table>";
			$previewEmailForm_Body.="<br><table style='padding-left:10px;padding-right:10px;'><tr><td>Good morning ".$esgcmUserDisplayName[0].",<br><br>The ESGCM tickets listed below were scheduled for deployment greater than 3 days ago.  Please update these tickets with the necesary comments and information and mark them as <u>Pass/Fail</u> in JIRA.  In the future, be sure to keep your tickets up-to-date and mark them as Pass/Fail once work has been completed.  This helps to keep reporting accurate in the ESGCM queue.</td></tr></table><br>";
			$previewEmailForm_Body.="<table style='width:100%;padding-top:5px;padding-bottom:5px;padding-left:10px;padding-right:10px;'><tr><td><table class='table-container'><tr><td><table style=\"width:100%;padding-left:10px;padding-right:10px;padding-top:10px;padding-bottom:5px;\" border=0 cellpadding=0 cellspacing=0><tr><td class='border-bottom'>Jira Ticket #</td><td class='border-bottom'>Summary</td><td class='border-bottom'>Assignee</td><td class='border-bottom'>Reporter</td><td class='border-bottom'>Deployment Date</td><td class='border-bottom'>Last Updated</td><td class='border-bottom'>Status</td></tr>";
			$previewEmailForm_Body.=getJiraTickets($jiraUN,$jiraPW,'esgcm-user',$esgcmUserList[$jj],$esgcmUserDisplayName[1]);
			$previewEmailForm_Body.="</table></td></tr></table></td></tr></table><br><table style='padding-left:10px;padding-right:10px;'><tr><td>If you have any questions about these open ESGCM tickets or the ESGCM queue workflow, please contact the Conversant NOC.<br><br>Thank you,<br><br>Conversant Network Operations Center<br><a href='mailto:noc@conversantmedia.com'>noc@conversantmedia.com</a><br>800.566.3316</td></tr></table></body></html>";

			if($_REQUEST['action']=='viewReports'){
				if($esgcmUserList[$jj]!=''){
					echo $previewEmailForm_Body; //ONLY ECHO
				}
			}
			
			if($_REQUEST['action']=='sendReports'){
				if($esgcmUserList[$jj]!=''){
					//SEND EMAIL	
					$args = array(
						'mailerTo'=>$esgcmUserList[$jj].'@conversantmedia.com',
						'mailerCC'=>'Network Operations, ',
						'mailerBCC'=>'',
						'mailerSubject'=>'[JIRA] Open ESGCM Tickets | '.$esgcmUserDisplayName[0],
						'mailerBody'=>$previewEmailForm_Body
					);
					$request = new HTTP_Request2('http://nocdash.dc.dotomi.net/php/smtpMailer.php'); //POST REQUEST TO smtpMailer.php
					$request->setMethod(HTTP_Request2::METHOD_POST);
					$request->addPostParameter($args);
					$response = $request->send();
					echo 'Email for '.$esgcmUserList[$jj].'@conversantmedia.com sent to smtpMailer<br>';
					echo 'Response: '.$response->getStatus().'<br><br>';
					//SEND EMAIL	
				}
			}
		}
	}
?>
