<?PHP
	$iad_nodes = 6;
	$sjc_nodes = 4;

	$dma_count = 35;
	$dcs_count = 16;
	$rtb_count = 15;
	$nsy_count = 8;

	$mh = curl_multi_init();

	for($i=1;$i<=$iad_nodes;$i++){
		for($ii=1;$ii<=$dma_count;$ii++){
			${"curl_dtiad".str_pad($i,2,"0",STR_PAD_LEFT)."dma".str_pad($ii,2,"0",STR_PAD_LEFT)."p"} = curl_init();
			curl_setopt(${"curl_dtiad".str_pad($i,2,"0",STR_PAD_LEFT)."dma".str_pad($ii,2,"0",STR_PAD_LEFT)."p"},CURLOPT_URL,"http://dtiad".str_pad($i,2,"0",STR_PAD_LEFT)."dma".str_pad($ii,2,"0",STR_PAD_LEFT)."p:7070/statistics_view.do");
			curl_setopt(${"curl_dtiad".str_pad($i,2,"0",STR_PAD_LEFT)."dma".str_pad($ii,2,"0",STR_PAD_LEFT)."p"},CURLOPT_HEADER,0);
			curl_setopt(${"curl_dtiad".str_pad($i,2,"0",STR_PAD_LEFT)."dma".str_pad($ii,2,"0",STR_PAD_LEFT)."p"},CURLOPT_RETURNTRANSFER,1);
			curl_setopt(${"curl_dtiad".str_pad($i,2,"0",STR_PAD_LEFT)."dma".str_pad($ii,2,"0",STR_PAD_LEFT)."p"},CURLOPT_CONNECTTIMEOUT,5);
			curl_setopt(${"curl_dtiad".str_pad($i,2,"0",STR_PAD_LEFT)."dma".str_pad($ii,2,"0",STR_PAD_LEFT)."p"},CURLOPT_TIMEOUT,10);
			curl_multi_add_handle($mh,${"curl_dtiad".str_pad($i,2,"0",STR_PAD_LEFT)."dma".str_pad($ii,2,"0",STR_PAD_LEFT)."p"});
		}
	}
	$active = null;
	
	//execute the handles
	do {
		$mrc = curl_multi_exec($mh, $active);
	} while ($mrc == CURLM_CALL_MULTI_PERFORM);

	while ($active && $mrc == CURLM_OK) {
		if (curl_multi_select($mh) != -1) {
			do {
				$mrc = curl_multi_exec($mh, $active);
			} while ($mrc == CURLM_CALL_MULTI_PERFORM);
		}
	}

	//close the handles
	for($i=1;$i<=$iad_nodes;$i++){
		for($ii=1;$ii<=$dma_count;$ii++){
			if(!curl_errno(${"curl_dtiad".str_pad($i,2,"0",STR_PAD_LEFT)."dma".str_pad($ii,2,"0",STR_PAD_LEFT)."p"})){
				$result = curl_getinfo(${"curl_dtiad".str_pad($i,2,"0",STR_PAD_LEFT)."dma".str_pad($ii,2,"0",STR_PAD_LEFT)."p"});
			}else{
				$errFound = true;
			}

			$html = curl_multi_getcontent(${"curl_dtiad".str_pad($i,2,"0",STR_PAD_LEFT)."dma".str_pad($ii,2,"0",STR_PAD_LEFT)."p"});
			$new_array = json_decode(json_encode((array) simplexml_load_string($html)),1);
			if( $new_array['@attributes']['STATE'] == 'Running' ){
				echo '<div style="background-color:#ddffdd;">';
			}else if( $new_array['@attributes']['STATE'] == 'Initializing' ){
				echo '<div style="background-color:#ffffdd;">';
			}else{
				echo '<div style="background-color:#ffdada;">';
			}
			echo $result['url'].' | STATUS="'.$new_array['@attributes']['STATE'].'"<br>';

			echo 'Status: '.$new_array['@attributes']['STATE'].'<br>';
			echo 'Since: '.$new_array['@attributes']['START'].'<br>';
			echo 'Build: '.$new_array['@attributes']['Build-Label'].'<br></div><hr>';
			
			curl_multi_remove_handle($mh,${"curl_dtiad".str_pad($i,2,"0",STR_PAD_LEFT)."dma".str_pad($ii,2,"0",STR_PAD_LEFT)."p"});
		}
	}
	curl_multi_close($mh);
?>
