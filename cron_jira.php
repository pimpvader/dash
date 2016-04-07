<?php
	$path=$_SERVER['DOCUMENT_ROOT'];
	require_once $path.'/php/dbconn.php';
	require $path.'/php/api/jiraCreds.php';

	session_start();
	
	function getJiraQueue($jiraQueue,$jiraUN,$jiraPW){
                switch($jiraQueue){
                        case 'netops':
                                $url = 'http://jira.cnvrmedia.net/rest/api/latest/search?jql=project%20%3D%20netops%20and%20status%20not%20in%20(closed,resolved,complete,done)&maxResults=250&fields=key,issuetype,summary,assignee,reporter,created,updated,description,status';
                                break;
                        case 'noc':
                                $url = 'http://jira.cnvrmedia.net/rest/api/latest/search?jql=project%20%3D%20noc%20AND%20(status%20not%20in%20(closed,resolved,complete,done)%20OR%20status%20in%20(closed%2C%20resolved)%20AND%20updated%20>%3D%20startOfDay(-1))%20ORDER%20BY%20updated%20DESC&maxResults=250&fields=key,issuetype,summary,assignee,reporter,created,updated,description,status';
                                break;
                        case 'change':
                                $url = 'http://jira.cnvrmedia.net/rest/api/latest/search?jql=project%20%3D%20CHANGE%20AND%20status%20in%20(approved%2C"needs%20approval"%2C"in%20progress")%20AND%20%22Deployment%20Date%22%20%3E=%20startOfDay(-7)%20order%20by%20%22Deployment%20Date%22%20DESC&maxResults=150';
                                break;
                        case 'esgcm':
				$url = 'http://jira.cnvrmedia.net/rest/api/latest/search?jql=project=ESGCM%20AND%20status%20in%20(%22Pending%20Approval%22,Approved,%22In%20Progress%22)%20AND%20%22Date%20%26%20Time%22%20%3E=%20startOfDay(-7)%20ORDER%20BY%20%22Date%20%26%20Time%22%20DESC&maxResults=150&fields=key,summary,assignee,reporter,created,updated,description,status,issuetype,customfield_13728';
                                break;
                        case 'it':
                                $url = 'http://jira.cnvrmedia.net/rest/api/latest/search?jql=project%20%3D%20it%20and%20status%20not%20in%20(closed,resolved,complete,done)&maxResults=250&fields=key,issuetype,summary,assignee,reporter,created,updated,description,status';
                                break;
                        case 'sysadmin':
                                $url = 'http://jira.cnvrmedia.net/rest/api/latest/search?jql=project%20%3D%20sysadmin%20and%20status%20not%20in%20(closed,resolved,complete,done)&maxResults=250&fields=key,issuetype,summary,assignee,reporter,created,updated,description,status';
                                break;
                        case 'syseng':
                                $url = 'http://jira.cnvrmedia.net/rest/api/latest/search?jql=project%20%3D%20syseng%20and%20status%20not%20in%20(closed,resolved,complete,done)&maxResults=350&fields=key,issuetype,summary,assignee,reporter,created,updated,description,status';
                                break;
                        case 'monitor':
                                $url = 'http://jira.cnvrmedia.net/rest/api/latest/search?jql=project%20%3D%20monitor%20and%20status%20not%20in%20(closed,resolved,complete,done)&maxResults=250&fields=key,issuetype,summary,assignee,reporter,created,updated,description,status';
                                break;
                        case 'devops':
                                $url = 'http://jira.cnvrmedia.net/rest/api/latest/search?jql=project%20%3D%20devops%20and%20status%20not%20in%20(closed,resolved,complete,done)&maxResults=250&fields=key,issuetype,summary,assignee,reporter,created,updated,description,status';
                                break;
                }
                $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_POST, FALSE);
                curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($curl, CURLOPT_USERPWD, "$jiraUN:$jiraPW");
                curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
                curl_setopt($curl, CURLOPT_CONNECTTIMEOUT,15); //The number of seconds to wait while trying to connect.
                curl_setopt($curl, CURLOPT_USERAGENT, $userAgent); //The contents of the "User-Agent: " header to be used in a HTTP request.
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); //To follow any "Location: " header that the serversends as part of the HTTP header.
                curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE); //To automatically set the Referer: field in requests where it follows a Location: redirect.
                curl_setopt($curl, CURLOPT_TIMEOUT, 110); //The maximum number of seconds to allow cURL functions to execute.
                curl_setopt($curl, CURLOPT_ENCODING,  '');
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);

                $return = curl_exec($curl);
               	$result = curl_getinfo($curl);

		if($return !== false){
                        if( $result['http_code'] == '200' ) {
				$db_connection_nocdash = new db_nocdash();
				$result = $db_connection_nocdash -> query("DELETE FROM NocDashCache.jiraQueues WHERE queue=".$db_connection_nocdash->quote($jiraQueue));

                                $decode = json_decode($return,true);
                                //$decode = json_decode($return);
                                //foreach($decode->issues as $issue){
                                foreach($decode['issues'] as $issue){
                                        switch($jiraQueue){
                                                case 'netops':
                                			$query = "INSERT INTO NocDashCache.jiraQueues (queue,issueNum,issueType,issueSummary,issueAssignee,issueReporter,issueCreated,issueUpdated,issueDesc,issueAssigneeUsername,maintStart,maintEnd,issueStatus) VALUES (".$db_connection_nocdash->quote($jiraQueue).",".$db_connection_nocdash->quote($issue->key).",".$db_connection_nocdash->quote($issue->fields->issuetype->name).",".$db_connection_nocdash->quote($issue->fields->summary).",".$db_connection_nocdash->quote($issue->fields->assignee->displayName).",".$db_connection_nocdash->quote($issue->fields->reporter->displayName).",".$db_connection_nocdash->quote($issue->fields->created).",".$db_connection_nocdash->quote($issue->fields->updated).",".$db_connection_nocdash->quote($issue->fields->description).",".$db_connection_nocdash->quote($issue->fields->assignee->name).",".$db_connection_nocdash->quote($issue->fields->customfield_11647).",".$db_connection_nocdash->quote($issue->fields->customfield_11648).",".$db_connection_nocdash->quote($issue->fields->status->name).")";
							break;
                                                case 'change':
							//$jira_issueType = $issue->fields->issuetype->name;
							//echo var_dump($decode);exit();
							if(is_null($issue->field->customfield_11120)){
								$jira_deployDate = $issue->fields->customfield_12459;
							}else{
								$jira_deployDate = $issue->fields->customfield_11120;
							}
                                			$query = "INSERT INTO NocDashCache.jiraQueues (queue,issueNum,deployDate,issueSummary,issueAssignee,issueReporter,issueCreated,issueUpdated,issueDesc,issueStatus,issueType) VALUES (".$db_connection_nocdash->quote($jiraQueue).",".$db_connection_nocdash->quote($issue->key).",".$db_connection_nocdash->quote($jira_deployDate).",".$db_connection_nocdash->quote($issue->fields->summary).",".$db_connection_nocdash->quote($issue->fields->assignee->displayName).",".$db_connection_nocdash->quote($issue->fields->reporter->displayName).",".$db_connection_nocdash->quote($issue->fields->created).",".$db_connection_nocdash->quote($issue->fields->updated).",".$db_connection_nocdash->quote($issue->fields->description).",".$db_connection_nocdash->quote($issue->fields->status->name).",".$db_connection_nocdash->quote($issue->fields->issuetype->name).")";
							break;
                                                case 'esgcm':
							$jira_deployDate = $issue['fields']['customfield_13728'];
                                			$query = "INSERT INTO NocDashCache.jiraQueues (queue,issueNum,deployDate,issueSummary,issueAssignee,issueReporter,issueCreated,issueUpdated,issueDesc,issueStatus,issueType,issueReporterUsername,issueAssigneeUsername) VALUES (".$db_connection_nocdash->quote($jiraQueue).",".$db_connection_nocdash->quote($issue['key']).",".$db_connection_nocdash->quote($jira_deployDate).",".$db_connection_nocdash->quote($issue['fields']['summary']).",".$db_connection_nocdash->quote($issue['fields']['assignee']['displayName']).",".$db_connection_nocdash->quote($issue['fields']['reporter']['displayName']).",".$db_connection_nocdash->quote($issue['fields']['created']).",".$db_connection_nocdash->quote($issue['fields']['updated']).",".$db_connection_nocdash->quote($issue['fields']['description']).",".$db_connection_nocdash->quote($issue['fields']['status']['name']).",".$db_connection_nocdash->quote($issue['fields']['issuetype']['name']).",".$db_connection_nocdash->quote($issue['fields']['reporter']['name']).",".$db_connection_nocdash->quote($issue['fields']['assignee']['name']).")";
							break;
                                                case 'it':
                                			$query = "INSERT INTO NocDashCache.jiraQueues (queue,issueNum,issueType,issueSummary,issueAssignee,issueReporter,issueCreated,issueUpdated,issueDesc,issueAssigneeUsername,issueStatus) VALUES (".$db_connection_nocdash->quote($jiraQueue).",".$db_connection_nocdash->quote($issue->key).",".$db_connection_nocdash->quote($issue->fields->issuetype->name).",".$db_connection_nocdash->quote($issue->fields->summary).",".$db_connection_nocdash->quote($issue->fields->assignee->displayName).",".$db_connection_nocdash->quote($issue->fields->reporter->displayName).",".$db_connection_nocdash->quote($issue->fields->created).",".$db_connection_nocdash->quote($issue->fields->updated).",".$db_connection_nocdash->quote($issue->fields->description).",".$db_connection_nocdash->quote($issue->fields->assignee->name).",".$db_connection_nocdash->quote($issue->fields->status->name).")";
							break;
                                                case 'noc':
                                			$query = "INSERT INTO NocDashCache.jiraQueues (queue,issueNum,issueType,issueSummary,issueAssignee,issueReporter,issueCreated,issueUpdated,issueDesc,issueAssigneeUsername,issueStatus,issueReporterUsername) VALUES (".$db_connection_nocdash->quote($jiraQueue).",".$db_connection_nocdash->quote($issue['key']).",".$db_connection_nocdash->quote($issue['fields']['issuetype']['name']).",".$db_connection_nocdash->quote($issue['fields']['summary']).",".$db_connection_nocdash->quote($issue['fields']['assignee']['displayName']).",".$db_connection_nocdash->quote($issue['fields']['reporter']['displayName']).",".$db_connection_nocdash->quote($issue['fields']['created']).",".$db_connection_nocdash->quote($issue['fields']['updated']).",".$db_connection_nocdash->quote($issue['fields']['description']).",".$db_connection_nocdash->quote($issue['fields']['assignee']['name']).",".$db_connection_nocdash->quote($issue['fields']['status']['name']).",".$db_connection_nocdash->quote($issue['fields']['reporter']['name']).")";
							break;
                                                case 'sysadmin':
                                			$query = "INSERT INTO NocDashCache.jiraQueues (queue,issueNum,issueType,issueSummary,issueAssignee,issueReporter,issueCreated,issueUpdated,issueDesc,issueAssigneeUsername,issueStatus,issueReporterUsername) VALUES (".$db_connection_nocdash->quote($jiraQueue).",".$db_connection_nocdash->quote($issue['key']).",".$db_connection_nocdash->quote($issue['fields']['issuetype']['name']).",".$db_connection_nocdash->quote($issue['fields']['summary']).",".$db_connection_nocdash->quote($issue['fields']['assignee']['displayName']).",".$db_connection_nocdash->quote($issue['fields']['reporter']['displayName']).",".$db_connection_nocdash->quote($issue['fields']['created']).",".$db_connection_nocdash->quote($issue['fields']['updated']).",".$db_connection_nocdash->quote($issue['fields']['description']).",".$db_connection_nocdash->quote($issue['fields']['assignee']['name']).",".$db_connection_nocdash->quote($issue['fields']['status']['name']).",".$db_connection_nocdash->quote($issue['fields']['reporter']['name']).")";
							break;
                                                case 'syseng':
                                			$query = "INSERT INTO NocDashCache.jiraQueues (queue,issueNum,issueType,issueSummary,issueAssignee,issueReporter,issueCreated,issueUpdated,issueDesc,issueAssigneeUsername,issueStatus,issueReporterUsername) VALUES (".$db_connection_nocdash->quote($jiraQueue).",".$db_connection_nocdash->quote($issue['key']).",".$db_connection_nocdash->quote($issue['fields']['issuetype']['name']).",".$db_connection_nocdash->quote($issue['fields']['summary']).",".$db_connection_nocdash->quote($issue['fields']['assignee']['displayName']).",".$db_connection_nocdash->quote($issue['fields']['reporter']['displayName']).",".$db_connection_nocdash->quote($issue['fields']['created']).",".$db_connection_nocdash->quote($issue['fields']['updated']).",".$db_connection_nocdash->quote($issue['fields']['description']).",".$db_connection_nocdash->quote($issue['fields']['assignee']['name']).",".$db_connection_nocdash->quote($issue['fields']['status']['name']).",".$db_connection_nocdash->quote($issue['fields']['reporter']['name']).")";
							break;
                                                case 'monitor':
                                			$query = "INSERT INTO NocDashCache.jiraQueues (queue,issueNum,issueType,issueSummary,issueAssignee,issueReporter,issueCreated,issueUpdated,issueDesc,issueAssigneeUsername,issueStatus,issueReporterUsername) VALUES (".$db_connection_nocdash->quote($jiraQueue).",".$db_connection_nocdash->quote($issue['key']).",".$db_connection_nocdash->quote($issue['fields']['issuetype']['name']).",".$db_connection_nocdash->quote($issue['fields']['summary']).",".$db_connection_nocdash->quote($issue['fields']['assignee']['displayName']).",".$db_connection_nocdash->quote($issue['fields']['reporter']['displayName']).",".$db_connection_nocdash->quote($issue['fields']['created']).",".$db_connection_nocdash->quote($issue['fields']['updated']).",".$db_connection_nocdash->quote($issue['fields']['description']).",".$db_connection_nocdash->quote($issue['fields']['assignee']['name']).",".$db_connection_nocdash->quote($issue['fields']['status']['name']).",".$db_connection_nocdash->quote($issue['fields']['reporter']['name']).")";
							break;
                                                case 'devops':
                                			$query = "INSERT INTO NocDashCache.jiraQueues (queue,issueNum,issueType,issueSummary,issueAssignee,issueReporter,issueCreated,issueUpdated,issueDesc,issueAssigneeUsername,issueStatus,issueReporterUsername) VALUES (".$db_connection_nocdash->quote($jiraQueue).",".$db_connection_nocdash->quote($issue['key']).",".$db_connection_nocdash->quote($issue['fields']['issuetype']['name']).",".$db_connection_nocdash->quote($issue['fields']['summary']).",".$db_connection_nocdash->quote($issue['fields']['assignee']['displayName']).",".$db_connection_nocdash->quote($issue['fields']['reporter']['displayName']).",".$db_connection_nocdash->quote($issue['fields']['created']).",".$db_connection_nocdash->quote($issue['fields']['updated']).",".$db_connection_nocdash->quote($issue['fields']['description']).",".$db_connection_nocdash->quote($issue['fields']['assignee']['name']).",".$db_connection_nocdash->quote($issue['fields']['status']['name']).",".$db_connection_nocdash->quote($issue['fields']['reporter']['name']).")";
							break;
                                        }
					echo $query.'<br>';
					$result = $db_connection_nocdash->query($query);
					if($result === false){
						echo '[Error]: '.$db_connection_nocdash->error().'<br><br>';
					}else{
						echo '[Success]: Rows updated.<br><br>';
					}
                                }
                	} else {
				echo 'Unexpected HTTP status: ' . $result['http_code'].'<br>';
                	}
		}else{
			echo 'Unexpected HTTP response: ' . $return.'<br>';
		}
        }

	function getJiraComments($jiraKey,$jiraUN,$jiraPW){
                $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
                $curl = curl_init('http://jira.cnvrmedia.net/rest/api/latest/issue/'.$jiraKey.'/comment');
                curl_setopt($curl, CURLOPT_POST, FALSE);
                curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($curl, CURLOPT_USERPWD, "$jiraUN:$jiraPW");
                curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
                curl_setopt($curl, CURLOPT_CONNECTTIMEOUT,2); //The number of seconds to wait while trying to connect.
                curl_setopt($curl, CURLOPT_USERAGENT, $userAgent); //The contents of the "User-Agent: " header to be used in a HTTP request.
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); //To follow any "Location: " header that the serversends as part of the HTTP header.
                curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE); //To automatically set the Referer: field in requests where it follows a Location: redirect.
                curl_setopt($curl, CURLOPT_TIMEOUT, 5); //The maximum number of seconds to allow cURL functions to execute.
                curl_setopt($curl, CURLOPT_ENCODING,  '');
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);

                $return = curl_exec($curl);
               	$result = curl_getinfo($curl);
		if($return !== false){
			if( $result['http_code'] == '200' ) {
				$db_connection_nocdash = new db_nocdash();
				$decode = json_decode($return);
				$commentNum = 1;
				$commentTot = $decode->total;

                        	foreach($decode->comments as $comment){
					if($commentNum==$commentTot){
						$result = $db_connection_nocdash -> query("UPDATE Noc.Turnover SET JiraLastComment=".$db_connection_nocdash->quote($comment->body).",JiraLastCommentAuthor=".$db_connection_nocdash->quote($comment->updateAuthor->displayName)." WHERE JiraNumber=".$db_connection_nocdash->quote($jiraKey));
					} else {
						$commentNum++;
					}
                        	}
                	} else {
				echo 'Unexpected HTTP status: ' . $result['http_code'].'<br>';
                	}
		}else{
			echo 'Unexpected HTTP response: ' . $return.'<br>';
		}
        }

	session_write_close();

	$cronStartTime = microtime(true);

	getJiraQueue($_REQUEST['getProject'],$jiraUN,$jiraPW);
	
	$cronEndTime = microtime(true);
	$cronProcessTime = $cronEndTime-$cronStartTime;
	echo 'Cron job complete.<br>Process Time: '.round($cronProcessTime,3).' seconds.';
?>
