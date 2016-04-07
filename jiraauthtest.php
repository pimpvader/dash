<!DOCTYPE HTML>
<html>
	<head>
	</head>

	<body>
		<?PHP
		function op1(){
			$path=$_SERVER['DOCUMENT_ROOT'];
			//include 'resources/php/common.php';
			require_once 'HTTP/Request2.php';
			require_once $path.'/php/api/jiraCreds.php';
			session_start();
		
                	//$request = new HTTP_Request2('http://jira.cnvrmedia.net/rest/api/latest/issue/'.$jiraKey.'/comment', HTTP_Request2::METHOD_GET);
                	$request = new HTTP_Request2('http://jira.cnvrmedia.net/rest/auth/1/session/', HTTP_Request2::METHOD_POST);
                	//$request = new HTTP_Request2('http://jira.cnvrmedia.net/rest/auth/1/session/', HTTP_Request2::METHOD_GET);
                	//$request = new HTTP_Request2('http://jira.cnvrmedia.net/rest/api/latest/user/?username=ptomasik', HTTP_Request2::METHOD_GET);
                	$request->setAuth($jiraUN,$jiraPW,HTTP_Request2::AUTH_BASIC);
			$request->addPostParameter("username",$jiraUN);
			$request->addPostParameter("password",$jiraPW);
			$request->setHeader('Content-type: application/json');
			$jiraCreds = array("username"=>$jiraUN,"password"=>$jiraPW);
			$encodeCreds = json_encode($jiraCreds);
			$request->addPostParameter($encodeCreds);
			//echo var_dump($request);exit();

                	try {
				echo 'Try<br>';
                	        $response = $request->send();
                	        if (200 == $response->getStatus()) {
					echo '<br>200<br>';
                	                $result = $response->getBody();
                	                $decode = json_decode($result);
					echo var_dump($decode);exit();
                	               	/* 
					foreach($decode->comments as $comment){
						if($commentNum==$commentTot){
							$result = mysql_query("UPDATE Noc.Turnover SET JiraLastComment='".sanitize($comment->body)."',JiraLastCommentAuthor='".$comment->updateAuthor->displayName."' WHERE JiraNumber='".$jiraKey."'");
			                                $resultCount++;
			                                $resultList = $resultList.$result->sysname.",";
			                        }
					}*/
			        } else {
			                echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
			                $response->getReasonPhrase();
			        }
			} catch (HTTP_Request2_Exception $e) {
				echo 'Error: ' . $e->getMessage();
			}
		}

		function op2(){
			echo 'starting op2...<br><br>';
			$path=$_SERVER['DOCUMENT_ROOT'];
			require_once 'HTTP/Request2.php';
			require_once $path.'/php/api/jiraCreds.php';
			//echo 'jiraUN: '.$jiraUN.'<br>jiraPW: '.$jiraPW.'<br><br>';
			
			$request = new HTTP_Request2('http://jira.cnvrmedia.net/rest/api/latest/issue', HTTP_Request2::METHOD_POST);
                        $request->setAuth($jiraUN,$jiraPW,HTTP_Request2::AUTH_BASIC);
                        $request->setConfig(array(
                                'ssl_verify_peer'   => FALSE,
                                'ssl_verify_host'   => FALSE
                        ));
			$request->setHeader('Content-type: application/json');

			$issueAttrib = array("fields");
			$issueAttrib["fields"] = array(
				"project" => array("id" => "10720"),
				"summary" => "testing1234 from API",
				//"customfield_12440" => "client name goes here",
				"issuetype" => array("id" => " *** ID for new issue type *** "), // Incident
				//"issuetype" => array("id" => "85"), // Server Hardware Request
				//"issuetype" => array("id" => "108"), //FTP Account Creation
				//"customfield_12453" => "12925", //FTP Account Creation
				"assignee" => array("name" => "ptomasik"),
				"reporter" => array("name" => "ptomasik")
			);
			
			$encodeAttrib = json_encode($issueAttrib);
			var_dump($encodeAttrib);
			//$request->addPostParameter($encodeAttrib);
                        
			try {
                                $response = $request->send();
                                if (200 == $response->getStatus()) {
                                        $json_response = $response->getBody();
                                        $decode = json_decode($json_response);

                                        var_dump($decode);
                                } else {
                                        echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
                                        $response->getReasonPhrase();
                                }
                        } catch (HTTP_Request2_Exception $e) {
                                echo 'Error: ' . $e->getMessage();
                                exit();
                        }

		}

		op2();

		?>
	</body>
</html>
