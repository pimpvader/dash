<?php
	$path=$_SERVER['DOCUMENT_ROOT'];
	require_once $path.'/php/dbconn.php';
	require_once $path.'/php/api/jiraCreds.php';

	session_start();
	
	function curl_request($url){
                $curl = curl_init();
                $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
                curl_setopt($curl,CURLOPT_URL,$url); //The URL to fetch. This can also be set when initializing a session with curl_init().
                curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
                curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,2); //The number of seconds to wait while trying to connect.
                curl_setopt($curl, CURLOPT_USERAGENT, $userAgent); //The contents of the "User-Agent: " header to be used in a HTTP request.
                curl_setopt($curl, CURLOPT_FAILONERROR, TRUE); //To fail silently if the HTTP code returned is greater than or equal to 400.
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); //To follow any "Location: " header that the server sends as part of the HTTP header.
                curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE); //To automatically set the Referer: field in requests where it follows a Location: redirect.
                curl_setopt($curl, CURLOPT_TIMEOUT, 4); //The maximum number of seconds to allow cURL functions to execute.
                curl_setopt($curl, CURLOPT_ENCODING,  '');
                $contents = curl_exec($curl);
                curl_close($curl);
                return $contents;
        }

	function scrapePage($html){
                $substrStart = '<p style="font-family:verdana;font-size:240%;color:red">';
                $startPos = strpos($html,$substrStart);
                $ketchupBoard = substr($html,$startPos);
                $ketchupBoard = str_replace('<body style="background-color:black;">','',$ketchupBoard);
                $ketchupBoard = str_replace('<p style="font-family:verdana;font-size:240%;color:red">','',$ketchupBoard);
                $substrStart = 'ABSOLUTE THRESHOLDS';
                $substrEnd = ')';
                $startPos = strpos($ketchupBoard, $substrStart);
                $endPos = strpos($ketchupBoard, $substrEnd);
                $removeStrLen = ($endPos-$startPos)+6;
                $removeStr = substr($ketchupBoard, $startPos, $removeStrLen);
                $ketchupBoard = str_replace($removeStr,'',$ketchupBoard);
                echo 'Cleaning up text ...<br>';

                $substrEnd = '<br>';
                $startPos = 0;
                $endPos = strpos($ketchupBoard, $substrEnd);
                $removeStrLen = ($endPos-$startPos);
                $removeStr = substr($ketchupBoard, $startPos, $removeStrLen);
                $ketchupBoard = str_replace($removeStr,'',$ketchupBoard);
		
		return $ketchupBoard;
        }

	function scrapeGangliaTimestamp(){
                $html = curl_request('http://dtiad00gng01p.dc.dotomi.net/abs.kb.html'); //New IAD Ganglia
                $substrStart = '<p style="font-family:verdana;font-size:240%;color:red">';
                $startPos = strpos($html,$substrStart);
                $ketchupBoard = substr($html,$startPos);
                $ketchupBoard = str_replace('<body style="background-color:black;">','',$ketchupBoard);
                $ketchupBoard = str_replace('<p style="font-family:verdana;font-size:240%;color:red">','',$ketchupBoard);
                $timestamp_substrStart = 0;
                $timestamp_startPos = strpos($ketchupBoard,$timestamp_substrStart);
                $timestamp_substrEnd = 'ABSOLUTE THRESHOLDS';
                $timestamp_endPos = strpos($ketchupBoard, $timestamp_substrEnd);
                $timestamp_removeStrLen = ($timestamp_endPos-$timestamp_startPos);
                $gangliaTimestamp = substr($ketchupBoard, $timestamp_startPos, $timestamp_removeStrLen);
                $gangliaTimestamp = str_replace('<br>','',$gangliaTimestamp);
                $gangliaTimestamp = trim($gangliaTimestamp);

		return $gangliaTimestamp;
	}
	
	function cacheGangliaAlerts(){	
		$ketchupBoardData='';
                //$scrape = curl_request('http://trend.dc.dotomi.net/gweb/abs.aggregate.status'); //Old ORD Ganglia
		//$ketchupBoardData.=scrapePage($scrape);
                $scrape = curl_request('http://dtiad00gng01p.dc.dotomi.net/abs.kb.html'); //New IAD Ganglia
		$ketchupBoardData.=scrapePage($scrape);
                $scrape = curl_request('http://dtsjc00gng01p.dc.dotomi.net/abs.kb.html'); //New SJC Ganglia
		$ketchupBoardData.=scrapePage($scrape);
                $scrape = curl_request('http://dtams00gng01p.dc.dotomi.net/abs.kb.html'); //New AMS Ganglia
		$ketchupBoardData.=scrapePage($scrape);
		$gangliaTimestamp=scrapeGangliaTimestamp();

		echo 'Timestamp: '.$gangliaTimestamp.'<br>';
		$gangliaList = explode("<br>", $ketchupBoardData);

		$db_connection_nocdash = new db_nocdash();

                for($i=0;$i<sizeof($gangliaList);$i++){
                        $gangliaList[$i] = trim($gangliaList[$i]);
                        if(!EMPTY($gangliaList[$i])){
                                $gangliaListSplit = explode(" ",$gangliaList[$i]);
                                $gangliaListSplit[4] = str_replace('T:','',$gangliaListSplit[4]);
                                $gangliaListSplit[5] = str_replace('A:','',$gangliaListSplit[5]);
				$gangliaListOrig[$i] = $gangliaList[$i];
                                $gangliaList[$i] = str_replace($gangliaListSplit[3],'<a href="http://ord-mon102.corp.valueclick.com/Orion/NetPerfMon/Resources/NodeSearchResults.aspx?Property=Caption&SearchText='.$gangliaListSplit[3].'" target="_blank">'.$gangliaListSplit[3].'</a>',$gangliaList[$i]);
                                
				$result = $db_connection_nocdash->query("SELECT * FROM Noc.gangliaAlerts WHERE (hostname=".$db_connection_nocdash->quote($gangliaListSplit[3])." AND checkType=".$db_connection_nocdash->quote($gangliaListSplit[2]).") ORDER BY hostname DESC LIMIT 1"); //check for existing hostname/checkType alert
				echo 'Checking for existing alert in db ...';
                                if($result->num_rows > 0) { // existing entry for hostname/checkType combo found
					echo 'existing entry found<br>';
					$result = $db_connection_nocdash->query("UPDATE Noc.gangliaAlerts SET entry=".$db_connection_nocdash->quote($gangliaListOrig[$i]).",entryLink=".$db_connection_nocdash->quote($gangliaList[$i]).",highLow=".$db_connection_nocdash->quote($gangliaListSplit[0]).",threshold=".$db_connection_nocdash->quote(intval($gangliaListSplit[4])).",absolute=".$db_connection_nocdash->quote(intval($gangliaListSplit[5])).",alertTimestamp=".$db_connection_nocdash->quote($gangliaTimestamp).",alertActive=1 WHERE (hostname=".$db_connection_nocdash->quote($gangliaListSplit[3])." AND checkType=".$db_connection_nocdash->quote($gangliaListSplit[2]).")");
                                } else { // no existing entry for this hostname/checkType,create one
					echo 'NO existing entry found<br>';
					$result = $db_connection_nocdash->query("INSERT INTO Noc.gangliaAlerts (entry,entryLink,highLow,checkType,hostname,threshold,absolute,alertTimestamp,alertActive) VALUES (".$db_connection_nocdash->quote($gangliaListOrig[$i]).",".$db_connection_nocdash->quote($gangliaList[$i]).",".$db_connection_nocdash->quote($gangliaListSplit[0]).",".$db_connection_nocdash->quote($gangliaListSplit[2]).",".$db_connection_nocdash->quote($gangliaListSplit[3]).",".$db_connection_nocdash->quote(intval($gangliaListSplit[4])).",".$db_connection_nocdash->quote(intval($gangliaListSplit[5])).",".$db_connection_nocdash->quote($gangliaTimestamp).",'1')");
					//echo 'no existing alert found';
                                }
				$result = $db_connection_nocdash->query("UPDATE Noc.gangliaAlerts SET snoozeUntil=NULL WHERE snoozeUntil<=NOW()");
                        }
                }

		$result = $db_connection_nocdash->query("UPDATE Noc.gangliaAlerts SET alertActive='0' WHERE alertTimestamp!=".$db_connection_nocdash->quote($gangliaTimestamp));
		echo 'Setting old alerts as non-active in db.<br>';
		echo 'Closing db connection.<br><br>';

		return $ketchupBoardData;
        }

	session_write_close();

	$cronStartTime = microtime(true);
		
	$scrapedOutput = cacheGangliaAlerts();

	$cronEndTime = microtime(true);
	$cronProcessTime = $cronEndTime-$cronStartTime;

	echo 'Scraped data:';
	echo $scrapedOutput.'<br>';
	echo 'Cron job complete.  Process Time: '.round($cronProcessTime,3).' seconds.';
?>
