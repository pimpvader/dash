<?PHP
	$path=$_SERVER['DOCUMENT_ROOT'];
	require_once $path.'/php/dbconn.php';
	include $path.'/php/common.php';
?>

<!DOCTYPE HTML>
<html>
	<head>
		<?PHP getHtmlHead(); ?>
		<script>
			$(document).ready(function () {
				$(document).attr('title', $(document).attr('title')+' - My Profile');
				var tz = jstz.determine();
				$('#userTZ').append(tz.name());
				setInterval(function(){
				
				}, 60000);
			});
		</script>
	</head>

	<body>
		<?PHP getHeader(); ?>
		
                <div id="pageContainer" class="container">
			<div class="panel panel-default">
				<div class="panel-body">
					Name: <?PHP echo $_SESSION['displayName']; ?><br>
					Username: <?PHP echo $_SESSION['username']; ?><br>
					Security Group: <?PHP echo $_SESSION['usergroup']; ?><br>
					Timezone: <span id='userTZ'></span>
				</div>
			</div>
		</div>

		<?PHP addFooter(); ?>
	</body>
</html>
