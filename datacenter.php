<?PHP
	$path=$_SERVER['DOCUMENT_ROOT'];
	include $path.'/php/common.php';
	require $path.'/php/api/jiraCreds.php';
	require 'HTTP/Request2.php';
	

	function getView($viewType){
		require $path.'/php/dbconn.php';
		$db_dcim = "opendcim.dc.dotomi.net";
		$db_dcim_un = "nocdev";
		$db_dcim_pw = "SyEVvQxfwJpp2M6q";
		
		$con_dcim = mysql_connect($db_dcim,$db_dcim_un,$db_dcim_pw,true);
        	if(!$con_dcim){
        	        die('Could not connect: ' . mysql_error());
        	}
		//echo 'OpenDCIM dbconn: '.$con_dcim.'<br>';
		//echo 'NocDash dbconn: '.$con_nocdash.'<br><br>';
		
		if($viewType=="alerts-by-rack"){
			$query_cabinet = "SELECT CabinetHeight FROM fac_Cabinet WHERE CabinetID=13;";
			$query_devices = "SELECT position,label,serialno,primaryip,height FROM dcim.fac_Device WHERE Cabinet=13 AND backside=0 ORDER BY position DESC";
			
			$rowCounter=0;
                	$colCounter=0;
                	$queryTimeStart = microtime(true);
			
			$selectDB = mysql_select_db('dcim',$con_dcim);
			//$selectDB = mysql_select_db('Noc',$con_nocdash);
        		if(!$selectDB){
        	        	die('Can\'t select table: ' . mysql_error());
        		}
                	
                	$result = mysql_query($query_cabinet); //get height of cabinet
			if ( !mysql_num_rows($result) ) {
        	        	echo 'No records found: ' . mysql_error();
                	} else {
                	        $cab_size_tmp = mysql_fetch_array($result);
				$cab_size = $cab_size_tmp[0];
			}

			$result2 = mysql_query($query_devices);
			if ( !mysql_num_rows($result2) ) {
        	        	echo 'No records found: ' . mysql_error();
                	} else {
                	        echo '<table class="table" style="width:15%;margin:0 auto;border:1px solid #aaa;text-align:center;background:white;"><thead><tr><th>Pos</th><th>Server</th><th>Pos</th></tr></thead><tbody>';

				$curr_row = $cab_size;
                	        while ($row2 = mysql_fetch_array($result2)) {
					//var_dump($row2);
					for($ii=$curr_row;$ii>0;$ii--){
						if($ii==$row2['position']){
		                	                echo '<tr><td style="width:30px;">'.$row2['position'].'</td><td rowspan='.$row2['height'].' class="rackToggle" title="'.$row2['serialno'].'" data-toggle="popover" data-container="body" data-trigger="hover" data-html="true" data-content="<div>IP: '.$row2['primaryip'].'</div>">'.$row2['label'].'</td><td style="width:30px;">'.$row2['position'].'</td></tr>';
							$curr_row-=$row2['height'];
							break;
						}else{
		                	                echo '<tr><td>'.$ii.'</td><td></td><td>'.$ii.'</td></tr>';
							$curr_row-=1;
						}
					}
                	        }
                	        echo '</tbody></table>';
                	}
                	$queryTimeEnd = microtime(true);
                	$queryProcessTime = $queryTimeEnd-$queryTimeStart;
                	//echo '<br>Rows Returned: '.$rowCounter.' rows.<br>Query Process Time: '.round($queryProcessTime,3).' seconds.';
			//echo '<br><br>Connection closed.';
		}
		mysql_close($con_dcim);
		mysql_close($con_nocdash);
	}
?>

<!DOCTYPE HTML>
<html>
	<head>
		<?PHP getHtmlHead(); ?>
		<style>
			#rackContainer { font-size:12px !important; }
			td { padding:0px !important; }
		</style>
	</head>

	<body>
		<?PHP getHeader(); ?>
                
		<div id="rackContainer" class="container">
			<div><?PHP getView('alerts-by-rack'); ?></div>
		</div>

                <script>
                        $(window).load(function () {
				var tz = jstz.determine();
				$(document).attr('title', $(document).attr('title')+' - Alerts By Rack');
				$('#nav-active-reports').addClass('active');

				$('.rackToggle').popover({placement:"right"});

                                setInterval(function(){
                                
				}, 30000);
                        });
                </script>
		
		<?PHP addFooter(); ?>
	</body>
</html>
