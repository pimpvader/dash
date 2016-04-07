<?PHP
	$path=$_SERVER['DOCUMENT_ROOT'];
	require_once $path.'/php/dbconn.php';
	include $path.'/php/common.php';
	require_once 'HTTP/Request2.php';
?>

<!DOCTYPE HTML>
<html>
	<head>
		<?PHP getHtmlHead(); ?>
	</head>

	<body>
		<?PHP getHeader(); ?>
		<div id="pageContainer" class="container">
			<div id="jiraLeftPane" class="container col-6 col-sm-6 col-lg-6">
				<div id="esgcmQueue" class="col-12 col-sm-12 col-lg-12 panel panel-default" style="padding-left:0px;padding-right:0px;">
					<div class="panel-heading"><a href="http://jira.cnvrmedia.net/browse/ESGCM" title="Open ESGCM queue" target="_blank">ESGCM Queue</a><div style="float:right;"><div style="width: 5px; height: 5px; border: 1px solid #ddd; background-color: #ffdada; padding: 9px; display: inline-block; vertical-align: middle;"></div><div style="display:inline-block;vertical-align:middle;margin-top:3px;margin-right:5px;">&nbsp;<a href="http://jira.cnvrmedia.net/issues/?jql=project%20%3D%20ESGCM%20and%20(%22Date%20%26%20Time%22%20%3E%3D%20startOfDay()%20and%20%22Date%20%26%20Time%22%20%3C%3D%20endOfDay())" title="View today's ESG change tickets in Jira" target="_blank">Today</a></div></div></div>
					<div class="panel-body">
						<ul id="esgcmQueueList" style="list-style-type:none;margin-left:-40px;">
						</ul>
					</div>
				</div>
				<div id="nocQueue" class="col-12 col-sm-12 col-lg-12 panel panel-default" style="padding-left:0px;padding-right:0px;">
					<div class="panel-heading"><a href="http://jira.cnvrmedia.net/issues/?jql=project%20%3D%20NOC%20AND%20status%20not%20in%20(closed%2C%20resolved)%20ORDER%20BY%20updated%20DESC%2C%20priority%20DESC" title="Open NOC Queue" target="_blank">NOC Queue</a><div style="float:right;"><div style="width: 5px; height: 5px; border: 1px solid #ddd; background-color: #ffffda; padding: 9px; display: inline-block; vertical-align: middle;"></div><div style="display:inline-block;vertical-align:middle;margin-top:3px;margin-right:5px;">&nbsp;On Hold</div><div style="width: 5px; height: 5px; border: 1px solid #ddd; background-color: #ffdada; padding: 9px; display: inline-block; vertical-align: middle;"></div><div style="display:inline-block;vertical-align:middle;margin-top:3px;margin-right:5px;">&nbsp;Not Acknowledged</div><div style="width: 5px; height: 5px; border: 1px solid #ddd; background-color: #ddddff; padding: 9px; display: inline-block; vertical-align: middle;"></div><div style="display:inline-block;vertical-align:middle;margin-top:3px;margin-right:5px;">&nbsp;Unassigned</div></div></div>
					<div class="panel-body">
						<ul id="nocQueueList" style="list-style-type:none;margin-left:-40px;">
						</ul>
					</div>
				</div>
			</div>

			<div id="jiraRightPane" class="container col-6 col-sm-6 col-lg-6">
				<div id="myIssues" class="col-12 col-sm-12 col-lg-12 panel panel-default" style="padding-left:0px;padding-right:0px;">
					<div class="panel-heading"><a href="http://jira.cnvrmedia.net/issues/?jql=(assignee=<?PHP if(isset($_SESSION['username'])){echo $_SESSION['username'];} ?> or reporter=<?PHP if(isset($_SESSION['username'])){echo $_SESSION['username'];} ?>) and status not in (resolved,closed,done,complete)" title="View my issues in Jira" target="_blank">My Jira Issues (Opened or Assigned)</a><div style="float:right;"><div style="width: 5px; height: 5px; border: 1px solid #ddd; background-color: #ffffda; padding: 9px; display: inline-block; vertical-align: middle;"></div><div style="display:inline-block;vertical-align:middle;margin-top:3px;margin-right:5px;">&nbsp;On Hold</div></div></div>
					<div class="panel-body">
						<ul id="myIssuesList" style="list-style-type:none;margin-left:-40px;">
						</ul>
					</div>
				</div>

				<div id="monitorQueue" class="col-12 col-sm-12 col-lg-12 panel panel-default" style="padding-left:0px;padding-right:0px;">
					<div class="panel-heading"><a href="http://jira.cnvrmedia.net/browse/MONITOR" title="Open MONITOR queue" target="_blank">MONITOR Queue</a><div style="float:right;"><div style="width: 5px; height: 5px; border: 1px solid #ddd; background-color: #ffffda; padding: 9px; display: inline-block; vertical-align: middle;"></div><div style="display:inline-block;vertical-align:middle;margin-top:3px;margin-right:5px;">&nbsp;On Hold</div><div style="width: 5px; height: 5px; border: 1px solid #ddd; background-color: #ffdada; padding: 9px; display: inline-block; vertical-align: middle;"></div><div style="display:inline-block;vertical-align:middle;margin-top:3px;margin-right:5px;">&nbsp;<a href="http://jira.cnvrmedia.net/issues/?jql=project%20%3D%20monitor%20and%20status%20!%3D%20closed%20and%20assignee%20is%20EMPTY" title="View unassigned MONITOR issues in Jira" target="_blank">Unassigned</a></div></div></div>
					<div class="panel-body">
						<ul id="monitorQueueList" style="list-style-type:none;margin-left:-40px;">
						</ul>
					</div>
				</div>
			</div>
		</div>

		<script>
			$(document).ready(function () {
				$(document).attr('title', $(document).attr('title')+' - JIRA Queues');
				$('#nav-active-jira').addClass('active');
				var tz = jstz.determine();

				function generateQueue(Issues,jq){
					if(jq=='jira-netops-30'){ // JIRA NETOPS issues < 30 days old
                                        	$("#monitorQueueList .issue").remove();

	                	                if(Issues.length==0){
        	                	                $('#monitorQueueList').append("<span class='issue'>No issues in NETOPS queue.</span>");
		                                } else {
        		                                for(var i=0; i<Issues.length; i++) {
								var a = moment.utc(Issues[i].issueUpdated).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss');
								var b = moment(a).from();
								Issues[i].issueUpdated = b;
								
								if(Issues[i].issueAssignee==''){
									Issues[i].issueAssignee='None';
								}
        	                	                	$('#monitorQueueList').append("<li class='issue'><a href='http://jira.cnvrmedia.net/browse/"+Issues[i].issueNum+"' target='_blank'>"+Issues[i].issueNum+" | "+Issues[i].issueType+" | "+Issues[i].issueSummary+"</a><br>Status: <b>"+Issues[i].issueStatus+"</b> | Assigned to: <b>"+Issues[i].issueAssignee+"</b><br>Created: <i>"+moment.utc(Issues[i].issueCreated).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss')+"</i><br>Updated: <i>"+Issues[i].issueUpdated+"</i></li>");
								if(Issues[i].issueStatus=='On Hold'){
									$("#monitorQueueList .issue:last").css({backgroundColor:"#ffffda",border:"1px #ddd solid"});
								}
								if(Issues[i].issueAssignee=='None'){
									$("#monitorQueueList .issue:last").css({backgroundColor:"#ffdada",border:"1px #ddd solid"});
								}
		                                        }
		                                }
	        	                }

					if(jq=='jira-monitor-all'){ // JIRA NETOPS issues
                                        	$("#monitorQueueList .issue").remove();

	                	                if(Issues.length==0){
        	                	                $('#monitorQueueList').append("<span class='issue'>No issues in NETOPS queue.</span>");
		                                } else {
        		                                for(var i=0; i<Issues.length; i++) {
								var a = moment.utc(Issues[i].issueUpdated).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss');
								var b = moment(a).from();
								Issues[i].issueUpdated = b;
								
								if(Issues[i].issueAssignee==''){
									Issues[i].issueAssignee='None';
								}
        	                	                	$('#monitorQueueList').append("<li class='issue'><a href='http://jira.cnvrmedia.net/browse/"+Issues[i].issueNum+"' target='_blank'>"+Issues[i].issueNum+" | "+Issues[i].issueType+" | "+Issues[i].issueSummary+"</a><br>Status: <b>"+Issues[i].issueStatus+"</b> | Assigned to: <b>"+Issues[i].issueAssignee+"</b><br>Created: <i>"+moment.utc(Issues[i].issueCreated).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss')+"</i><br>Updated: <i>"+Issues[i].issueUpdated+"</i></li>");
								if(Issues[i].issueStatus=='On Hold'){
									$("#monitorQueueList .issue:last").css({backgroundColor:"#ffffda",border:"1px #ddd solid"});
								}
								if(Issues[i].issueAssignee=='None'){
									$("#monitorQueueList .issue:last").css({backgroundColor:"#ffdada",border:"1px #ddd solid"});
								}
		                                        }
		                                }
	        	                }

					if(jq=='jira-netops-old'){ // JIRA NETOPS issues > 30 days old
                                        	$("#netops30QueueList .issue").remove();

	                	                if(Issues.length==0){
        	                	                $('#netops30QueueList').append("<span class='issue'>No issues in NETOPS queue.</span>");
		                                } else {
        		                                for(var i=0; i<Issues.length; i++) {
        	                	                	$('#netops30QueueList').append("<li class='issue'><a href='http://jira.cnvrmedia.net/browse/"+Issues[i].issueNum+"' target='_blank'>"+Issues[i].issueNum+" | "+Issues[i].issueType+" | "+Issues[i].issueSummary+"</a><br>Assigned to: "+Issues[i].issueAssignee+" | Opened by "+Issues[i].issueReporter+" on: "+moment.utc(Issues[i].issueCreated).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss')+"<br></li>");
		                                        }
		                                }
	        	                }

					if(jq=='jira-noc-all'){ // JIRA NOC issues
                                        	$("#nocQueueList .issue").remove();

	                	                if(Issues.length==0){
        	                	                $('#nocQueueList').append("<span class='issue'>No issues in NOC queue.</span>");
		                                } else {
        		                                for(var i=0; i<Issues.length; i++) {
								var a = moment.utc(Issues[i].issueUpdated).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss');
								var b = moment(a).from();
								Issues[i].issueUpdated = b;
								
								if(Issues[i].issueAssignee==''){
									Issues[i].issueAssignee='None';
								}
        	                	                	$('#nocQueueList').append("<li class='issue'><a href='http://jira.cnvrmedia.net/browse/"+Issues[i].issueNum+"' target='_blank'>"+Issues[i].issueNum+" | "+Issues[i].issueType+" | "+Issues[i].issueSummary+"</a><br>Status: <b>"+Issues[i].issueStatus+"</b> | Assigned to: <b>"+Issues[i].issueAssignee+"</b><br>Created: <i>"+moment.utc(Issues[i].issueCreated).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss')+"</i><br>Updated: <i>"+Issues[i].issueUpdated+"</i></li>");
								if(Issues[i].issueStatus=='On Hold'){
									$("#nocQueueList .issue:last").css({backgroundColor:"#ffffda",border:"1px #ddd solid"});
								}
								if(Issues[i].issueAssignee=='None'){
									$("#nocQueueList .issue:last").css({backgroundColor:"#ddddff",border:"1px #ddd solid"});
								}
								if(Issues[i].issueStatus=='New'){
									$("#nocQueueList .issue:last").css({backgroundColor:"#ffdada",border:"1px #ddd solid"});
								}
		                                        }
		                                }
	        	                }

					if(jq=='jira-esgcm'){ // JIRA CHANGE issues
                                        	$("#esgcmQueueList .issue").remove();

                                                if(Issues.length==0){
                                                        $('#esgcmQueueList').append("<span class='issue'>No issues in ESGCM queue for the next 7 days.</span>");
                                                } else {
							var d = new Date();
							var todayDate = (d.getMonth()+1)+'/'+d.getDate()+'/'+d.getFullYear();
                                                        for(var i=0; i<Issues.length; i++) {
								var a = moment.utc(Issues[i].deployDate).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss');
								var b = moment(a).from();
								if(Issues[i].issueAssignee==''){
									Issues[i].issueAssignee='None';
								}
        	                	                	$('#esgcmQueueList').append("<li class='issue'><a href='http://jira.cnvrmedia.net/browse/"+Issues[i].issueNum+"' target='_blank'>"+Issues[i].issueNum+" | "+Issues[i].issueType+" | "+Issues[i].issueSummary+"</a><br>Status: <b>"+Issues[i].issueStatus+"</b> | Opened by: <b>"+Issues[i].issueReporter+"</b><br>Deployment date: <i>"+moment.utc(Issues[i].deployDate).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss')+" ("+b+")</i></li>");
								if( todayDate == moment.utc(Issues[i].deployDate).tz(tz.name()).format('M/D/YYYY') ){
									$("#esgcmQueueList .issue:last").css({backgroundColor:"#ffdada",border:"1px #ddd solid"});
								}
                                                        }
                                                }
                                        }

					if(jq=='jira-myIssues'){
                                        	$("#myIssuesList .issue").remove();

	                	                if(Issues.length==0){
        	                	                $('#myIssuesList').append("<span class='issue'>No issues assigned to you in the NETOPS queue.</span>");
		                                } else {
        		                                for(var i=0; i<Issues.length; i++) {
								var a = moment.utc(Issues[i].issueUpdated).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss');
								var b = moment(a).from();
								Issues[i].issueUpdated = b;

								if(Issues[i].issueAssignee==''){
									Issues[i].issueAssignee='None';
								}
        	                	                	$('#myIssuesList').append("<li class='issue'><a href='http://jira.cnvrmedia.net/browse/"+Issues[i].issueNum+"' target='_blank'>"+Issues[i].issueNum+" | "+Issues[i].issueType+" | "+Issues[i].issueSummary+"</a><br>Status: <b>"+Issues[i].issueStatus+"</b> | Opened by: <b>"+Issues[i].issueReporter+"</b> | Assigned to: <b>"+Issues[i].issueAssignee+"</b><br>Created: <i>"+moment.utc(Issues[i].issueCreated).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss')+"</i><br>Updated: <i>"+Issues[i].issueUpdated+"</i></li>");
								if(Issues[i].issueStatus=='On Hold'){
									$("#myIssuesList .issue:last").css({backgroundColor:"#ffffda",border:"1px #ddd solid"});
								}
		                                        }
		                                }
	        	                }
				}

				$(' #loading-modal ').modal('show');
                                
                                var Issues = getJSONData('jira-esgcm');
                                generateQueue(Issues,'jira-esgcm');
                                
				Issues = getJSONData('jira-netops-old');
                                generateQueue(Issues,'jira-netops-old');
				
				Issues = getJSONData('jira-noc-all');
                                generateQueue(Issues,'jira-noc-all');
				
				Issues = getJSONData('jira-monitor-all');
                                generateQueue(Issues,'jira-monitor-all');
                                
				Issues = getJSONData('jira-myIssues');
                                generateQueue(Issues,'jira-myIssues');

				$(' #loading-modal ').modal('hide');
                                
				setInterval(function(){
					$(' #loading-modal ').modal('show');
                                        Issues = getJSONData('jira-change');
                                	generateQueue(Issues,'jira-change');
                                        
                                        Issues = getJSONData('jira-esgcm');
                                	generateQueue(Issues,'jira-esgcm');
                                        
					Issues = getJSONData('jira-monitor-all');
                                	generateQueue(Issues,'jira-monitor-all');
					
					Issues = getJSONData('jira-netops-old');
                                	generateQueue(Issues,'jira-netops-old');
                                        
					Issues = getJSONData('jira-noc-all');
                                	generateQueue(Issues,'jira-noc-all');
                                        
					Issues = getJSONData('jira-myIssues');
                                	generateQueue(Issues,'jira-myIssues');
					$(' #loading-modal ').modal('hide');
                                }, 60000);
			});
		</script>

		<?PHP addFooter(); ?>
	</body>
</html>
