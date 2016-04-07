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
                        function activityEvents(jsonEvents,activityLength){
				var tz = jstz.determine();
                                //console.log(jsonEvents);
                                //console.log(jsonEvents.length);
                                if(jsonEvents.length==0){
                                        $('#activityEventsTable_'+activityLength).append("<tr class='event'><td colspan=8'>No activity for the past 12 hours</td></tr>");
                                } else {
                                        for(var i=0; i<jsonEvents.length; i++) {
						if(jsonEvents[i].Source=='jira'){
							if(jsonEvents[i].JiraAssignee==''){
								jsonEvents[i].JiraAssignee='None';
							}
							var bb = 'Latest Comment by <b>'+jsonEvents[i].JiraLastCommentAuthor+'</b>: <i>'+jsonEvents[i].JiraLastComment+'</i>';
							if(jsonEvents[i].JiraLastCommentAuthor=='System'){
								var bb = 'No comments in ticket.';
							}
							$('#activityEventsTable_'+activityLength).append("<div class='panel panel-default col-lg-12 col-md-12 col-sm-12 activityItem-jira' style='background:#ddddff;'><div class='panel-body'><div class='event'><b><u>"+moment.utc(jsonEvents[i].Date).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss')+"</u></b></div><div class='event'><a href='http://jira.cnvrmedia.net/browse/"+jsonEvents[i].JiraNumber+"' target='_blank' data-toggle='tooltip' title='Open in JIRA' data-placement='right'>"+jsonEvents[i].JiraNumber+": "+jsonEvents[i].Event+"</a></div><div class='event'>Status: <b>"+jsonEvents[i].JiraStatus+"</b> | Owner: <b>"+jsonEvents[i].JiraAssignee+"</b></div><div class='event'>"+bb+"</div></div></div>");
						}
						if(jsonEvents[i].Source=='sentmail'){
							var mailRecipients = "<div class='event'>To:&nbsp;"+jsonEvents[i].sentTo+"</div>";
							if(!jsonEvents[i].sentCC==""){
								mailRecipients+="<div class='event'>CC:&nbsp;"+jsonEvents[i].sentCC+"</div>";
							}
							if(!jsonEvents[i].sentBCC==""){
								mailRecipients+="<div class='event'>BCC:&nbsp;"+jsonEvents[i].sentBCC+"</div>";
							}
	                                                $('#activityEventsTable_'+activityLength).append("<div class='panel panel-default col-lg-12 col-md-12 col-sm-12 activityItem-email' style='background:#ddffdd;'><div class='panel-body'><div class='event'><b><u>"+moment.utc(jsonEvents[i].Date).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss')+"</u></b></div><div class='event'>NOC Email Sent:&nbsp;<a href='viewEmail.php?mailID="+jsonEvents[i].mailID+"'>"+jsonEvents[i].Event+"</a></div>"+mailRecipients+"<div class='event'>Sent By:&nbsp;<b>"+jsonEvents[i].sentBy+"</b></div></div></div>");
						}
						if(jsonEvents[i].Source=='fireBoard'){
							switch(jsonEvents[i].eventType){
                                                        	case 'maint':
                                                                	jsonEvents[i].eventType = 'Maintenance';
	                                                                break;
        	                                                case 'fire':
                	                                                jsonEvents[i].eventType = 'Fire';
                        	                                        break;
                                	                        case 'deploy':
                                        	                        jsonEvents[i].eventType = 'Deployment';
                                                	                break;
                                                        	case 'canary':
	                                                                jsonEvents[i].eventType = 'Canary';
        	                                                        break;
                	                                }

							if(jsonEvents[i].eventActive=='1'){//if fireBoard event active
		                                                $('#activityEventsTable_'+activityLength).append("<div class='panel panel-default col-lg-12 col-md-12 col-sm-12 activityItem-fireBoard' style='background:#ffdddd;'><div class='panel-body'><div class='event'><b><u>"+moment.utc(jsonEvents[i].Date).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss')+"</u></b></div><div class='event'>FireBoard Event Added: <a href='firesDash.php'>"+jsonEvents[i].Event+"</a></div><div class='event'>Event Type: <b>"+jsonEvents[i].eventType+"</b> | Posted By: <b>"+jsonEvents[i].createdBy+"</b></div></div></div>");
							}else{//if fireboard event closed
		                                                $('#activityEventsTable_'+activityLength).append("<div class='panel panel-default col-lg-12 col-md-12 col-sm-12 activityItem-fireBoard' style='background:#ffdddd;'><div class='panel-body'><div class='event'><b><u>"+moment.utc(jsonEvents[i].Date).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss')+"</u></b></div><div class='event'>FireBoard Event Concluded: <a href='edit.php?type=firesDash&id="+jsonEvents[i].eventID+"'>"+jsonEvents[i].Event+"</a></div><div class='event'>Event Type: <b>"+jsonEvents[i].eventType+"</b> | Removed By: <b>"+jsonEvents[i].closedBy+"</b></div></div></div>");
							}
						}
						//$('#activityEventsTable_'+activityLength).append("<tr class='event'><td></td></tr><tr class='event'><td></td></tr>");
						$('#activity-'+activityLength+'-badge').text(i+1);
                                        }
                                }
                        }

                        $(document).ready(function () {
				$(document).attr('title', $(document).attr('title')+' - Activity Board');
				$('#nav-active-activity').addClass('active');
                                var jsonEvents = getJSONData('activity-12h');
                                activityEvents(jsonEvents,'12h');
                                jsonEvents = getJSONData('activity-24h');
                                activityEvents(jsonEvents,'24h');
                                jsonEvents = getJSONData('activity-7d');
                                activityEvents(jsonEvents,'7d');

				function activityItemCheck(){
					if( $(' .activityItem-fireBoard ').is(":visible") ){
						$(' #activityLegend-fireBoard-color ').css('background-color','#ffdada');
					}else{
						$(' #activityLegend-fireBoard-color ').css('background-color','#ffffff');
					}
					if( $(' .activityItem-email ').is(":visible") ){
						$(' #activityLegend-email-color ').css('background-color','#ddffdd');
					}else{
						$(' #activityLegend-email-color ').css('background-color','#ffffff');
					}
					if( $(' .activityItem-jira ').is(":visible") ){
						$(' #activityLegend-jira-color ').css('background-color','#ddddff');
					}else{
						$(' #activityLegend-jira-color ').css('background-color','#ffffff');
					}
				}

				$('a[data-toggle="tab"]').on('shown.bs.tab',function(e){
					activityItemCheck();
				});

				$(' .activityLegend-fireBoard ').on('click',function(){
					$(' .activityItem-fireBoard ').toggle();
					if( $(' .activityItem-fireBoard ').is(":visible") ){
						$(' #activityLegend-fireBoard-color ').css('background-color','#ffdada');
					}else{
						$(' #activityLegend-fireBoard-color ').css('background-color','#ffffff');
					}
				});

				$(' .activityLegend-email ').on('click',function(){
					$(' .activityItem-email ').toggle();
					if( $(' .activityItem-email ').is(":visible") ){
						$(' #activityLegend-email-color ').css('background-color','#ddffdd');
					}else{
						$(' #activityLegend-email-color ').css('background-color','#ffffff');
					}
				});

				$(' .activityLegend-jira ').on('click',function(){
					$(' .activityItem-jira ').toggle();
					if( $(' .activityItem-jira ').is(":visible") ){
						$(' #activityLegend-jira-color ').css('background-color','#ddddff');
					}else{
						$(' #activityLegend-jira-color ').css('background-color','#ffffff');
					}
				});

                                /*setInterval(function(){
                                        jsonEvents = getJSONData('activity-12h');
                                        $(".event").remove();
					$("#activityEventsTable_12h div").remove();
					$("#activityEventsTable_24h div").remove();
					$("#activityEventsTable_7d div").remove();
                                        $("#submitDiv").remove();
                                        activityEvents(jsonEvents,'12h');
                                        jsonEvents = getJSONData('activity-24h');
                                        activityEvents(jsonEvents,'24h');
                                        jsonEvents = getJSONData('activity-7d');
                                        activityEvents(jsonEvents,'7d');
                                }, 30000);*/
				
				$('#activity-board-tabs a[href="#activity-board-container-1"]').tab('show');
				activityItemCheck();
                        });

			
			function getJiraUser(jiraUsername){
				var jiraUserObj = $.parseJSON(
					jQuery.ajax({
						url: 'http://jira.cnvrmedia.net/rest/api/latest/user?username='+jiraUsername,
						async: false,
						dataType: 'json'
					}).responseText
				);
				return jiraUserObj[0].displayName;
			}

                </script>
	</head>

	<body>
		<?PHP getHeader(); ?>
		
                <div id="pageContainer" class="container">
			<div class="panel panel-default" style="background:white;">
                                <ul id="activity-board-tabs" class="nav nav-tabs nav-justified">
                                        <li id="activity-board-tabs-1" class="active"><a href="#activity-board-container-1" data-toggle="tab">Last 12 Hours&nbsp;&nbsp;<span class="badge" id="activity-12h-badge">0</span></a></li>
                                        <li id="activity-board-tabs-2"><a href="#activity-board-container-2" data-toggle="tab">Last 24 Hours&nbsp;&nbsp;<span class="badge" id="activity-24h-badge">0</span></a></li>
                                        <li id="activity-board-tabs-3"><a href="#activity-board-container-3" data-toggle="tab">Last 7 Days&nbsp;&nbsp;<span class="badge" id="activity-7d-badge">0</span></a></li>
                                </ul>

				<div id="activity-board-container" class="tab-content" style="padding:10px;border-left:#ddd 1px solid;border-bottom:#ddd 1px solid;border-right:#ddd 1px solid;">
					<div style="margin-bottom:5px;border:1px solid #eee;border-radius:5px;">
						<div title="Hide/Show FireBoard events" class="btn activityLegend-fireBoard" style="padding:5px 0px;"><div id="activityLegend-fireBoard-color" style="width:5px;height:5px;border:1px #ddd solid;background-color:#ffdada;padding:9px;display:inline-block;vertical-align:middle;"></div><div style="display:inline-block;vertical-align:middle;margin-top:3px;margin-right:5px;">&nbsp;FireBoard</div></div>
						<div title="Hide/Show email sent" class="btn activityLegend-email" style="padding:5px 0px;"><div id="activityLegend-email-color" style="width:5px;height:5px;border:1px #ddd solid;background-color:#ddffdd;padding:9px;display:inline-block;vertical-align:middle;"></div><div style="display:inline-block;vertical-align:middle;margin-top:3px;margin-right:5px;">&nbsp;Email</div></div>
						<div title="Hide/Show Jira ticket updates" class="btn activityLegend-jira" style="padding:5px 0px;"><div id="activityLegend-jira-color" style="width:5px;height:5px;border:1px #ddd solid;background-color:#ddddff;padding:9px;display:inline-block;vertical-align:middle;"></div><div style="display:inline-block;vertical-align:middle;margin-top:3px;margin-right:5px;">&nbsp;Jira</div></div>
					</div>
					<div id="activity-board-container-1" class="row tab-pane fade in active" style="margin-left:0px !important;margin-right:0px !important;">
						<div id="activityEventsTable_12h">
						</div>
					</div>
					<div id="activity-board-container-2" class="row tab-pane fade" style="margin-left:0px !important;margin-right:0px !important;">
						<div id="activityEventsTable_24h">
						</div>
					</div>
					<div id="activity-board-container-3" class="row tab-pane fade" style="margin-left:0px !important;margin-right:0px !important;">
						<div id="activityEventsTable_7d">
						</div>
					</div>
				</div>
			</div>
		</div>

		<?PHP addFooter(); ?>
	</body>
</html>
