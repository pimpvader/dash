<?php
	$path = $_SERVER['DOCUMENT_ROOT'];
	require_once $path.'/php/dbconn.php';

	session_start();
	
	function getFiresDash() { //FiresDash is displayed on the NocDash site
		$db_connection_nocdash = new db_nocdash();
		$result = $db_connection_nocdash -> query("SELECT eventID,eventType,eventDesc,eventStart,est_duration,eventLastModified,lastModifiedBy FROM Noc.fireBoard WHERE eventActive=1");
		$rows = array();
		while( $row = $result->fetch_assoc() ) {
			$rows[] = $row;
		}
		if($result->num_rows > 0) {
			echo json_encode($rows);
		}else{
			echo '{"length":"0","results":""}';
		}
		//echo phpinfo();
	}

	function getMaintDash($maintLength) {
		$db_connection_nocdash = new db_nocdash();
		if($maintLength=='24h'){$query = "SELECT * from Noc.maintenance WHERE ((Work_Start >= NOW() AND Work_Start <= DATE_ADD(NOW(),INTERVAL 1 DAY)) OR (Work_Start <= NOW() AND Work_End >= NOW())) AND Cancelled!=1 and Completed!=1";}
		if($maintLength=='7d'){$query = "SELECT * from Noc.maintenance WHERE ((Work_Start >= NOW() AND Work_Start <= DATE_ADD(NOW(),INTERVAL 7 DAY)) OR (Work_Start <= NOW() AND Work_End >= NOW())) AND Cancelled!=1 and Completed!=1";}
		if($maintLength=='30d'){$query = "SELECT * from Noc.maintenance WHERE ((Work_Start >= NOW() AND Work_Start <= DATE_ADD(NOW(),INTERVAL 30 DAY)) OR (Work_Start <= NOW() AND Work_End >= NOW())) AND Cancelled!=1 and Completed!=1";}
		if($maintLength=='60d'){$query = "SELECT * from Noc.maintenance WHERE ((Work_Start >= NOW() AND Work_Start <= DATE_ADD(NOW(),INTERVAL 60 DAY)) OR (Work_Start <= NOW() AND Work_End >= NOW())) AND Cancelled!=1 and Completed!=1";}
		if($maintLength=='archive'){$query = "SELECT * from Noc.maintenance WHERE (Work_End <= NOW() AND Work_End >= DATE_SUB(NOW(),INTERVAL 30 DAY)) ORDER BY Work_End DESC";}
		$result = $db_connection_nocdash -> query($query);
		$rows = array();
		while( $row = $result->fetch_assoc() ) {
			$rows[] = $row;
		}
		if($result->num_rows > 0) {
			echo json_encode($rows);
		}else{
			echo '{"length":"0","results":""}';
		}
	}

	function getFooter($footerType){
		$db_connection_nocdash = new db_nocdash();
		if($footerType=='ganglia'){$query = "SELECT * FROM Noc.gangliaAlerts WHERE alertActive=1";}
		if($footerType=='deploy'){$query = "SELECT * FROM Noc.fireBoard WHERE eventType='deploy' AND eventActive=1";}
		$result = $db_connection_nocdash -> query("SELECT eventID,eventType,eventDesc,eventStart,est_duration,eventLastModified,lastModifiedBy FROM Noc.fireBoard WHERE eventActive=1");
		$rows = array();
		while( $row = $result->fetch_assoc() ) {
			$rows[] = $row;
		}
		if($result->num_rows > 0) {
			echo json_encode($rows);
		}else{
			echo '{"length":"0","results":""}';
		}
	}

	function getFireBoard() { //FireBoard is displayed on the NocWall screen
		$db_connection_nocdash = new db_nocdash();
		$result = $db_connection_nocdash -> query("SELECT eventID,eventType,eventDesc,eventStart,est_duration FROM Noc.fireBoard WHERE eventActive=1");
		$rows = array();
		while( $row = $result->fetch_assoc() ) {
			$rows[] = $row;
		}
		if($result->num_rows > 0) {
			echo json_encode($rows);
		}else{
			echo '{"length":"0","results":""}';
		}
	}

	function getGangliaAlerts($gangliaView) {
		$db_connection_nocdash = new db_nocdash();
		if($gangliaView=='default'){ $query = "SELECT Noc.gangliaAlerts.entry,Noc.gangliaAlerts.highLow,Noc.gangliaAlerts.checkType,Noc.gangliaAlerts.hostname,Noc.gangliaAlerts.threshold,Noc.gangliaAlerts.absolute,Noc.gangliaAlerts.alertTimestamp,Noc.gangliaAlerts.alertID,Noc.gangliaAlerts.snoozeUntil,Noc.SentMail.sentBy,Noc.SentMail.sentDate FROM Noc.gangliaAlerts LEFT JOIN Noc.SentMail USING (mailID) WHERE Noc.gangliaAlerts.alertActive=1 ORDER BY Noc.SentMail.sentDate"; }
		if($gangliaView=='nocwall'){ $query = "SELECT entry,highLow,checkType,hostname,threshold,absolute,alertTimestamp,alertID FROM Noc.gangliaAlerts WHERE snoozeUntil IS NULL AND alertActive=1 ORDER BY checkType"; }
		$result = $db_connection_nocdash -> query($query);
		$rows = array();
		while( $row = $result->fetch_assoc() ) {
			$rows[] = $row;
		}
		if($result->num_rows > 0) {
			echo json_encode($rows);
		}else{
			echo '{"length":"0","results":""}';
		}
        }

	function getIrisAlerts(){
                $curl = curl_init('https://iris.vclk.net/ticket/results_json?insert_date=&min_insert_date=&max_insert_date=&ticket_number=&ticket_status=OPEN&host_name=&host_group_name=&description=&contact_queue_id=6nH2HiC&priority_operator=&priority_no=&search_date=&min_search_date=&max_search_date=&relative_start=this-month&relative_end=');
		$userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
                curl_setopt($curl, CURLOPT_POST, FALSE);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
                curl_setopt($curl, CURLOPT_CONNECTTIMEOUT,2); //The number of seconds to wait while trying to connect.
                curl_setopt($curl, CURLOPT_USERAGENT, $userAgent); //The contents of the "User-Agent: " header to be used in a HTTP request.
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); //To follow any "Location: " header that the serversends as part of the HTTP header.
                curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE); //To automatically set the Referer: field in requests where it follows a Location: redirect.
                curl_setopt($curl, CURLOPT_TIMEOUT,5); //The maximum number of seconds to allow cURL functions to execute.
                curl_setopt($curl, CURLOPT_ENCODING,  '');
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

                $return = curl_exec($curl);
                $result = curl_getinfo($curl);

		if($return !== false){
			$tmpIris = json_decode($return);
			$tmpIris = $tmpIris->data;
			if( count($tmpIris) > 0) {
				echo json_encode($tmpIris);
				//var_export($tmpIris);
			}else{
				echo '{"length":"0","results":""}';
			}
                }else{
                        echo 'Unexpected HTTP response: ' . $return.'<br>';
                }
        }


	function getSwindsData(){
                $swindsUN = "corp\\".$_SESSION['username'];
                $swindsPW = $_SESSION['password'];

		$userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
                $curl = curl_init('https://ord-swdb101.corp.valueclick.com:17778/SolarWinds/InformationService/v3/Json/Query?query=SELECT+nodes.sysname+FROM+orion.nodes+WHERE+nodes.sysname+LIKE+%27%rtr%%27+OR+nodes.sysname+LIKE+%27%cor%%27+AND+nodes.status=%272%27');
                curl_setopt($curl, CURLOPT_POST, FALSE);
                curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($curl, CURLOPT_USERPWD, "$swindsUN:$swindsPW");
                curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
                curl_setopt($curl, CURLOPT_CONNECTTIMEOUT,30); //The number of seconds to wait while trying to connect.
                curl_setopt($curl, CURLOPT_USERAGENT, $userAgent); //The contents of the "User-Agent: " header to be used in a HTTP request.
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); //To follow any "Location: " header that the serversends as part of the HTTP header.
                curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE); //To automatically set the Referer: field in requests where it follows a Location: redirect.
                curl_setopt($curl, CURLOPT_TIMEOUT, 60); //The maximum number of seconds to allow cURL functions to execute.
                curl_setopt($curl, CURLOPT_ENCODING,  '');
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

                $return = curl_exec($curl);
                $result = curl_getinfo($curl);

                if($return !== false){
                        if( $result['http_code'] == '200' ) {
                                $decode = json_decode($return);
				$resultCount=0;
				$resultList='';

                                foreach($decode->results as $result){
					//echo $result->sysname."<br>";
					$resultCount++;
					$resultList = $resultList.$result->sysname.",";
                                }
				$resultList = explode(',',$resultList);
				echo '<span title="'.$resultList[0].'\n'.$resultList[1].'">'.$resultCount.'</span>';
			} else {
				echo 'Unexpected HTTP status: ' . $result['http_code'].'<br>';
			}
		}else{
			echo 'Unexpected HTTP response: ' . $return.'<br>';
		}
	}

	function getJiraQueue($j) {
		$db_connection_nocdash = new db_nocdash();
		if($j=='jira-all'){
	                $query = "SELECT * FROM NocDashCache.jiraQueues WHERE issueStatus NOT IN ('Closed','Resolved','Done','Complete')";
		}else if($j=='jira-change'){
	                $query = "SELECT * FROM NocDashCache.jiraQueues WHERE queue='change' and issueStatus NOT IN ('Closed','Resolved','Done','Complete') ORDER BY deployDate ASC";
		}else if($j=='jira-esgcm'){
	                $query = "SELECT * FROM NocDashCache.jiraQueues WHERE queue='esgcm' and issueStatus NOT IN ('Closed','Resolved','Done','Complete') ORDER BY deployDate ASC";
		}else if($j=='jira-noc-all'){
	                $query = "SELECT * FROM NocDashCache.jiraQueues WHERE queue='noc' and issueStatus NOT IN ('Closed','Resolved','Done','Complete') ORDER BY issueUpdated DESC";
		}else if($j=='jira-noc-30'){
	                $query = "SELECT * FROM NocDashCache.jiraQueues WHERE queue='noc' and issueStatus NOT IN ('Closed','Resolved','Done','Complete') AND issueUpdated >= NOW()-INTERVAL 30 DAY ORDER BY issueUpdated DESC";
		}else if($j=='jira-noc-old'){
	                $query = "SELECT * FROM NocDashCache.jiraQueues WHERE queue='noc' and issueStatus NOT IN ('Closed','Resolved','Done','Complete') AND issueUpdated <= NOW()-INTERVAL 30 DAY";
		}else if($j=='jira-monitor-all'){
	                $query = "SELECT * FROM NocDashCache.jiraQueues WHERE queue='monitor' ORDER BY issueUpdated DESC";
		}else if($j=='jira-netops-all'){
	                $query = "SELECT * FROM NocDashCache.jiraQueues WHERE queue='netops' ORDER BY issueUpdated DESC";
		}else if($j=='jira-netops-30'){
	                $query = "SELECT * FROM NocDashCache.jiraQueues WHERE queue='netops' AND issueUpdated >= NOW()-INTERVAL 30 DAY ORDER BY issueUpdated DESC";
		}else if($j=='jira-netops-old'){
	                $query = "SELECT * FROM NocDashCache.jiraQueues WHERE queue='netops' AND issueUpdated <= NOW()-INTERVAL 30 DAY";
		}else if($j=='jira-myIssues'){
	                $query = "SELECT * FROM NocDashCache.jiraQueues WHERE (issueAssigneeUsername='".$_SESSION['username']."' OR issueReporterUsername='".$_SESSION['username']."') and issueStatus NOT IN ('Closed','Resolved','Done','Complete') ORDER BY issueUpdated DESC";
		}else if($j=='jira-change-calendar'){
			$query = "SELECT issueNum as id,issueSummary as title,deployDate as start,'UTC' as timezone FROM NocDashCache.jiraQueues WHERE queue='change'";
		}else if($j=='jira-netops-calendar'){
			$query = "SELECT issueNum as id,issueSummary as title,maintStart as start,maintEnd as end,'UTC' as timezone FROM NocDashCache.jiraQueues WHERE queue='netops'";
		}else{
			echo 'YOU SHOULD NOT SEE THIS!!!';
		}
		$result = $db_connection_nocdash -> query($query);
		$rows = array();
		while( $row = $result->fetch_assoc() ) {
			$rows[] = $row;
		}
		if($result->num_rows > 0) {
			echo json_encode($rows);
		}else{
			echo '{"length":"0","results":""}';
		}
        }

	function getActivity($activityLength){
		$db_connection_nocdash = new db_nocdash();
		if($activityLength=='12h'){$query = "SELECT JiraLastModified AS Date,'jira' AS Source,JiraSummary AS Event,'' AS eventID,JiraStatus,JiraAssignee,'' AS createdBy,'' AS eventType,JiraLastCommentAuthor,JiraLastComment,JiraNumber,'' AS eventActive,'' AS closedBy,'' AS sentBy,'' AS sentTo,'' AS sentCC,'' AS sentBCC,'' AS mailID FROM Noc.Turnover WHERE JiraLastModified > NOW()-INTERVAL 12 HOUR UNION SELECT eventLastModified,'fireBoard',eventDesc,eventID,'','',createdBy,eventType,'','','',eventActive,closedBy,'','','','','' FROM Noc.fireBoard WHERE eventLastModified > NOW()-INTERVAL 12 HOUR UNION SELECT sentDate,'sentmail',subject,'','','','','','','','','','',sentBy,sentTo,sentCC,sentBCC,mailID FROM Noc.SentMail WHERE sentDate > NOW()-INTERVAL 12 HOUR ORDER BY Date DESC";}
		if($activityLength=='24h'){$query = "SELECT JiraLastModified AS Date,'jira' AS Source,JiraSummary AS Event,'' AS eventID,JiraStatus,JiraAssignee,'' AS createdBy,'' AS eventType,JiraLastCommentAuthor,JiraLastComment,JiraNumber,'' AS eventActive,'' AS closedBy,'' AS sentBy,'' AS sentTo,'' AS sentCC,'' AS sentBCC,'' AS mailID FROM Noc.Turnover WHERE JiraLastModified > NOW()-INTERVAL 24 HOUR UNION SELECT eventLastModified,'fireBoard',eventDesc,eventID,'','',createdBy,eventType,'','','',eventActive,closedBy,'','','','','' FROM Noc.fireBoard WHERE eventLastModified > NOW()-INTERVAL 24 HOUR UNION SELECT sentDate,'sentmail',subject,'','','','','','','','','','',sentBy,sentTo,sentCC,sentBCC,mailID FROM Noc.SentMail WHERE sentDate > NOW()-INTERVAL 24 HOUR ORDER BY Date DESC";}
		if($activityLength=='7d'){$query = "SELECT JiraLastModified AS Date,'jira' AS Source,JiraSummary AS Event,'' AS eventID,JiraStatus,JiraAssignee,'' AS createdBy,'' AS eventType,JiraLastCommentAuthor,JiraLastComment,JiraNumber,'' AS eventActive,'' AS closedBy,'' AS sentBy,'' AS sentTo,'' AS sentCC,'' AS sentBCC,'' AS mailID FROM Noc.Turnover WHERE JiraLastModified > NOW()-INTERVAL 7 DAY UNION SELECT eventLastModified,'fireBoard',eventDesc,eventID,'','',createdBy,eventType,'','','',eventActive,closedBy,'','','','','' FROM Noc.fireBoard WHERE eventLastModified > NOW()-INTERVAL 7 DAY UNION SELECT sentDate,'sentmail',subject,'','','','','','','','','','',sentBy,sentTo,sentCC,sentBCC,mailID FROM Noc.SentMail WHERE sentDate > NOW()-INTERVAL 7 DAY ORDER BY Date DESC";}
		if($activityLength=='jira'){$query = "SELECT issueNum,issueType,issueSummary from NocDashCache.jiraQueues WHERE queue='NETOPS' AND issueType!='New Server Request' AND issueType!='Decommission Server'";}
		
		$result = $db_connection_nocdash -> query($query);
		$rows = array();
		while( $row = $result->fetch_assoc() ) {
			$rows[] = $row;
		}
		if($result->num_rows > 0) {
			echo json_encode($rows);
		}else{
			echo '{"length":"0","results":""}';
		}
	}

	function getEmails(){
		$db_connection_nocdash = new db_nocdash();
		$query = "SELECT mailID,sentDate,subject,sentBy,sentTo,sentCC,sentBCC,body FROM Noc.SentMail WHERE sentDate >= NOW()-INTERVAL 30 DAY ORDER BY sentDate DESC";
		$result = $db_connection_nocdash -> query($query);
		$rows = array();
		while( $row = $result->fetch_assoc() ) {
			$rows[] = $row;
		}
		if($result->num_rows > 0) {
			echo json_encode($rows);
		}else{
			echo '{"length":"0","results":""}';
		}
	}

	function generateReport($reportType,$jiraUN,$jiraPW){
                switch($reportType){
                        case 'jira-weeklyissues-noc':
				$url = 'http://jira.cnvrmedia.net/rest/api/latest/search?jql=project%20%3D%20CHANGE%20AND%20status%20in%20(approved%2C%22needs%20approval%22%2C%22in%20progress%22)%20AND%20(%22Deployment%20Date%22%20%3E=%20startOfDay()%20and%20%22Deployment%20Date%22%20%3C=%20endOfDay())%20order%20by%20%22Deployment%20Date%22%20DESC&maxResults=150&fields=summary,reporter,assignee,created,updated,status,issuetype,customfield_11120,customfield_12459';
                                break;
                        case 'change':
                                $url = 'http://jira.cnvrmedia.net/rest/api/latest/search?jql=project%20%3D%20CHANGE%20AND%20status%20in%20(approved%2C"needs%20approval"%2C"in%20progress")%20AND%20%22Deployment%20Date%22%20%3E=%20startOfDay(-7)%20order%20by%20%22Deployment%20Date%22%20DESC&maxResults=150&fields=summary,assignee,created,updated,status,issuetype,customfield_11120,customfield_12459';
                                break;
                        case 'esgcm':
                                $url = 'http://jira.cnvrmedia.net/rest/api/latest/search?jql=project=ESGCM%20AND%20status%20in%20(%22Pending%20Approval%22,Approved,%22In%20Progress%22)%20AND%20%22Date%20%26%20Time%22%20%3E=%20startOfDay(-7)%20ORDER%20BY%20%22Date%20%26%20Time%22%20DESC&maxResults=150&fields=summary,assignee,created,updated,status,issuetype,customfield_13728';
                                break;
                }

		$userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_POST, FALSE);
                curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($curl, CURLOPT_USERPWD, "$jiraUN:$jiraPW");
                curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
                curl_setopt($curl, CURLOPT_CONNECTTIMEOUT,30); //The number of seconds to wait while trying to connect.
                curl_setopt($curl, CURLOPT_USERAGENT, $userAgent); //The contents of the "User-Agent: " header to be used in a HTTP request.
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); //To follow any "Location: " header that the serversends as part of the HTTP header.
                curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE); //To automatically set the Referer: field in requests where it follows a Location: redirect.
                curl_setopt($curl, CURLOPT_TIMEOUT, 60); //The maximum number of seconds to allow cURL functions to execute.
                curl_setopt($curl, CURLOPT_ENCODING,  '');
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

                $return = curl_exec($curl);
                $result = curl_getinfo($curl);

                if($return !== false){
                        if( $result['http_code'] == '200' ) {
                                $decode = json_decode($return);
                                foreach($decode->issues as $issue){
                                        switch($jiraQueue){
                                                case 'netops':
							
							break;
                                                case 'change':
							
							break;
                                                case 'it':
							
							break;
                                        }
                                }
			} else {
				echo 'Unexpected HTTP status: ' . $result['http_code'].'<br>';
			}
		}else{
			echo 'Unexpected HTTP response: ' . $return.'<br>';
		}
	}

	function getVendorMaint($vendorTicket){
		$db_connection_nocdash = new db_nocdash();
		$result = $db_connection_nocdash -> query("SELECT * FROM Noc.maintenance WHERE Provider_Ticket_Num='".$vendorTicket."'");
		$rows = array();
		while( $row = $result->fetch_assoc() ) {
			$rows[] = $row;
		}
		if($result->num_rows > 0) {
			echo json_encode($rows);
		}else{
			echo '{"length":"0","results":""}';
		}
	}

	function getVendorMaintEmail($maintStatus) {
		$db_connection_nocdash = new db_nocdash();
		if($maintStatus=='upcoming'){$query = "SELECT * from Noc.maintenance WHERE ( Work_Start >= NOW() AND Work_Start <= DATE_ADD(NOW(),INTERVAL 13 HOUR) ) AND Cancelled!=1 AND Completed!=1";}
		if($maintStatus=='ongoing'){$query = "SELECT * from Noc.maintenance WHERE (Work_Start <= NOW() AND Work_End >= NOW()) AND Cancelled!=1 AND Completed!=1";}
		$result = $db_connection_nocdash -> query($query);
		$rows = array();
		while( $row = $result->fetch_assoc() ) {
			$rows[] = $row;
		}
		if($result->num_rows > 0) {
			echo json_encode($rows);
		}else{
			echo '{"length":"0","results":""}';
		}
	}

	function getReportData($reportType){
		require 'php/dbconn-swinds.php';
		if($reportType=='hw-health-critical'){
			$query = "select nodeid,hardwarename,hardwarestatus,sensorswithproblems,manufacturer,model,servicetag from APM_HardwareAlertData where hardwarestatus='critical'";
		}
		if($reportType=='hw-health-undefined'){
			$query = "select nodeid,hardwarename,hardwarestatus,hardwarestatusdescription,sensorswithproblems,manufacturer,model,servicetag from APM_HardwareAlertData where (hardwarestatus='unknown' OR hardwarestatus='undefined')";
		}
		if($reportType=='hw-disks-warning'){
			$query = "select nodeid,hardwarename,hardwarestatus,sensorswithproblems,manufacturer,model,servicetag from APM_HardwareAlertData where (hardwarestatus='warning' or hardwarestatus='critical') and sensorswithproblems like \"%physical disk%\"";
		}
		if($reportType=='hw-dimm-warning'){
			$query = "select nodeid,hardwarename,hardwarestatus,sensorswithproblems,manufacturer,model,servicetag from APM_HardwareAlertData where (hardwarestatus='warning' or hardwarestatus='critical') and sensorswithproblems like \"%DIMM%\"";
		}
		if($reportType=='nodes-maintMode'){
			$query = "select nodes.nodeid,nodes.sysname,nodes.maintenancemodenotes,apm_hardwareinfo.model,apm_hardwareinfo.servicetag from nodes,apm_hardwareinfo where nodes.nodeid=apm_hardwareinfo.nodeid and nodes.maintenancemode=1 order by nodes.sysname";
		}
		
		$result = mssql_query($query);
		if(!is_bool($result)){
			if(mssql_num_rows($result) > 0){
				while($rec = mssql_fetch_array($result)){ //while true
					$arr[] = $rec;
				}
				$jsonresult = json_encode($arr);
				echo $jsonresult;
			}else{
                        	echo '{"length":"0","results":""}';
			}
                }else{
                        echo '{"length":"0","results":""}';
		}
		mssql_close();
	}

	function getDevList($dev,$devDC,$devErrors){
		require 'php/dbconn-swinds.php';
		if($dev=='swt'){
			$query = "SELECT nodeid FROM nodes WHERE (sysname LIKE '%swt%' OR sysname LIKE '%poe%')";
		}
		if($dev=='rtr'){
			$query = "SELECT nodeid FROM nodes WHERE (sysname LIKE '%rtr%' OR sysname LIKE '%cor%')";
		}
		if($dev=='fwl'){
			$query = "SELECT nodeid FROM nodes WHERE sysname LIKE '%fwl%'";
		}
		if($dev=='linux'){
			$query = "SELECT nodeid FROM nodes WHERE description LIKE '%linux%'";
		}
		if($dev=='win'){
			$query = "SELECT nodeid FROM nodes WHERE vendor LIKE '%windows%'";
		}
	
		if($devDC!='total'){
			$query.=" AND sysname LIKE \"%".$devDC."%\"";
		}
		if($devErrors=='true'){
			$query.=" AND status=2";
		}

		$result = mssql_query($query);
		if(!is_bool($result)){
			if(mssql_num_rows($result) > 0){
				while($rec = mssql_fetch_array($result)){ //while true
					$arr[] = $rec;
				}
				$jsonresult = json_encode($arr);
				echo $jsonresult;
			}else{
                        	echo '{"length":"0","results":""}';
			}
                }else{
                        echo '{"length":"0","results":""}';
		}
		mssql_close();
	}

	function getAppStats(){
		$queryStartTime = microtime(true);
		$appCount_dcs=16;
		$appCount_rtb=15;
		$appCount_nsy=8;
		$appCount_dma=35;
		$nodeList=array('iad01','iad02','iad03','iad04','iad05','iad06','sjc01','sjc02','sjc03','sjc04','ams01','ams02');

		for($ii=0;$ii<sizeof($nodeList);$ii++){
			for($jj=1;$jj<=$appCount_dcs;$jj++){
				$appStatsHost = 'dt'.$nodeList[$ii].'dcs'.str_pad($jj,2,"0",STR_PAD_LEFT).'p';
				$appStatsUrl = 'http://'.$appStatsHost.'.dc.dotomi.net:6060/statistics_view.do';
				$appStatsResult = json_decode(queryAppStats($appStatsHost,$appStatsUrl));

                                foreach($appStatsResult as $result){
					echo $result->hostname.': '.$result->state.'<br>';
					$queryEndTime = microtime(true);
					$queryProcessTime = $queryEndTime-$queryStartTime;
					echo '<span style="color:red;">Process time: '.round($queryProcessTime,4).' seconds</span><br>';
				}
			}
		}
		for($ii=0;$ii<sizeof($nodeList);$ii++){
			for($jj=1;$jj<=$appCount_rtb;$jj++){
				$appStatsHost = 'dt'.$nodeList[$ii].'rtb'.str_pad($jj,2,"0",STR_PAD_LEFT).'p';
				$appStatsUrl = 'http://'.$appStatsHost.'.dc.dotomi.net:8081/statistics_view.do';
				echo $appStatsUrl.'<br>';
			}
		}
		$queryEndTime = microtime(true);
		$queryProcessTime = $queryEndTime-$queryStartTime;
		echo '<span style="color:red;">Process time: '.round($queryProcessTime,4).' seconds</span><br>';
		for($ii=0;$ii<sizeof($nodeList);$ii++){
			for($jj=1;$jj<=$appCount_nsy;$jj++){
				$appStatsHost = 'dt'.$nodeList[$ii].'nsy'.str_pad($jj,2,"0",STR_PAD_LEFT).'p';
				$appStatsUrl = 'http://'.$appStatsHost.'.dc.dotomi.net:7089/statistics_view.do';
				echo $appStatsUrl.'<br>';
			}
		}
		$queryEndTime = microtime(true);
		$queryProcessTime = $queryEndTime-$queryStartTime;
		echo '<span style="color:red;">Process time: '.round($queryProcessTime,4).' seconds</span><br>';
		for($ii=0;$ii<sizeof($nodeList);$ii++){
			for($jj=1;$jj<=$appCount_dma;$jj++){
				$appStatsHost = 'dt'.$nodeList[$ii].'dma'.str_pad($jj,2,"0",STR_PAD_LEFT).'p';
				$appStatsUrl = 'http://'.$appStatsHost.'.dc.dotomi.net:7070/statistics_view.do';
				echo $appStatsUrl.'<br>';
			}
		}
		$queryEndTime = microtime(true);
		$queryProcessTime = $queryEndTime-$queryStartTime;
		echo '<span style="color:red;">Process time: '.round($queryProcessTime,4).' seconds</span><br>';
	}

	function queryAppStats($hostToQuery,$urlToQuery){
		$curl = curl_init();
		$userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
		curl_setopt($curl,CURLOPT_URL,$urlToQuery); //The URL to fetch. This can also be set when initializing a session with curl_init().
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
		curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,2); //The number of seconds to wait while trying to connect.	
		curl_setopt($curl, CURLOPT_USERAGENT, $userAgent); //The contents of the "User-Agent: " header to be used in a HTTP request.
		curl_setopt($curl, CURLOPT_FAILONERROR, TRUE); //To fail silently if the HTTP code returned is greater than or equal to 400.
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); //To follow any "Location: " header that the server sends as part of the HTTP header.
		curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE); //To automatically set the Referer: field in requests where it follows a Location: redirect.
		curl_setopt($curl, CURLOPT_TIMEOUT, 4); //The maximum number of seconds to allow cURL functions to execute.
		curl_setopt($curl, CURLOPT_ENCODING,  '');
		$rawFeed = curl_exec($curl);
		curl_close($curl);
		$appStatsArr = array();
		if( !empty($rawFeed) ){
			$new_array = json_decode(json_encode((array) simplexml_load_string($rawFeed)), 1);
			$appStatsArr[] = array('hostname'=>$hostToQuery,'build'=>$new_array['@attributes']['Build-Label'],'state'=>$new_array['@attributes']['STATE'],'start'=>$new_array['@attributes']['START']);
		}else{
			$appStatsArr[] = array('hostname'=>$hostToQuery,'build'=>$new_array['@attributes']['Build-Label'],'state'=>$new_array['@attributes']['STATE'],'start'=>$new_array['@attributes']['START']);
		}

		$jsonresult = json_encode($appStatsArr);
		return $jsonresult;
	}

	function getAppList($app,$appDC,$appErrors){
		require 'php/dbconn-swinds.php';
		if($app=='dcs'){
			$query = "SELECT nodeid FROM nodes WHERE sysname LIKE '%dcs%'";
		}
		if($app=='dma'){
			$query = "SELECT nodeid FROM nodes WHERE sysname LIKE '%dma%' AND sysname NOT LIKE '%usdmac%'";
		}
		if($app=='nda'){
			$query = "SELECT nodeid FROM nodes WHERE sysname LIKE '%nda%'";
		}
		if($app=='yotta'){
			$query = "SELECT nodeid FROM nodes WHERE (sysname LIKE '%ydb%' OR sysname LIKE '%ymg%')";
		}
		if($app=='nsy'){
			$query = "SELECT nodeid FROM nodes WHERE sysname LIKE '%nsy%'";
		}
		if($app=='rtb'){
			$query = "SELECT nodeid FROM nodes WHERE sysname LIKE '%rtb%' AND sysname NOT LIKE '%rtbd%'";
		}
		if($app=='hdp'){
			$query = "SELECT nodeid FROM nodes WHERE (sysname LIKE '%hdp%' OR sysname LIKE '%hfl%')";
		}
		if($app=='flume'){
			$query = "SELECT nodeid FROM nodes WHERE sysname LIKE '%flm%'";
		}
		if($app=='lvs'){
			$query = "SELECT nodeid FROM nodes WHERE sysname LIKE '%lvs%'";
		}
		if($app=='gluster'){
			$query = "SELECT nodeid FROM nodes WHERE (sysname LIKE '%gfs%' OR sysname LIKE '%gbu%')";
		}
		if($app=='maestro'){
			$query = "SELECT nodeid FROM nodes WHERE sysname LIKE '%dtordmgrpn06%'";
		}
		if($app=='res'){
			$query = "SELECT nodeid FROM nodes WHERE sysname LIKE '%res%'";
		}

		if($appDC!='total'){
			$query.=" AND sysname LIKE \"%".$appDC."%\"";
		}
		if($appErrors=='true'){
			$query.=" AND status=2";
		}
              
		$result = mssql_query($query);
		if(!is_bool($result)){
			if(mssql_num_rows($result) > 0){
				while($rec = mssql_fetch_array($result)){ //while true
					$arr[] = $rec;
				}
				$jsonresult = json_encode($arr);
				echo $jsonresult;
			}else{
                        	echo '{"length":"0","results":""}';
			}
                }else{
                        echo '{"length":"0","results":""}';
		}
		mssql_close();
	}

	if( isset($_REQUEST['getList']) && $_REQUEST['getList']=='swinds'){
		getSwindsData();
	}
	if( isset($_REQUEST['getList']) && $_REQUEST['getList']=='firesDash'){
		getFiresDash();
	}
	if( isset($_REQUEST['getList']) && $_REQUEST['getList']=='maint-24h'){
		getMaintDash('24h');
	}
	if( isset($_REQUEST['getList']) && $_REQUEST['getList']=='maint-7d'){
		getMaintDash('7d');
	}
	if( isset($_REQUEST['getList']) && $_REQUEST['getList']=='maint-30d'){
		getMaintDash('30d');
	}
	if( isset($_REQUEST['getList']) && $_REQUEST['getList']=='maint-60d'){
		getMaintDash('60d');
	}
	if( isset($_REQUEST['getList']) && $_REQUEST['getList']=='maint-archive'){
		getMaintDash('archive');
	}
	if( isset($_REQUEST['getList']) && $_REQUEST['getList']=='fireBoard'){
		getFireBoard();
	}
	if( isset($_REQUEST['getList']) && $_REQUEST['getList']=='activity-12h'){
		getActivity('12h');
	}
	if( isset($_REQUEST['getList']) && $_REQUEST['getList']=='activity-24h'){
		getActivity('24h');
	}
	if( isset($_REQUEST['getList']) && $_REQUEST['getList']=='activity-7d'){
		getActivity('7d');
	}
	if( isset($_REQUEST['getList']) && $_REQUEST['getList']=='activity-jira'){
		getActivity('jira');
	}
	if( isset($_REQUEST['getList']) && $_REQUEST['getList']=='ganglia'){
		getGangliaAlerts('default');
	}
	if( isset($_REQUEST['getList']) && $_REQUEST['getList']=='ganglia-nocwall'){
		getGangliaAlerts('nocwall');
	}
	if( isset($_REQUEST['getList']) && $_REQUEST['getList']=='iris'){
		getIrisAlerts('default');
	}
	if( isset($_REQUEST['getList']) && $_REQUEST['getList']=='jira-all'){
		getJiraQueue('jira-all');
	}
	if( isset($_REQUEST['getList']) && $_REQUEST['getList']=='jira-change'){
		getJiraQueue('jira-change');
	}
	if( isset($_REQUEST['getList']) && $_REQUEST['getList']=='jira-esgcm'){
		getJiraQueue('jira-esgcm');
	}
	if( isset($_REQUEST['getList']) && $_REQUEST['getList']=='jira-netops-30'){
		getJiraQueue('jira-netops-30');
	}
	if( isset($_REQUEST['getList']) && $_REQUEST['getList']=='jira-netops-all'){
		getJiraQueue('jira-netops-all');
	}
	if( isset($_REQUEST['getList']) && $_REQUEST['getList']=='jira-monitor-all'){
		getJiraQueue('jira-monitor-all');
	}
	if( isset($_REQUEST['getList']) && $_REQUEST['getList']=='jira-netops-old'){
		getJiraQueue('jira-netops-old');
	}
	if( isset($_REQUEST['getList']) && $_REQUEST['getList']=='jira-noc-all'){
		getJiraQueue('jira-noc-all');
	}
	if( isset($_REQUEST['getList']) && $_REQUEST['getList']=='jira-myIssues'){
		getJiraQueue('jira-myIssues');
	}
	if( isset($_REQUEST['getList']) && $_REQUEST['getList']=='jira-myCreatedIssues'){
		getJiraQueue('jira-myCreatedIssues');
	}
	if( isset($_REQUEST['getList']) && $_REQUEST['getList']=='jira-change-calendar'){
		getJiraQueue('jira-change-calendar');
	}
	if( isset($_REQUEST['getList']) && $_REQUEST['getList']=='jira-netops-calendar'){
		getJiraQueue('jira-netops-calendar');
	}
	if( isset($_REQUEST['getList']) && $_REQUEST['getList']=='pastEmails'){
		getEmails();
	}
	if( isset($_REQUEST['getList']) && $_REQUEST['getList']=='footer-ganglia'){
		getFooter('ganglia');
	}
	if( isset($_REQUEST['getList']) && $_REQUEST['getList']=='footer-deploy'){
		getFooter('deploy');
	}
	if( isset($_REQUEST['getReport']) ){
		getReportData($_REQUEST['getReport']);
	}
	if( isset($_REQUEST['getDevList']) ){
		getDevList($_REQUEST['getDevList'],$_REQUEST['getDevDC'],$_REQUEST['getDevErrors']);
	}
	if( isset($_REQUEST['getAppList']) ){
		getAppList($_REQUEST['getAppList'],$_REQUEST['getAppDC'],$_REQUEST['getAppErrors']);
	}
	if( isset($_REQUEST['getAppStats']) ){
		getAppStats();
	}
	if( isset($_REQUEST['getVendorMaintEmail']) && $_REQUEST['getVendorMaintEmail']=='upcoming'){
		getVendorMaintEmail('upcoming');
	}
	if( isset($_REQUEST['getVendorMaintEmail']) && $_REQUEST['getVendorMaintEmail']=='ongoing'){
		getVendorMaintEmail('ongoing');
	}
	
	session_write_close();
?>
