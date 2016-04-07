<?PHP
	//include $path.'/php/common.php';
	$path=$_SERVER['DOCUMENT_ROOT'];
	require_once $path.'/php/dbconn.php';
	require_once 'HTTP/Request2.php';
	require_once $path.'/php/api/jiraCreds.php';

	function checkJiraSvcTag($svcTag,$jiraUN,$jiraPW){
                $request = new HTTP_Request2('http://jira.cnvrmedia.net/rest/api/latest/search?jql=project%20in%20(NOC,SYSADMIN,SYSENG)%20AND%20Status%20NOT%20IN%20(%22Resolved%22,%22Closed%22)%20and%20%22Service%20Tag%22~%22'.$svcTag.'%22%20order%20by%20updated%20asc&maxResults=200', HTTP_Request2::METHOD_GET);
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

	//////////// START CRIT HARDWARE SECTION
	$previewEmailForm_Body.="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\"><head><style>body{font-family:Gibson-Regular,Helvetica,Arial,sans-serif;font-size:14px;min-width:1100px;margin:0;padding:0;}table{border-spacing:0px;}.oddrow{background-color:#eee;}.evenrow{background-color:#fff;}.table-container{border:1px solid #aaa;border-radius:5px;background-color:#fff;width:100%;padding-top:0px;padding-bottom:0px;padding-left:10px;padding-right:10px;}.table-issue{width:100%;padding:10px;background-color:#fff;}.table-issue-head{width:25%;text-decoration:bold;}.table-issue-body{width:75%;}.border-bottom{border-bottom:1px solid #aaa;}.item-header{font-size:20px;color:#fff;text-align:center;background-color:#101820;border-radius:5px;padding:5px 0px;}.hotItems-issue-head{background-color:#e1523d !important;}.deployments-issue-head{background-color:#00af66 !important;}.vendorMaint-issue-head{background-color:#34657f !important;}.header-img{border:0px;margin:0px;padding:0px;}</style></head><body><table style='width:100%;padding-left:10px;padding-right:10px;'><tr><td style='background-color:#101820;border-bottom:3px solid #ffb500;padding:5px;width:100%;text-align:right;'><img class='header-img' src='http://www.conversantmedia.com/sites/all/themes/my_theme/images/conversant_logo.png' /></td></tr></table>";
	$previewEmailForm_Body.="<br><table style='width:100%;padding-top:5px;padding-bottom:5px;padding-left:10px;padding-right:10px;'><tr><td class='item-header' style='background-color:#e1523d;'>Servers with Critical Hardware Alerts</td></tr></table><br>";
	$previewEmailForm_Body.="<table style='width:100%;padding-top:5px;padding-bottom:5px;padding-left:10px;padding-right:10px;'><tr><td><table class='table-container'><tr><td><table style=\"width:100%;padding-left:10px;padding-right:10px;padding-top:10px;padding-bottom:5px;\" border=0 cellpadding=0 cellspacing=0><tr><td class='border-bottom'>Hostname</td><td class='border-bottom'>Hardware Issues</td><td class='border-bottom'>Model</td><td class='border-bottom'>Service Tag</td><td class='border-bottom'>Open Jira Tickets</td></tr>";
       	$crit_request = new HTTP_Request2('http://nocdash.dc.dotomi.net/list.php?getReport=hw-health-critical', HTTP_Request2::METHOD_GET);
        try {
		$response = $crit_request->send();
                if (200 == $response->getStatus()) {
			$crit_response = $response->getBody();
                        $crit_decode = json_decode($crit_response);

			if( $crit_decode->length=='0' ){
				$previewEmailForm_Body.="<tr><td style='text-align:center;padding-top:10px;' colspan=\"5\">No equipment is currently exhibiting a critical hardware issue.</td></tr>";
			}else{
				if($crit_decode->length!=''){
					$previewEmailForm_Body.="<tr><td style='text-align:center;padding-top:10px;' colspan=\"5\">No equipment is currently exhibiting a critical hardware issue.</td></tr>";
				}else{
					foreach($crit_decode as $node){
						$previewEmailForm_Body.="<tr><td><a href='http://ord-mon102.corp.valueclick.com/Orion/NetPerfMon/NodeDetails.aspx?NetObject=N:".$node->nodeid."&ViewID=79' target='_blank'>".$node->hardwarename."</a></td>";
                        		        $node->sensorswithproblems=str_replace("\n","<br>",$node->sensorswithproblems);
                                		$previewEmailForm_Body.="<td style='padding-top:5px;padding-bottom:5px;'>".$node->sensorswithproblems."</td><td>".$node->model."</td><td>".$node->servicetag."</td><td>";
		                       	        $previewEmailForm_Body.=checkJiraSvcTag($node->servicetag,$jiraUN,$jiraPW);
        		               	        $previewEmailForm_Body.="</td></tr>";
					}
				}
			}
		} else {
			echo 'Unexpected HTTP status: ' . $crit_response->getStatus() . ' ' .
			$crit_response->getReasonPhrase();
		}
	} catch (HTTP_Request2_Exception $e) {
		echo 'Error: ' . $e->getMessage();
		exit();
	}
	$previewEmailForm_Body.="</table></td></tr></table></td></tr></table>";



	//////////// START DISK ISSUES SECTION
	$previewEmailForm_Body.="<br><table style='width:100%;padding-top:5px;padding-bottom:5px;padding-left:10px;padding-right:10px;'><tr><td class='item-header' style='background-color:#34657f;'>Physical Disks with Failures</td></tr></table><br>";
        $previewEmailForm_Body.="<table style='width:100%;padding-top:5px;padding-bottom:5px;padding-left:10px;padding-right:10px;'><tr><td><table class='table-container'><tr><td><table style=\"width:100%;padding-left:10px;padding-right:10px;padding-top:10px;padding-bottom:5px;\" border=0 cellpadding=0 cellspacing=0><tr><td class='border-bottom'>Hostname</td><td class='border-bottom'>Hardware Issues</td><td class='border-bottom'>Model</td><td class='border-bottom'>Service Tag</td><td class='border-bottom'>Open Jira Tickets</td></tr>";
        $warn_request = new HTTP_Request2('http://nocdash.dc.dotomi.net/list.php?getReport=hw-disks-warning', HTTP_Request2::METHOD_GET);
        try {
                $response = $warn_request->send();
                if (200 == $response->getStatus()) {
                        $crit_response = $response->getBody();
                        $crit_decode = json_decode($crit_response);

                        if( $crit_decode->length=='0' ){
                                $previewEmailForm_Body.="<tr><td style='text-align:center;padding-top:10px;' colspan=\"5\">No physical disks are currently exhibiting issues.</td></tr>";
			}else{
				foreach($crit_decode as $node){
					$previewEmailForm_Body.="<tr><td><a href='http://ord-mon102.corp.valueclick.com/Orion/NetPerfMon/NodeDetails.aspx?NetObject=N:".$node->nodeid."&ViewID=79' target='_blank'>".$node->hardwarename."</a></td>";
                        	        $node->sensorswithproblems=str_replace("\n","<br>",$node->sensorswithproblems);                                
                                        $previewEmailForm_Body.="<td style='padding-top:5px;padding-bottom:5px;'>".$node->sensorswithproblems."</td><td>".$node->model."</td><td>".$node->servicetag."</td><td>";
	                                $previewEmailForm_Body.=checkJiraSvcTag($node->servicetag,$jiraUN,$jiraPW);                            
        	                        $previewEmailForm_Body.="</td></tr>";
				}
                        }
                } else {
                        echo 'Unexpected HTTP status: ' . $crit_response->getStatus() . ' ' .
                        $crit_response->getReasonPhrase();
                }
        } catch (HTTP_Request2_Exception $e) {
                echo 'Error: ' . $e->getMessage();
                exit();
        }
        $previewEmailForm_Body.="</table></td></tr></table></td></tr></table>";


	
	//////////// START DIMM ISSUES SECTION
	$previewEmailForm_Body.="<br><table style='width:100%;padding-top:5px;padding-bottom:5px;padding-left:10px;padding-right:10px;'><tr><td class='item-header' style='background-color:#00af66;'>Memory DIMMs with Failures</td></tr></table><br>";
        $previewEmailForm_Body.="<table style='width:100%;padding-top:5px;padding-bottom:5px;padding-left:10px;padding-right:10px;'><tr><td><table class='table-container'><tr><td><table style=\"width:100%;padding-left:10px;padding-right:10px;padding-top:10px;padding-bottom:5px;\" border=0 cellpadding=0 cellspacing=0><tr><td class='border-bottom'>Hostname</td><td class='border-bottom'>Hardware Issues</td><td class='border-bottom'>Model</td><td class='border-bottom'>Service Tag</td><td class='border-bottom'>Open Jira Tickets</td></tr>";
        $warn_request = new HTTP_Request2('http://nocdash.dc.dotomi.net/list.php?getReport=hw-dimm-warning', HTTP_Request2::METHOD_GET);
        try {
                $response = $warn_request->send();
                if (200 == $response->getStatus()) {
                        $crit_response = $response->getBody();
                        $crit_decode = json_decode($crit_response);

                        if( $crit_decode->length=='0' ){
                                $previewEmailForm_Body.="<tr><td style='text-align:center;padding-top:10px;' colspan=\"5\">No memory DIMMs are currently exhibiting issues.</td></tr>";
			}else{
				foreach($crit_decode as $node){
					$previewEmailForm_Body.="<tr><td><a href='http://ord-mon102.corp.valueclick.com/Orion/NetPerfMon/NodeDetails.aspx?NetObject=N:".$node->nodeid."&ViewID=79' target='_blank'>".$node->hardwarename."</a></td>";
                        	        $node->sensorswithproblems=str_replace("\n","<br>",$node->sensorswithproblems);                                
                                        $previewEmailForm_Body.="<td style='padding-top:5px;padding-bottom:5px;'>".$node->sensorswithproblems."</td><td>".$node->model."</td><td>".$node->servicetag."</td><td>";
	                                $previewEmailForm_Body.=checkJiraSvcTag($node->servicetag,$jiraUN,$jiraPW);                            
        	                        $previewEmailForm_Body.="</td></tr>";
				}
                        }
                } else {
                        echo 'Unexpected HTTP status: ' . $crit_response->getStatus() . ' ' .
                        $crit_response->getReasonPhrase();
                }
        } catch (HTTP_Request2_Exception $e) {
                echo 'Error: ' . $e->getMessage();
                exit();
        }
        $previewEmailForm_Body.="</table></td></tr></table></td></tr></table>";
	$previewEmailForm_Body.="<table style='width:100%;margin:10px;'><tr><td>Please contact the Conversant Network Operations Center with any questions.<br><br>Thank you,<br><br>Conversant Network Operations Center<br><a href='mailto:noc@conversantmedia.com'>noc@conversantmedia.com</a><br>800.566.3316</td></tr></table>";
	$previewEmailForm_Body.="</body></html>";

	if($_REQUEST['action']=='viewReports'){
		echo $previewEmailForm_Body; //ONLY ECHO
	}
		
	if($_REQUEST['action']=='sendReports'){
		//SEND EMAIL	
		$args = array(
			'mailerTo'=>'noc@conversantmedia.com',
			'mailerCC'=>'',
			'mailerBCC'=>'',
			'mailerSubject'=>'Daily Hardware Report',
			'mailerBody'=>$previewEmailForm_Body
		);
		$request = new HTTP_Request2('http://nocdash.dc.dotomi.net/php/smtpMailer.php'); //POST REQUEST TO smtpMailer.php
		$request->setMethod(HTTP_Request2::METHOD_POST);
		$request->addPostParameter($args);
		$response = $request->send();
		//echo 'Email for '.$changeUserList[$jj].'@conversantmedia.com sent to smtpMailer<br>';
		echo 'Daily Hardware Report sent to smtpMailer<br>';
		echo 'Response: '.$response->getStatus().'<br><br>';
		//SEND EMAIL	
	}
?>
