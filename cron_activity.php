<?php
	$path=$_SERVER['DOCUMENT_ROOT'];
	require_once $path.'/php/dbconn.php';
	require_once $path.'/php/api/jiraCreds.php';

	session_start();
	
	function generateActivity($jiraUN,$jiraPW,$jiraQueue){
		$db_connection_nocdash = new db_nocdash();
		$result = $db_connection_nocdash->query("SELECT * FROM NocDashCache.jiraQueues WHERE queue='noc' AND issueUpdated >= NOW()-INTERVAL 30 DAY ORDER BY issueUpdated DESC");

		if($result->num_rows > 0){
			echo 'Number of results pulled from NocDashCache.jiraQueues: '.$result->num_rows.'<br><br>';
			//$row = array();
			while($row = $result->fetch_assoc()){
				echo var_dump($row).'<br><br>';
				$result2 = $db_connection_nocdash->query("SELECT * FROM Noc.Turnover WHERE JiraNumber=".$db_connection_nocdash->quote($row['issueNum']));
				if($result2->num_rows < 1) {
					//if JiraNumber not already in DB, INSERT:
					$db_connection_nocdash->query("INSERT INTO Noc.Turnover (JiraNumber,JiraSummary,JiraAssignee,JiraLastModified,JiraStatus) VALUES (".$db_connection_nocdash->quote($row['issueNum']).",".$db_connection_nocdash->quote($row['issueSummary']).",'None',".$db_connection_nocdash->quote($row['issueUpdated']).",".$db_connection_nocdash->quote($row['issueStatus']).")");
				} else {
					//if JiraNumber already exists in DB, UPDATE:
					$db_connection_nocdash->query("UPDATE Noc.Turnover SET JiraSummary=".$db_connection_nocdash->quote($row['issueSummary']).",JiraAssignee=".$db_connection_nocdash->quote($row['issueAssignee']).",JiraLastModified=".$db_connection_nocdash->quote($row['issueUpdated']).",JiraStatus=".$db_connection_nocdash->quote($row['issueStatus'])." WHERE JiraNumber=".$db_connection_nocdash->quote($row['issueNum']));
				}
				getJiraComments($row['issueNum'],$jiraUN,$jiraPW);
			}
		}
        }

	function getJiraComments($jiraKey,$jiraUN,$jiraPW){
		$userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
		$curl = curl_init('http://jira.cnvrmedia.net/rest/api/latest/issue/'.$jiraKey.'/comment');
		curl_setopt($curl, CURLOPT_POST, FALSE);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_USERPWD, "$jiraUN:$jiraPW");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT,30); //The number of seconds to wait while trying to connect.
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

				$decode = json_decode($return);
				$commentNum = 1;
				$commentTot = $decode->total;

                                foreach($decode->comments as $comment){
					if($commentNum==$commentTot){
						$db_connection_nocdash->query("UPDATE Noc.Turnover SET JiraLastComment=".$db_connection_nocdash->quote($comment->body).",JiraLastCommentAuthor=".$db_connection_nocdash->quote($comment->updateAuthor->displayName)." WHERE JiraNumber=".$db_connection_nocdash->quote($jiraKey));
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
		
	generateActivity($jiraUN,$jiraPW,$_REQUEST['jiraQueue']);
	
	$cronEndTime = microtime(true);
	$cronProcessTime = $cronEndTime-$cronStartTime;
	echo 'Cron job complete.  Process Time: '.round($cronProcessTime,3).' seconds.';
?>
