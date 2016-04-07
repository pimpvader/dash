<?PHP
	$path=$_SERVER['DOCUMENT_ROOT'];
	require_once $path.'/php/dbconn.php';
	include $path.'/php/common.php';

	function dellWarrantyResults(){
		switch(rand(1,3)){
			case 1:
				$apiKey = '1adecee8a60444738f280aad1cd87d0e';
				break;
			case 2:
				$apiKey = 'd676cf6e1e0ceb8fd14e8cb69acd812d';
				break;
			case 3:
				$apiKey = '849e027f476027a394edd656eaef4842';
				break;
		}

		if( !empty($_POST) || isset($_REQUEST[svctagLookup]) ){
			$tagList = explode(",",$_POST['lookupDellWarranty_tag']);
			$tagListTmp = array();
			$j=0;
			for($i=0;$i<sizeof($tagList);$i++){
				if(trim($tagList[$i])!=''){
					$tagListTmp[$j] = trim($tagList[$i]);
					$j++;
				}
			}
			$tagListOut = implode("|",$tagListTmp);
			if( isset($_REQUEST[svctagLookup]) ) {
				$tagListOut = $_REQUEST[svctagLookup];
			}
			$requestURL = 'https://api.dell.com/support/v2/assetinfo/warranty/tags.json?svctags='.$tagListOut.'&apikey='.$apiKey;//echo $requestURL;
			$userAgent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko)';
			$curl = curl_init();
			curl_setopt($curl,CURLOPT_URL,$requestURL); //The URL to fetch. This can also be set when initializing a session with curl_init().
			curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
			curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,3); //The number of seconds to wait while trying to connect.
			curl_setopt($curl, CURLOPT_USERAGENT, $userAgent); //The contents of the "User-Agent: " header to be used in a HTTP request.
			curl_setopt($curl, CURLOPT_FAILONERROR, TRUE); //To fail silently if the HTTP code returned is greater than or equal to 400.
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); //To follow any "Location: " header that the server sends as part of the HTTP header.
			curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE); //To automatically set the Referer: field in requests where it follows a Location: redirect.
			curl_setopt($curl, CURLOPT_HTTPGET, TRUE);
			curl_setopt($curl, CURLOPT_TIMEOUT, 10); //The maximum number of seconds to allow cURL functions to execute.
			curl_setopt($curl, CURLOPT_ENCODING,  '');
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			$result = curl_exec($curl);

			if(!curl_errno($curl)){
				$curl_info = curl_getinfo($curl);
			}else{
				echo '[Timeout] api.dell.com';
			}
			curl_close($curl);

			if( $curl_info['http_code']=="200" ){
				$jsonResponse = $result;
				$decode = json_decode($jsonResponse);

				if(sizeof($tagListTmp)<=1){ // single service tag:
					foreach($decode->GetAssetWarrantyResponse->GetAssetWarrantyResult->Response as $asset){
						outputWarrantyTable($asset,$requestURL);
					}
				}else{// multiple service tags:
					foreach($decode->GetAssetWarrantyResponse->GetAssetWarrantyResult->Response->DellAsset as $asset){
						outputWarrantyTable($asset,$requestURL);
					}
				}
			}
		}
	}

	function outputWarrantyTable($result,$requestURL){
		echo '<div class="panel panel-default" style="margin-top:25px;">';
		echo '<div class="panel-body">';
		echo '
			<table style="width:100%;margin-bottom:10px;">
				<tr>
					<tr><td><b>Dell API Call:</b> [<a href="'.$requestURL.'" target="_blank">JSON Response</a>]</td></tr>
					<td><b>Service Tag:</b> ';echo $result->ServiceTag;echo ' [<a href="http://www.dell.com/support/home/us/en/04/product-support/servicetag/'.$result->ServiceTag.'" target="_blank">Dell Support Site</a>]</td>
					<td><b>Model:</b> ';echo $result->MachineDescription;echo '</td>
					<td><b>Ship Date:</b> ';echo gmdate("m-d-Y",strtotime($result->ShipDate));echo '</td>
				</tr>
			</table>
			<table style="width:100%;border-top:dotted 1px #ccc;border-bottom:dotted 1px #ccc;">
				<th>Service</th>
				<th>Start date</th>
				<th>End date</th>';
		if( sizeof($result->Warranties->Warranty) > 1){
			foreach($result->Warranties->Warranty as $result_warranty){
				echo '
					<tr>
						<td>';echo $result_warranty->ServiceLevelDescription;echo '</td>
						<td style="min-width:90px;">';echo gmdate("m-d-Y",strtotime($result_warranty->StartDate));echo '</td>
						<td style="min-width:90px;">';echo gmdate("m-d-Y",strtotime($result_warranty->EndDate));echo '</td>
					</tr>
				';
			}
		}else{
			foreach($result->Warranties as $result_warranty){
				echo '
					<tr>
						<td>';echo $result_warranty->Warranty->ServiceLevelDescription;echo '</td>
						<td style="min-width:90px;">';echo gmdate("m-d-Y",strtotime($result_warranty->StartDate));echo '</td>
						<td style="min-width:90px;">';echo gmdate("m-d-Y",strtotime($result_warranty->EndDate));echo '</td>
					</tr>
				';
			}
		}
		echo '</table>';
		echo '</div></div>';
	}
?>

<!DOCTYPE HTML>
<html>
	<head>
		<?PHP getHtmlHead(); ?>
	</head>

	<body>
		<?PHP getHeader(); ?>
		
                <div id="pageContainer" class="container">
                       	<div class="panel panel-default">
				<div class="panel-heading">Dell Hardware - Warranty Lookup</div>
	                        <div class="panel-body">
					<form name="warrantyLookupForm" role="form" action="warranty.php" method="post">
						<label for="lookupDellWarranty_tag" class="control-label">Service Tag(s)</label><br>
						<input type="text" style="width:60%;" id="lookupDellWarranty_tag" name="lookupDellWarranty_tag" placeholder="Tag#1, Tag#2, Tag#3" required autofocus>
						<input type="submit" name="lookupDellWarranty" value="Lookup Warranty">
					</form>
					<?PHP dellWarrantyResults(); ?>
                               	</div>
                       	</div>
                </div>

		<script>
			$(document).ready(function(){
				$(document).attr('title', $(document).attr('title')+' - Dell Warranty Lookup');
			});
		</script>
		<?PHP addFooter(); ?>
	</body>
</html>
