<?PHP
	$path=$_SERVER['DOCUMENT_ROOT'];
	require_once $path.'/php/dbconn.php';
	include $path.'/php/common.php';

	session_write_close();

?>

<!DOCTYPE HTML>
<html>
	<head>
		<?PHP getHtmlHead(); ?>

		<script type="text/javascript">
			$(document).ready(function() {
    				$('#selectAll').click(function(event) {  //on click 
        				if(this.checked) { // check select status
            					$('.dataCenter').each(function() { //loop through each checkbox
                					this.checked = true;  //select all checkboxes with class "checkbox1" 
						});
					}
        				else{
            					$('.dataCenter').each(function() { //loop through each checkbox
                					this.checked = false; //deselect all checkboxes with class "checkbox1"                       
            					});         
        				}
    				});
			});
		</script> 
		<script type="text/javascript">
                        $(document).ready(function() {
				$('.dataCenter').click(function(event) {  //on click
					if(this.checked === false) { // check select status
						$('#selectAll').each(function() {
                                                	this.checked = false;  //deselect selectAll
                                                });
					}	
                                });
			});
		</script>
	</head>

	<body>
		<?PHP getHeader(); ?>
		<div id="pageContainer" class="container">
			<div class="panel panel-default">
				<div class="panel-heading">Set MaintenanceMode in SolarWinds</div>
				<div class="panel-body">
			        <form method="post" name="submitForm" action="deployments.php">
				<div class="checkbox" style="margin-top:0px;">
					<label><input type="checkbox" id="selectAll" name="selectAll"/>Select All</label>
				</div>
				<div class="checkbox" style="margin-left:20px;">
                                	<label><input class="dataCenter" type="checkbox" name="dataCenter_checked[]" value="iad01">iad01</label>
				</div>
				<div class="checkbox" style="margin-left:20px;">
                                	<label><input class="dataCenter" type="checkbox" name="dataCenter_checked[]" value="iad02">iad02</label>
				</div>
				<div class="checkbox" style="margin-left:20px;">
                                	<label><input class="dataCenter" type="checkbox" name="dataCenter_checked[]" value="iad03">iad03</label>
				</div>
				<div class="checkbox" style="margin-left:20px;">
                                	<label><input class="dataCenter" type="checkbox" name="dataCenter_checked[]" value="iad04">iad04</label>
				</div>
				<div class="checkbox" style="margin-left:20px;">
                                	<label><input class="dataCenter" type="checkbox" name="dataCenter_checked[]" value="iad05">iad05</label>
				</div>
				<div class="checkbox" style="margin-left:20px;">
                                	<label><input class="dataCenter" type="checkbox" name="dataCenter_checked[]" value="iad06">iad06</label>
				</div>
				<div class="checkbox" style="margin-left:20px;">
                                	<label><input class="dataCenter" type="checkbox" name="dataCenter_checked[]" value="sjc01">sjc01</label>
				</div>
				<div class="checkbox" style="margin-left:20px;">
                                	<label><input class="dataCenter" type="checkbox" name="dataCenter_checked[]" value="sjc02">sjc02</label>
				</div>
				<div class="checkbox" style="margin-left:20px;">
                                	<label><input class="dataCenter" type="checkbox" name="dataCenter_checked[]" value="sjc03">sjc03</label>
				</div>
				<div class="checkbox" style="margin-left:20px;">
                                	<label><input class="dataCenter" type="checkbox" name="dataCenter_checked[]" value="sjc04">sjc04</label>
				</div>
				<div class="checkbox" style="margin-left:20px;">
                                	<label><input class="dataCenter" type="checkbox" name="dataCenter_checked[]" value="ams01">ams01</label>
				</div>
				<div class="checkbox" style="margin-left:20px;">
                                	<label><input class="dataCenter" type="checkbox" name="dataCenter_checked[]" value="ams02">ams02</label>
				</div>

				<div class="radio">
					<label><input type="radio" name="appStack" value="dma" required>DMA</label>
				</div>
				<div class="radio">
					<label><input type="radio" name="appStack" value="rtb" required>RTB</label>
				</div>
				<div class="radio">
					<label><input type="radio" name="appStack" value="nsy" required>NSY</label>
				</div>
				<div class="radio">
					<label><input type="radio" name="appStack" value="dcs" required>DCS</label>
				</div>

				<input id="submitMainMode" class="btn btn-sm btn-default" type="submit" value="Enable">
				</form><br>
				
				<?PHP 
					if( !empty($_POST) ){ 
						//echo 'form submitted:<br>';
						//var_export($_POST);
						$swindsQuery='UPDATE nodes SET maintenanceMode=1 WHERE ';
						if( !isset($_POST[selectAll]) ){
							$dataCenters=$_POST[dataCenter_checked];
							$swindsQuery.="(";
							for($ii=0;$ii<count($dataCenters);$ii++){
								$swindsQuery.="sysname LIKE '%".$dataCenters[$ii]."%'";
								if($ii+1<count($dataCenters)){$swindsQuery.=" OR ";}
							}
							$swindsQuery.=") AND ";
						}
						$swindsQuery.="sysname LIKE '%".$_POST[appStack]."%';";
						echo 'Query: '.$swindsQuery;
					}
				?>
				</div>
			</div>	
		</div>
	</body>
</html>
