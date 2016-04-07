<?php
	$path=$_SERVER['DOCUMENT_ROOT'];
	require 'HTTP/Request2.php';
	session_start();
	
	const ROOM_CONVERSANT_MEDIA = 454217;
	const ROOM_NETOPS = 454229;
		
	const FORMAT_HTML = 'html';
	const FORMAT_TEXT = 'text';

	function cleanInput($input) {
                $search = array(
                        '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
                        '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
                        '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
                        '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
                );

                $output = preg_replace($search, '', $input);
                return $output;
        }

        function sanitize($input) {
                if (is_array($input)) {
                        foreach($input as $var=>$val) {
                                $output[$var] = sanitize($val);
                        }
                }
                else {
                        if (get_magic_quotes_gpc()) {
                                $input = stripslashes($input);
                        }
                        $input  = cleanInput($input);
                        $output = mysql_real_escape_string($input);
                }
                return $output;
        }

	function make_request($api_method,$args,$http_method){
		echo 'Making request...<br><br>';
		//$apiKey = "ff6befd9e690b85d254e378662521c"; // Notification only
		$apiKey = "73aa439f8af7a0c5f22caca34ee910"; // Admin
                
		$request = new HTTP_Request2('http://api.hipchat.com/v1/'.$api_method.'?format=json&auth_token='.$apiKey);
		if($http_method=="POST"){
			$request->setMethod(HTTP_Request2::METHOD_POST);
			$request->addPostParameter($args);
		}else{
			$request->setMethod(HTTP_Request2::METHOD_GET);
			$url = $request->getUrl();
			$url->setQueryVariables($args);
		}
		//$request->addPostParameter($args);
		echo var_export($request);
		echo '<br><br>';
                
		try {
                        $response = $request->send();
                        if (200 == $response->getStatus()) {
				echo '200...<br><br>';
        	                $jsonResponse = $response->getBody();
	        	        $decode = json_decode($jsonResponse);
				echo var_dump($decode);exit();
				foreach($decode->room as $room){
					echo 'Room';
				}
                        } else {
                                echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
                                $response->getReasonPhrase();
                        }
                } catch (HTTP_Request2_Exception $e) {
                        echo 'Error: ' . $e->getMessage();
                }

		//echo var_dump($request);
        }

	function set_room_topic($room_topic){
		$args = array(
			'room_id'=>ROOM_NETOPS,
			'from'=>'NocDash',
			'topic'=>$room_topic,
			'message_format'=>FORMAT_TEXT
		);
		make_request('rooms/topic',$args,'POST');
	}

	function message_room($message){
		$args = array(
			'room_id'=>ROOM_NETOPS,
			'from'=>'NocDash',
			'message'=>$message,
			'message_format'=>FORMAT_TEXT
		);
		make_request('rooms/message',$args,'POST');
	}

	function room_show(){
		$args = array(
			'room_id'=>ROOM_NETOPS
		);
		make_request('rooms/show',$args,'GET');
	}

	//message_room("test123");
	room_show();
?>
