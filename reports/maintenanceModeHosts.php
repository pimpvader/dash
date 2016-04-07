<?PHP
	//include $path.'/php/common.php';
	$path=$_SERVER['DOCUMENT_ROOT'];
	//require_once $path.'/php/dbconn.php';
	require_once 'HTTP/Request2.php';
	//require_once $path.'/php/api/jiraCreds.php';

	function getReportData($curlUrl){
                $curl = curl_init($curlUrl);
		$userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
                curl_setopt($curl, CURLOPT_POST, FALSE);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
                curl_setopt($curl, CURLOPT_CONNECTTIMEOUT,10); //The number of seconds to wait while trying to connect.
                curl_setopt($curl, CURLOPT_USERAGENT, $userAgent); //The contents of the "User-Agent: " header to be used in a HTTP request.
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); //To follow any "Location: " header that the serversends as part of the HTTP header.
                curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE); //To automatically set the Referer: field in requests where it follows a Location: redirect.
                curl_setopt($curl, CURLOPT_TIMEOUT,20); //The maximum number of seconds to allow cURL functions to execute.
                curl_setopt($curl, CURLOPT_ENCODING,  '');
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

                $return = curl_exec($curl);
                $result = curl_getinfo($curl);

		//var_dump($return);exit;

		if($return !== false){
                        //if( $result['http_code'] == '200' ) {
                                $decode = json_decode($return);
			//} else {
                        //        echo 'Unexpected HTTP status: ' . $result['http_code'].'<br>';
                        //}
                }else{
                        echo 'Unexpected HTTP response: ' . $return.'<br>';
                }
                return $decode;
        }

	//////////// START EMAIL BODY
	$previewEmailForm_Body="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\"><head><style>body{font-family:Gibson-Regular,Helvetica,Arial,sans-serif;font-size:14px;min-width:800px;margin:0;padding:0;}a{color:#275379;text-decoration:none;}a:hover,a:focus{color:#428bca;text-decoration:underline;}table{border-spacing:0px;}.oddrow{background-color:#eee;}.evenrow{background-color:#fff;}.table-container{border:1px solid #aaa;border-radius:5px;background-color:#fff;width:100%;padding-top:0px;padding-bottom:0px;padding-left:10px;padding-right:10px;}.table-issue{width:100%;padding:10px;background-color:#fff;}.table-issue-head{width:25%;text-decoration:bold;}.table-issue-body{width:75%;}.border-bottom{border-bottom:1px solid #aaa;}.item-header{font-size:20px;color:#fff;text-align:center;background-color:#101820;border-radius:5px;padding:5px 0px;}.hotItems-issue-head{background-color:#e1523d !important;}.deployments-issue-head{background-color:#00af66 !important;}.vendorMaint-issue-head{background-color:#34657f !important;}.header-img{border:0px;margin:0px;padding:0px;}</style></head><body><table style='width:100%;padding-left:10px;padding-right:10px;'><tr><td style='background-color:#101820;border-bottom:3px solid #ffb500;padding:5px;width:100%;text-align:right;'><img class='header-img' src='http://www.conversantmedia.com/sites/all/themes/my_theme/images/conversant_logo.png' /></td></tr></table>";
	//$previewEmailForm_Body.="<br><table style='width:100%;padding-top:5px;padding-bottom:5px;padding-left:10px;padding-right:10px;'><tr><td class='item-header' style='background-color:#e1523d;'>Servers with Critical Hardware Alerts</td></tr></table><br>";
	$previewEmailForm_Body.="<table style='width:100%;padding-top:5px;padding-bottom:5px;padding-left:10px;padding-right:10px;'><tr><td><table class='table-container'><tr><td><table style=\"width:100%;padding-left:10px;padding-right:10px;padding-top:10px;padding-bottom:5px;\" border=0 cellpadding=0 cellspacing=0><tr><td class='border-bottom'>Hostname</td><td class='border-bottom'>MaintenanceMode Notes</td><td class='border-bottom' style='text-align:center;'>View Jira Tickets</td></tr>";

	$reportData = getReportData('http://nocdash.dc.dotomi.net/list.php?getReport=nodes-maintMode');
	if( count($reportData)=='0' ){ //no report data
		//$previewEmailForm_Body.="<tr><td style='text-align:center;padding-top:10px;' colspan=\"5\">No equipment is currently exhibiting a critical hardware issue.</td></tr>";
	}else{
		//var_dump($reportData);exit;
		$oddEven=0;
		foreach($reportData as $data){
			if($oddEven==1){
				$rowBackground="#ddd";
				$oddEven=0;
			}else{
				$rowBackground="white";
				$oddEven=1;
			}
			//$previewEmailForm_Body.="<td style='padding-top:3px;padding-bottom:3px;background:".$rowBackground.";'><a href='https://iris.vclk.net/ticket/results?min_insert_date=".$date->format('Y-m-d')."+05%3A00&max_insert_date=".$dateToday->format('Y-m-d')."+04%3A59&contact_queue_id=6nH2HiC&min_search_date=".$date->format('Y-m-d')."&max_search_date=".$dateToday->format('Y-m-d')."&host_name=".$row[$jj][hostname]."' target='_blank' title='View alerts for this server in Iris'>".$row[$jj][hostname]."</a></td><td style='background:".$rowBackground.";'>".$row[$jj][check][$kk]."</td><td style='background:".$rowBackground.";text-align:center;'>".$row[$jj][count][$kk]."</td></tr>";
			$previewEmailForm_Body.="<tr><td style='padding-top:3px;padding-bottom:3px;background:".$rowBackground.";'><a href='http://ord-mon102.corp.valueclick.com/Orion/NetPerfMon/NodeDetails.aspx?NetObject=N:".$data->nodeid."' target='_blank'>".$data->sysname."</a></td>";
			$previewEmailForm_Body.="<td style='background:".$rowBackground.";'>".$data->maintenancemodenotes."</td><td style='background:".$rowBackground.";text-align:center;'>";
			$previewEmailForm_Body.="[<a href='http://jira.cnvrmedia.net/issues/?jql=%22Service%20Tag%22%20~%20%22".$data->servicetag."%22' target='_blank'>Open</a>]</td></tr>";
		}
	}
        
	$previewEmailForm_Body.="</table></td></tr></table></td></tr></table>";
	$previewEmailForm_Body.="<table style='width:100%;margin:10px;'><tr><td>Conversant Network Operations Center<br><a href='mailto:noc@conversantmedia.com'>noc@conversantmedia.com</a><br>800.566.3316</td></tr></table>";
	$previewEmailForm_Body.="</body></html>";
	
	//////////// END EMAIL BODY

	if($_REQUEST['action']=='viewReports'){
		echo $previewEmailForm_Body; //ONLY ECHO
	}
	if($_REQUEST['action']=='sendReports'){
		//SEND EMAIL	
		$args = array(
			'mailerTo'=>'noc@conversantmedia.com',
			'mailerCC'=>'',
			'mailerBCC'=>'',
			'mailerSubject'=>'MaintenanceMode Hosts Report',
			'mailerBody'=>$previewEmailForm_Body
		);
		$request = new HTTP_Request2('http://nocdash.dc.dotomi.net/php/smtpMailer.php'); //POST REQUEST TO smtpMailer.php
		$request->setMethod(HTTP_Request2::METHOD_POST);
		$request->addPostParameter($args);
		$response = $request->send();
		echo 'MaintenanceMode Hosts Report sent to smtpMailer<br>';
		echo 'Response: '.$response->getStatus().'<br><br>';
		//SEND EMAIL	
	}
?>
