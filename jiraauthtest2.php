<!DOCTYPE HTML>
<html>
	<head>
	</head>

	<body>
		<?PHP
			$path=$_SERVER['DOCUMENT_ROOT'];
			session_start();
			session_write_close();

			$jiraAppLink_UN = 'nocdash';
			$jiraAppLink_PW = 'J3TzYlSx9RiVodgA';
			$jiraAppLink_creds = $jiraAppLink_UN.':'.$jiraAppLink_PW;
			$jiraUserSession_UN = 'ptomasik';
			$jiraUserSession_PW = 'password';

			$url = 'http://crowd.cnvrmedia.net/crowd/rest/usermanagement/latest/session/';
			//$url = 'http://crowd.cnvrmedia.net/crowd/rest/usermanagement/latest/session/';
			//$url = 'http://jira.cnvrmedia.net/rest/auth/latest/session/';
			$strData_crowd = array(
				"username"=>$jiraUserSession_UN,
				"password"=>$jiraUserSession_PW,
				"validation-factors"=>array(
					"validationFactors"=>array(
						"name"=>"remote_address",
						"value"=>$_SERVER['SERVER_ADDR']
					)
				)
			);
			$strData_jira = array(
				"username"=>$jiraUserSession_UN,
				"password"=>$jiraUserSession_PW
			);
			$json = json_encode($strData_crowd);
			//$json = json_encode($strData_jira);
			//var_dump($json);exit;

			$userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';

			$curl = curl_init();

			curl_setopt($curl,CURLOPT_URL,$url); //The URL to fetch. This can also be set when initializing a session with curl_init().
			curl_setopt($curl, CURLOPT_COOKIEFILE, "cookiefile");
			curl_setopt($curl, CURLOPT_COOKIEJAR, "cookiefile");
			//curl_setopt($curl, CURLOPT_HEADER, TRUE);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json','Content-Type: application/json'));
			//curl_setopt($curl, CURLOPT_POST, TRUE);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
			curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($curl, CURLOPT_USERPWD, $jiraAppLink_creds);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT,30); //The number of seconds to wait while trying to connect.
			curl_setopt($curl, CURLOPT_USERAGENT, $userAgent); //The contents of the "User-Agent: " header to be used in a HTTP request.
			//curl_setopt($curl, CURLOPT_FAILONERROR, TRUE); //To fail silently if the HTTP code returned is greater than or equal to 400.
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); //To follow any "Location: " header that the serversends as part of the HTTP header.
			curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE); //To automatically set the Referer: field in requests where it follows a Location: redirect.
			curl_setopt($curl, CURLOPT_TIMEOUT, 60); //The maximum number of seconds to allow cURL functions to execute.
			curl_setopt($curl, CURLOPT_ENCODING,  '');
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			//curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
			$return = curl_exec($curl);

			$result = curl_getinfo($curl);

			/*if(!curl_errno($curl)){
				$result = curl_getinfo($curl);
			}else{
				$errFound = true;
			}*/
			curl_close($curl);

			$return = json_encode($return);
			var_dump($return);
			//var_dump($result);
			if( $result['http_code']=="201" ){
				echo 'STATUS="Up"';
			}else{
				$errFound = true;
			}

			//$decode = json_decode($return);
			//var_dump($decode);
		?>
	</body>
</html>
