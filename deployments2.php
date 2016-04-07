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
	</head>

	<body>
		<?PHP getHeader(); ?>
		<div id="pageContainer" class="container">
			<div class="panel panel-default">
				<div class="panel-heading">Set Maintenance Mode</div>
				<div class="panel-body">
				<!--
			        <select name="scopeChoice">
					<option value=" "></option>
					<option value="only">Only</option>
					<option value="all">All</option>
				</select>
				-->
				<form>
				<!-- <input type="text" name="hostname"><br> -->
				<table>
					<thead><th></th></thead>
					<tbody>
						<tr>
							<td>
								<input type="checkbox" name="checkboxNode" value="ams01">&nbsp;AMS01<br>
								<input type="checkbox" name="checkboxNode" value="ams02">&nbsp;AMS02<br>
								<input type="checkbox" name="checkboxNode" value="iad01">&nbsp;IAD01<br>
								<input type="checkbox" name="checkboxNode" value="iad02">&nbsp;IAD02<br>
								<input type="checkbox" name="checkboxNode" value="iad03">&nbsp;IAD03<br>
								<input type="checkbox" name="checkboxNode" value="iad04">&nbsp;IAD04<br>
								<input type="checkbox" name="checkboxNode" value="iad05">&nbsp;IAD05<br>
								<input type="checkbox" name="checkboxNode" value="iad06">&nbsp;IAD06<br>
								<input type="checkbox" name="checkboxNode" value="sjc01">&nbsp;SJC01<br>
								<input type="checkbox" name="checkboxNode" value="sjc02">&nbsp;SJC02<br>
								<input type="checkbox" name="checkboxNode" value="sjc03">&nbsp;SJC03<br>
								<input type="checkbox" name="checkboxNode" value="sjc04">&nbsp;SJC04<br>
							</td>
							<td>
								<input type="radio" name="appStack" value="dma">&nbsp;DMA<br>
								<input type="radio" name="appStack" value="rtb">&nbsp;RTB<br>
								<input type="radio" name="appStack" value="nsy">&nbsp;NSY<br>
								<input type="radio" name="appStack" value="dcs">&nbsp;DCS<br>
							</td>
						</tr>
					</tbody>
				</table>
					<br><input id="submitMaintMode" class="btn btn-sm btn-default" type="submit" value="Submit">
				</form>
				</div>
			</div>	
		</div>
	</body>
</html>
