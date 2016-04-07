<?PHP
	//include '../php/common.php';
	$path=$_SERVER['DOCUMENT_ROOT'];
	require_once 'HTTP/Request2.php';
	//require_once $path.'/php/api/jiraCreds.php';
	//require_once $path.'/php/db-swinds.php';

	/*
	function checkJiraSvcTag($svcTag,$jiraUN,$jiraPW){
                $request = new HTTP_Request2('http://jira.cnvrmedia.net/rest/api/latest/search?jql=project%20%3D%20NOC%20AND%20Status%20NOT%20IN%20(%22Resolved%22,%22Closed%22)%20and%20%22Service%20Tag%22~%22'.$svcTag.'%22%20order%20by%20updated%20asc&maxResults=200', HTTP_Request2::METHOD_GET);
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
	*/

	//$recipientTimeZone = "America/New_York";
	$recipientTimeZone = "America/Chicago";

	//////////// START UPCOMING VENDOR MAINT SECTION
	$previewEmailForm_Body.="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\"><head><style>body{font-family:Gibson-Regular,Helvetica,Arial,sans-serif;font-size:14px;min-width:1100px;margin:0;padding:0;}table{border-spacing:0px;}.oddrow{background-color:#eee;}.evenrow{background-color:#fff;}.table-container{border:1px solid #aaa;border-radius:5px;background-color:#fff;width:100%;padding-top:0px;padding-bottom:0px;padding-left:10px;padding-right:10px;}.table-issue{width:100%;padding:10px;background-color:#fff;}.table-issue-head{width:25%;text-decoration:bold;}.table-issue-body{width:75%;}.border-bottom{border-bottom:1px solid #aaa;}.item-header{font-size:20px;color:#fff;text-align:center;background-color:#101820;border-radius:5px;padding:5px 0px;}.hotItems-issue-head{background-color:#e1523d !important;}.deployments-issue-head{background-color:#00af66 !important;}.vendorMaint-issue-head{background-color:#34657f !important;}.header-img{border:0px;margin:0px;padding:0px;}</style></head><body><table style='width:100%;padding-left:10px;padding-right:10px;'><tr><td style='background-color:#101820;border-bottom:3px solid #ffb500;padding:5px;width:100%;text-align:right;'><img class='header-img' src='http://www.conversantmedia.com/sites/all/themes/my_theme/images/conversant_logo.png' /></td></tr></table>";
	$previewEmailForm_Body.="<br><table style='width:100%;padding-top:5px;padding-bottom:5px;padding-left:10px;padding-right:10px;'><tr><td class='item-header' style='background-color:#00af66;'>Upcoming Vendor Maintenance</td></tr></table><br>";
	$previewEmailForm_Body.="<table style='width:100%;padding-top:5px;padding-bottom:5px;padding-left:10px;padding-right:10px;'><tr><td><table class='table-container'><tr><td><table style=\"width:100%;padding-left:10px;padding-right:10px;padding-top:10px;padding-bottom:5px;\" border=0 cellpadding=0 cellspacing=0><tr><td class='border-bottom'>Vendor</td><td class='border-bottom'>Start Time</td><td class='border-bottom'>End Time</td><td class='border-bottom'>Duration</td><td class='border-bottom'>Circuit ID</td><td class='border-bottom'>Affected Sites</td><td class='border-bottom'>Vendor Ticket #</td></tr>";
       	$upcomingMaint_request = new HTTP_Request2('http://nocdash.dc.dotomi.net/list.php?getVendorMaintEmail=upcoming', HTTP_Request2::METHOD_GET);
        try {
		$response = $upcomingMaint_request->send();
                if (200 == $response->getStatus()) {
			$upcomingMaint_response = $response->getBody();
                        $upcomingMaint_decode = json_decode($upcomingMaint_response);

			$upcomingMaint_length = count($upcomingMaint_decode);
			if( $upcomingMaint_decode->length=='0' ){
				$previewEmailForm_Body.="<tr><td style='text-align:center;padding-top:10px;' colspan=\"7\">No upcoming vendor maintenance commencing in the next 12 hours.</td></tr>";
			}else{
				$jj = 0;
				foreach($upcomingMaint_decode as $maint){
					$previewEmailForm_Body.="<tr><td style='padding-top:5px;padding-bottom:5px;'><a href='http://nocdash.dc.dotomi.net/maintenance.php' target='_blank'>".$maint->Provider."</a></td><td>";
					$date = new DateTime($maint->Work_Start);
					$date->setTimezone(new DateTimeZone($recipientTimeZone));
					$previewEmailForm_Body.=$date->format('m/d/Y H:i:s');
					
					$previewEmailForm_Body.="</td><td>";

					$date = new DateTime($maint->Work_End);
					$date->setTimezone(new DateTimeZone($recipientTimeZone));
					$previewEmailForm_Body.=$date->format('m/d/Y H:i:s');

					$previewEmailForm_Body.="</td><td>".$maint->Duration."</td><td>".$maint->CKT_ID."</td><td>".$maint->Affected_Sites."</td><td>".$maint->Provider_Ticket_Num."</td></tr><tr><td colspan=7>".$maint->Work_Description."</td></tr>";
					$jj++;
					if($jj < $upcomingMaint_length){
						$previewEmailForm_Body.="<tr><td style='text-align:center;' colspan=7>- - -</td></tr>";
					}
				}
			}
		} else {
			echo 'Unexpected HTTP status: ' . $upcomingMaint_response->getStatus() . ' ' .
			$upcomingMaint_response->getReasonPhrase();
		}
	} catch (HTTP_Request2_Exception $e) {
		echo 'Error: ' . $e->getMessage();
		exit();
	}
	$previewEmailForm_Body.="</table></td></tr></table></td></tr></table>";

	
	//////////// START ONGOING VENDOR MAINT SECTION
	$previewEmailForm_Body.="<br><table style='width:100%;padding-top:5px;padding-bottom:5px;padding-left:10px;padding-right:10px;'><tr><td class='item-header' style='background-color:#34657f;'>Ongoing Vendor Maintenance</td></tr></table><br>";
        $previewEmailForm_Body.="<table style='width:100%;padding-top:5px;padding-bottom:5px;padding-left:10px;padding-right:10px;'><tr><td><table class='table-container'><tr><td><table style=\"width:100%;padding-left:10px;padding-right:10px;padding-top:10px;padding-bottom:5px;\" border=0 cellpadding=0 cellspacing=0><tr><td class='border-bottom'>Vendor</td><td class='border-bottom'>Start Time</td><td class='border-bottom'>End Time</td><td class='border-bottom'>Duration</td><td class='border-bottom'>Circuit ID</td><td class='border-bottom'>Affected Sites</td><td class='border-bottom'>Vendor Ticket #</td></tr>";
        
       	$ongoingMaint_request = new HTTP_Request2('http://nocdash.dc.dotomi.net/list.php?getVendorMaintEmail=ongoing', HTTP_Request2::METHOD_GET);
        try {
		$response = $ongoingMaint_request->send();
                if (200 == $response->getStatus()) {
			$ongoingMaint_response = $response->getBody();
                        $ongoingMaint_decode = json_decode($ongoingMaint_response);

			$ongoingMaint_length = count($ongoingMaint_decode);
			if( $ongoingMaint_decode->length=='0' ){
				$previewEmailForm_Body.="<tr><td style='text-align:center;padding-top:10px;' colspan=\"7\">No ongoing vendor maintenance occurring during the next 12 hours.</td></tr>";
			}else{
				$jj = 0;
				foreach($ongoingMaint_decode as $maint){
					$previewEmailForm_Body.="<tr><td style='padding-top:5px;padding-bottom:5px;'><a href='http://nocdash.dc.dotomi.net/maintenance.php' target='_blank'>".$maint->Provider."</a></td><td>";
					$date = new DateTime($maint->Work_Start);
					$date->setTimezone(new DateTimeZone($recipientTimeZone));
					$previewEmailForm_Body.=$date->format('m/d/Y H:i:s');
					
					$previewEmailForm_Body.="</td><td>";

					$date = new DateTime($maint->Work_End);
					$date->setTimezone(new DateTimeZone($recipientTimeZone));
					$previewEmailForm_Body.=$date->format('m/d/Y H:i:s');

					$previewEmailForm_Body.="</td><td>".$maint->Duration."</td><td>".$maint->CKT_ID."</td><td>".$maint->Affected_Sites."</td><td>".$maint->Provider_Ticket_Num."</td></tr><tr><td colspan=7>".$maint->Work_Description."</td></tr>";
					$jj++;
					if($jj < $ongoingMaint_length){
						$previewEmailForm_Body.="<tr><td style='text-align:center;' colspan=7>- - -</td></tr>";
					}
				}
			}
		} else {
			echo 'Unexpected HTTP status: ' . $ongoingMaint_response->getStatus() . ' ' .
			$ongoingMaint_response->getReasonPhrase();
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
			'mailerSubject'=>'Upcoming Vendor Maintenance',
			'mailerBody'=>$previewEmailForm_Body
		);
		$request = new HTTP_Request2('http://nocdash.dc.dotomi.net/php/smtpMailer.php'); //POST REQUEST TO smtpMailer.php
		$request->setMethod(HTTP_Request2::METHOD_POST);
		$request->addPostParameter($args);
		$response = $request->send();
		//echo 'Email for '.$changeUserList[$jj].'@conversantmedia.com sent to smtpMailer<br>';
		echo 'Upcoming vendor maintenance email sent to smtpMailer<br>';
		echo 'Response: '.$response->getStatus().'<br><br>';
		//SEND EMAIL	
	}
?>
