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
                <div id="pageContainer" class="container">
			<div id="homeLeftPane" class="container col-lg-6">
				<?PHP getHomeViewPane('deviceList'); ?>
				<?PHP getHomeViewPane('appList'); ?>
				<!-- <?PHP getHomeViewPane('changeCalendar'); ?> -->
			</div>
			<div id="homeRightPane" class="container col-lg-6">
				<div class="panel panel-default" style="padding-left:0px;padding-right:0px;">
					<div class="panel-heading">WAN Map</div>
					<div class="panel-body" style="max-height:525px;background-color:#555;"><?PHP getWanMap(); ?></div>
				</div>
				<!-- <?PHP getHomeViewPane('activeAlerts'); ?> -->
				<!-- <?PHP getHomeViewPane('augury'); ?> -->
				<!-- <?PHP getHomeViewPane('ganglia'); ?> -->
				<!-- <?PHP getHomeViewPane('hwAlerts'); ?> -->
				<!-- <?PHP getHomeViewPane('firesDash'); ?> -->
				<!-- <?PHP getHomeViewPane('jira'); ?> -->
				<!-- <?PHP getHomeViewPane('maint'); ?> -->
			</div>
                </div>

                <script>
			$(document).ready(function () {
				$(document).attr('title', $(document).attr('title')+' - Home');
				$('#nav-active-exec').addClass('active');
                                jsonDataset = getJSONData('firesDash');
				if(jsonDataset==0){
                                	$('#home-badges-firesDash').text(0);
                                }else{
					$('#home-badges-firesDash').text(jsonDataset.length);
				}
                                
				jsonDataset = getJSONData('maint-24h');
				if(jsonDataset==0){
	                                $('#home-badges-maint').text(0);
				}else{
                                	$('#home-badges-maint').text(jsonDataset.length);
				}
                                
				jsonDataset = getJSONData('ganglia');
				if(jsonDataset==0){
	                                $('#home-badges-ganglia').text(0);
				}else{
                                	$('#home-badges-ganglia').text(jsonDataset.length);
				}
				
				jsonDataset = getJSONData('jira-netops-all');
				if(jsonDataset==0){
                                	$('#home-badges-jira-netops').text(0);
				}else{
                                	$('#home-badges-jira-netops').text(jsonDataset.length);
				}

				jsonDataset = getJSONData('jira-change');
				if(jsonDataset==0){
					$('#home-badges-jira-change').text(0);
				}else{
					$('#home-badges-jira-change').text(jsonDataset.length);
				}
				
                                setInterval(function(){
                                        console.log('index.php JSON refresh for badges on home panes');
					jsonDataset = getJSONData('firesDash');
	                                if(jsonDataset==0){
        	                                $('#home-badges-firesDash').text(0);
                	                }else{
                        	                $('#home-badges-firesDash').text(jsonDataset.length);
                                	}

	                                jsonDataset = getJSONData('maint-24h');
        	                        if(jsonDataset==0){
                	                        $('#home-badges-maint').text(0);
                        	        }else{
                                	        $('#home-badges-maint').text(jsonDataset.length);
	                                }

        	                        jsonDataset = getJSONData('ganglia');
                	                if(jsonDataset==0){
                        	                $('#home-badges-ganglia').text(0);
	                                }else{
        	                                $('#home-badges-ganglia').text(jsonDataset.length);
                	                }
				
					jsonDataset = getJSONData('jira-netops-all');
					if(jsonDataset==0){
        	                        	$('#home-badges-jira-netops').text(0);
					}else{
                        	        	$('#home-badges-jira-netops').text(jsonDataset.length);
					}

					jsonDataset = getJSONData('jira-change');
					if(jsonDataset==0){
						$('#home-badges-jira-change').text(0);
					}else{
						$('#home-badges-jira-change').text(jsonDataset.length);
					}
                                }, 30000);
                        });
			
			$(window).load(function(){
				setTimeout(loadSolarWindsData,1000);
			});
                </script>

		<script>
			$(document).ready(function(){
				$('tr.homePane_deviceList_deviceType').click(function(){
					$(this).nextUntil('tr.homePane_deviceList_deviceType').css('display', function(i,v){
						return this.style.display === 'table-row' ? 'none' : 'table-row';
					});
				});
			});

			$('#home-changeCalendar-pane').ready(function(){
        			$('#home-changeCalendar-pane').fullCalendar({
			                editable: false,
					allDayDefault: false,
					height: 550,
			                header: {
			                        left: 'prev,today,next',
			                        center: 'title',
			                        right: 'month,agendaWeek,agendaDay'
			                },
			                defaultView: 'agendaDay',
			                eventSources:[{
						url: "list.php?getList=jira-change-calendar",
						color: "#336699",
						textColor: "white"
					},{
						url: "list.php?getList=jira-netops-calendar",
						color: "#009900",
						textColor: "white"
					}],
					eventClick: function(event) {
						window.open('http://jira.cnvrmedia.net/browse/'+event.id);
						return false;
        		        	}
		        	});

				if( $(window).width() < 480 ){
					$('#home-changeCalendar-pane').fullCalendar('changeView','agendaDay');
				}else{
					$('#home-changeCalendar-pane').fullCalendar('changeView','agendaWeek');
				}
				
				$( window ).resize(function() {
					//console.log( $( window ).width() );
					if( $(window).width() < 480 ){
						$('#home-changeCalendar-pane').fullCalendar('changeView','agendaDay');
					}else{
						$('#home-changeCalendar-pane').fullCalendar('changeView','agendaWeek');
					}
				});
			});
		</script>

		<?PHP addFooter(); ?>
	</body>
</html>
