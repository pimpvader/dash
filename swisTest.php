<?php
$data = array(
  'query' => "SELECT NodeID,InterfaceID,Status,Caption FROM Orion.NPM.Interfaces WHERE InterfaceID=1822"
  );
$url = "https://dtord01mon02p.corp.valueclick.com:17778/SolarWinds/InformationService/v3/Json/Query";
$jdata = json_encode($data);
echo $result = CallAPI($url, $jdata);
 
 
function CallAPI($url, $data = false) {
   $ch = curl_init();
 
    // Authentication:
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, "ptomasik:password");
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSLVERSION, 3);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_VERBOSE, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                        'Content-type: application/json',
                                        'Content-length: ' . strlen($data)
                                      ));
 
  $result = curl_exec($ch);
  $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $error = curl_error($ch);
  $info = curl_getinfo($ch);
 
  curl_close($ch);
 
    if ($result == false) {
  echo "Response: " . $response . "<br>";
  echo "Error: " . $error . "<br>";
  echo "Info: " . print_r($info);
  die();
  }
 
  return $result;
}
?>
