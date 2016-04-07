<!DOCTYPE HTML>
<html>
	<body>
		<head>
			<title>DRAC Check</title>
		</head>
		<?PHP
			$queryStartTime = microtime(true);
			if( isset($_REQUEST['hostname']) ){
				$errFound = false;
				$serverName = explode('.',$_REQUEST['hostname']);
				$serverName = $serverName[0];
				if(stristr($_REQUEST['hostname'],'dotomi.com') !== false){
					$url = "https://".strtolower($serverName)."r.dotomi.com";
				}else if(stristr($_REQUEST['hostname'],'dtord-homes2') !== false){
					$url = "https://".strtolower($serverName)."r.dc.dotomi.net";
				}else if(stristr($_REQUEST['hostname'],'dtmextranet.corp.valueclick.com') !== false){
					$url = "https://".strtolower($serverName).".drac.dtmextranet.corp.valueclick.com";
				}else if(stristr($_REQUEST['hostname'],'-mon') !== false){
					$url = "https://".strtolower($serverName).".drac.corp.valueclick.com";
				}else if(stristr($_REQUEST['hostname'],'ord-') !== false){
					$url = "https://".strtolower($serverName).".drac.corp.valueclick.com";
				}else if(stristr($_REQUEST['hostname'],'corp.valueclick.com') !== false){
					$url = "https://".strtolower($serverName).".drac.corp.valueclick.com";
				}else if(stristr($_REQUEST['hostname'],'.dc6.') || stristr($_REQUEST['hostname'],'.sj2.') || stristr($_REQUEST['hostname'],'.ams5.') || stristr($_REQUEST['hostname'],'.sh5.') || stristr($_REQUEST['hostname'],'.wl.')){
					if(stristr($_REQUEST['hostname'],'blade-')){
						$url = $_REQUEST['hostname'];
					}else{
						$url = 'console.'.$_REQUEST['hostname'];
					}
				}else{
					$url = "https://".strtolower($serverName)."r.dc.dotomi.net";
				}
				echo "Attempting to locate ".$url."...<br><br>";
                		$userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
				
				$curl = curl_init();
                		curl_setopt($curl,CURLOPT_URL,$url); //The URL to fetch. This can also be set when initializing a session with curl_init().
                		curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
                		curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,20); //The number of seconds to wait while trying to connect.   
                		curl_setopt($curl, CURLOPT_USERAGENT, $userAgent); //The contents of the "User-Agent: " header to be used in a HTTP request.
                		curl_setopt($curl, CURLOPT_FAILONERROR, TRUE); //To fail silently if the HTTP code returned is greater than or equal to 400.
                		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); //To follow any "Location: " header that the server sends as part of the HTTP header.
                		curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE); //To automatically set the Referer: field in requests where it follows a Location: redirect.
                		curl_setopt($curl, CURLOPT_TIMEOUT, 30); //The maximum number of seconds to allow cURL functions to execute.
                		curl_setopt($curl, CURLOPT_ENCODING,  '');
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                		
				//curl_exec($curl);
                		$return = curl_exec($curl);
                		$result = curl_getinfo($curl);

				if(!curl_errno($curl)){ 
					$result = curl_getinfo($curl);
				}else{
					$errFound = true;
				}

				if( $result['http_code']=="200" ){
					echo 'STATUS="Up"';
				}else{
					$return = curl_exec($curl);
					$result = curl_getinfo($curl);

					if(!curl_errno($curl)){ //try a 2nd time if error occurs
						$result = curl_getinfo($curl);
					}else{
						$errFound = true;
					}

					if( $result['http_code']=="200" ){
						echo 'STATUS="Up"';
					}else{
						$errFound = true;
					}
				}

			}else{
				echo 'No hostname specified in URL.';
			}

			if( $errFound ){
				echo 'Unable to locate '.$url.'...<br><br>STATUS="Down"';
			}

                	curl_close($curl);
			$queryEndTime = microtime(true);
			$queryProcessTime = $queryEndTime-$queryStartTime;
			echo '<br><br>Process time: '.round($queryProcessTime,4).' seconds';
		?>
	</body>
</html>
