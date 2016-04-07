<?PHP
	$path=$_SERVER['DOCUMENT_ROOT'];
	require_once $path.'/php/dbconn.php';
	include $path.'/php/common.php';
?>

<!DOCTYPE HTML>
<html>
	<head>
		<?PHP getHtmlHead(); ?>
	</head>

	<body>
		<?PHP getHeader(); ?>
                <div id="pageContainer" class="container" style="min-width:1370px;max-width:1370px;background-image: url('img/bg_content_gradient.gif');background-repeat: repeat-x;background-color:white;">
                	<iframe src="https://nms.vclk.net/Orion/DetachResource.aspx?ViewID=151&ResourceID=1576&NetObject=&currentUrl=aHR0cHM6Ly9ubXMudmNsay5uZXQvT3Jpb24vU3VtbWFyeVZpZXcuYXNweD9WaWV3SUQ9MTUx" 
                                style="width:100%;min-height:2250px;" frameborder=0 scrolling=no
                        />
			</iframe>
		</div>

                <script>
			$(document).ready(function () {
				$(document).attr('title', $(document).attr('title')+' - Circuit Status');
				$('#nav-active-network').addClass('active');
				
                                setInterval(function(){
                                
				}, 30000);
                        });
			
			$(window).load(function(){
			
			});
                </script>

		<?PHP addFooter(); ?>
	</body>
</html>
