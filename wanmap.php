<?PHP
	$path=$_SERVER['DOCUMENT_ROOT'];
	require_once $path.'/php/dbconn.php';
	require_once 'HTTP/Request2.php';
	include $path.'/php/common.php';
?>

<!DOCTYPE HTML>
<html>
	<head>
		<?PHP getHtmlHead(); ?>
	</head>

	<body style="background-color:black;">
		<?PHP getHeader(); ?>
                <div id="pageContainer" class="container" style="text-align:center;padding-top:35px;">
			<?PHP //getWanMap(); ?>
		</div>
		<?PHP addFooter(); ?>
		
		<script>
                        $(document).ready(function () {
				$(document).attr('title', $(document).attr('title')+' - WAN Map');
				$('#nav-active-network').addClass('active');
				
				var wanmapView = window.location.search;
				wanmapView = wanmapView.split("=");
				wanmapView = wanmapView[1];
				if(wanmapView == 'nocwall'){
					$(' body ').css('width','1920px');
					$(' body ').css('height','1080px');
					$(' #pageContainer ').css('width','1900');
					$(' .navbar ').css('display','none');
					$(' #footer ').css('display','none');
					$(' #svgContainer ').css('width','1800');
					$(' #svgContainer ').css('height','1000');
					$(' #underseaBreak ').css('top','120px');
				}

                                setInterval(function(){
					location.reload();
                                }, 60000);
                        });
                </script>
	</body>
</html>
