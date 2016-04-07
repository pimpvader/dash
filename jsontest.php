<?PHP
	$path=$_SERVER['DOCUMENT_ROOT'];
	//include 'resources/php/common.php';
	require 'HTTP/Request2.php';
	session_start();

	function getSwindsData(){
                //$request = new HTTP_Request2('https://dtord01mon02p:17778/SolarWinds/InformationService/v3/Json/Query?query=SELECT+COUNT(nodes.sysname)+AS+total_router_error+FROM+orion.nodes+WHERE+nodes.sysname+LIKE+%27%rtr%%27+OR+nodes.sysname+LIKE+%27%cor%%27+AND+nodes.status=%272%27', HTTP_Request2::METHOD_GET);
                $request = new HTTP_Request2('https://ord-swdb101.corp.valueclick.com:17778/SolarWinds/InformationService/v3/Json/Query?query=SELECT+nodes.sysname+FROM+orion.nodes+WHERE+nodes.sysname+LIKE+%27%rtr%%27+OR+nodes.sysname+LIKE+%27%cor%%27+AND+nodes.status=%272%27', HTTP_Request2::METHOD_GET);

                //$swQuery = '';
                $swindsUN = "corp\\".$_SESSION['username'];
                $swindsPW = $_SESSION['password'];
                $request->setAuth($swindsUN,$swindsPW,HTTP_Request2::AUTH_BASIC);
                $request->setConfig(array(
                        'ssl_verify_peer'   => FALSE,
                        'ssl_verify_host'   => FALSE
                ));

                try {
                        $response = $request->send();
                        if (200 == $response->getStatus()) {
                                $swinds_json = $response->getBody();
                                $decode = json_decode($swinds_json);
                                //$json_length = sizeof($swinds_json);
                                $resultCount=0;
                                $resultList='';

                                foreach($decode->results as $result){
                                        //echo $result->sysname."<br>";
                                        $resultCount++;
                                        $resultList = $resultList.$result->sysname.",";
                                }

				switch($swQuery){
					case 'total_switch':
                                		echo '<span title="'.$resultList[0].'\n'.$resultList[1].'">'.$resultCount.'</span>';
                                                break;
                                        case 'total_switch_error':
                                		echo '<span title="'.$resultList[0].'\n'.$resultList[1].'">'.$resultCount.'</span>';
                                                break;
                                        case 'total_router':
                                		echo '<span title="'.$resultList[0].'\n'.$resultList[1].'">'.$resultCount.'</span>';
                                                break;
                                        case 'total_router_error':
                                		echo '<span title="'.$resultList[0].'\n'.$resultList[1].'">'.$resultCount.'</span>';
                                                break;
                                        case 'total_firewall':
                                		echo '<span title="'.$resultList[0].'\n'.$resultList[1].'">'.$resultCount.'</span>';
                                                break;
                                        case 'total_firewall_error':
                                		echo '<span title="'.$resultList[0].'\n'.$resultList[1].'">'.$resultCount.'</span>';
                                                break;
                                        case 'total_nix_server':
                                		echo '<span title="'.$resultList[0].'\n'.$resultList[1].'">'.$resultCount.'</span>';
                                                break;
                                        case 'total_nix_server_error':
                                		echo '<span title="'.$resultList[0].'\n'.$resultList[1].'">'.$resultCount.'</span>';
                                                break;
                                        case 'total_win_server':
                                		echo '<span title="'.$resultList[0].'\n'.$resultList[1].'">'.$resultCount.'</span>';
                                                break;
                                        case 'total_win_server_error':
                                		echo '<span title="'.$resultList[0].'\n'.$resultList[1].'">'.$resultCount.'</span>';
                                                break;
                                }

                                $resultList = explode(',',$resultList);
                                //$resultList = trim($resultList);
                                //echo var_dump($resultList);
                                echo '<span title="'.$resultList[0].'\n'.$resultList[1].'">'.$resultCount.'</span>';
                        } else {
                                echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
                                $response->getReasonPhrase();
                        }
                } catch (HTTP_Request2_Exception $e) {
                        echo 'Error: ' . $e->getMessage();
                }
        }

	$request = new HTTP_Request2('https://api.dell.com/support/v2/assetinfo/warranty/tags.json?svctags=3T50BS1|2VT3GS1&apikey=1adecee8a60444738f280aad1cd87d0e', HTTP_Request2::METHOD_GET);

        $request->setConfig(array(
                'ssl_verify_peer'   => FALSE,
                'ssl_verify_host'   => FALSE
        ));

	try {
		$response = $request->send();
                if (200 == $response->getStatus()) {
                        $jsonResponse = $response->getBody();
                        $decode = json_decode($jsonResponse);
			//echo $decode->GetAssetWarrantyResponse->GetAssetWarrantyResult->Response;
			//echo $jsonResponse;

                        foreach($decode->GetAssetWarrantyResponse->GetAssetWarrantyResult->Response->DellAsset as $result){
				echo $result->ServiceTag;
                        }
                } else {
                        echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
                        $response->getReasonPhrase();
                }
        } catch (HTTP_Request2_Exception $e) {
                echo 'Error: ' . $e->getMessage();
        }
?>

<!DOCTYPE HTML>
<html>
	<head>
	</head>

	<body>
		<div id="pageContainer" class="container">
		</div>
	</body>
</html>
