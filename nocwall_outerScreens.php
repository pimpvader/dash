<?PHP
	//$path=$_SERVER['DOCUMENT_ROOT'];
	//include $path.'/php/common.php';
	require 'HTTP/Request2.php';
	//echo $path.='/php/common.php';'
	$path = $_SERVER['SERVER_NAME'];
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>NOC Dash</title>
		<?PHP
			echo '<link href="http://'.$path.'/css/jquery-ui.css" rel="stylesheet" type="text/css" />';
			echo '<link href="http://'.$path.'/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />';
			echo '<link href="http://'.$path.'/css/fullcalendar.css" rel="stylesheet" type="text/css" />';
			echo '<link href="http://'.$path.'/css/fullcalendar.print.css" rel="stylesheet" type="text/css" media="print" />';
			echo '<link href="http://'.$path.'/css/jquery.datetimeentry.css" rel="stylesheet" type="text/css" />';
			
			echo '<script src="http://'.$path.'/js/jquery.js"></script>';
			echo '<script src="http://'.$path.'/js/jquery.plugin.min.js"></script>';
			echo '<script src="http://'.$path.'/js/bootstrap.min.js"></script>';
			echo '<script src="http://'.$path.'/js/jquery-ui.js"></script>';
			echo '<script src="http://'.$path.'/js/fullcalendar.min.js"></script>';
			echo '<script src="http://'.$path.'/js/moment.min.js"></script>';
			echo '<script src="http://'.$path.'/js/moment-timezone.min.js"></script>';
			echo '<script src="http://'.$path.'/js/moment-timezone-data.js"></script>';
		?>
		<style>
			body {
				width: 100%;
				height: 100%;
				/*overflow: hidden;*/
				margin: 0px;
			}
			#pageContainer {
				width: 3840px;
				height: 2160px;
				margin: 0px;
				border: red 0px solid;
			}
			.screen {
				width: 1920px;
				height: 1080px;
				margin: 0px;
				border: red 0px solid;
			}
			#top-left-screen {
				position: absolute;
				top: 0px;
				left: 0px;
				background:green;
			}
			#top-right-screen {
				background:blue;
				position: absolute;
				top: 0px;
				left: 1920px;
			}
			#bottom-left-screen {
				background:red;
				position: absolute;
				top: 1080px;
				left: 0px;
			}
			#bottom-right-screen {
				background:yellow;
				position: absolute;
				top: 1080px;
				left: 1920px;
			}
		</style>
	</head>

	<body>
                <div id="pageContainer" class="container">
			<div id="top-left-screen" class="screen">

			</div>
		
			<div id="top-right-screen" class="screen">

			</div>
			
			<div id="bottom-left-screen" class="screen">

			</div>
			
			<div id="bottom-right-screen" class="screen">

			</div>
                </div>

		<script>
                        var url = 'controller.php?screen=outer-top-left';
                        
                        function getJSONData(){
                                
                                var obj = $.parseJSON(
                                        jQuery.ajax({
                                        url: url, 
                                        async: false,
                                        dataType: 'json'
                                        }).responseText
                                );
                                
                                return obj;
                        }
                        
                        function pushIssues(Issues){
                                console.log(Issues);
                                console.log(Issues.length);
                                var fireCount=0,maintCount=0,canaryCount=0,deployCount=0;
                        
                                for(var i=0; i<Issues.length; i++) {
                                
                                        if(Issues[i].eventType == 'fire'){
                                                if(fireCount==0){
                                                        $('#titleF').show();
                                                        $('#fires').show();
                                                        fireCount=1;
                                                }
                                                $('#fires').append("<div class='issue'>"+Issues[i].eventDesc+"</div>");
                                        }
                                                
                                        if(Issues[i].eventType == 'maint'){
                                                if(maintCount==0){
                                                        $('#titleM').show();
                                                        $('#maint').show();
                                                        maintCount=1;
                                                }
                                                $('#maint').append("<div class='issue'>"+Issues[i].eventDesc+"</div>");
                                        }

                                        if(Issues[i].eventType == 'canary'){
                                                if(canaryCount==0){
                                                        $('#titleC').show();
                                                        $('#canary').show();
                                                        canaryCount=1;
                                                }
                                                $('#canary').append("<div class='issue'>"+Issues[i].eventDesc+"</div>")
;
                                        }

                                        if(Issues[i].eventType == 'deploy'){
                                                if(deployCount==0){
                                                        $('#titleD').show();
                                                        $('#deploy').show();
                                                        deployCount=1;
                                                }
                                                $('#deploy').append("<div class='issue'>"+Issues[i].eventDesc+"</div>")
;
                                        }
                                                
                                }
                                $('.issue').css({"color":"white","font-size":"42px"});
                        }
                        
                        $(document).ready(function () {
				$(document).attr('title', $(document).attr('title')+' - NOC Wall [OUTER]');
                        
                                var Issues = getJSONData();
                                pushIssues(Issues);
                                
                                setInterval(function(){
                                        Issues = getJSONData();
                                        
                                        $(".issue").remove();
                                        $(".issueTitle").hide();
                                        console.log('Refreshed list.');
                                        
                                        pushIssues(Issues);
                                }, 10000);
                        });
                </script>

	</body>
</html>
