<?PHP
	$path=$_SERVER['DOCUMENT_ROOT'];
	include $path.'/php/common.php';
?>

<!DOCTYPE HTML>
<html>
	<head>
		<?PHP getHtmlHead(); ?>
	</head>

	<body>
		<?PHP getHeader(); ?>
		<div id="pageContainer" class="container">
			Notifications page.
		</div>

		<script>
			$(document).ready(function(){
			
			});
                </script>
		
		<?PHP addFooter(); ?>
	</body>
</html>
