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
                        function firesDashIssues(jsonFiresDash){
				var tz = jstz.determine();
                                var jiraPatt = new Array();
                                jiraPatt[0] = /change-\d{3,}/gi;
                                jiraPatt[1] = /dtm13-\d{3,}/gi;
                                jiraPatt[2] = /dtm14-\d{3,}/gi;
                                jiraPatt[3] = /dtm15-\d{3,}/gi;
                                jiraPatt[4] = /netops-\d{3,}/gi;
                                jiraPatt[5] = /prod-\d{3,}/gi;
                                jiraPatt[6] = /it-\d{3,}/gi;
                                jiraPatt[7] = /noc-\d{3,}/gi;
                                jiraPatt[8] = /sysadmin-\d{3,}/gi;
                                jiraPatt[9] = /syseng-\d{3,}/gi;
                                jiraPatt[10] = /neteng-\d{3,}/gi;
                                jiraPatt[11] = /esgcm-\d{3,}/gi;
                                jiraPatt[12] = /devops-\d{3,}/gi;
                                jiraPatt[13] = /esge-\d{3,}/gi;
                                jiraPatt[13] = /pi-\d{3,}/gi;

				if(jsonFiresDash.length==0){
					$('#firesDashTable').append("<tr class='issue'><td colspan=7 style='border-bottom:none;border-right:none;'>There are currently no FireBoard events.</td></tr>");
					$('#firesDashContainer .panel .panel-body').append("<div class='issue'><div colspan=7 style='border-bottom:none;border-right:none;'>There are currently no FireBoard events.</div></div>");
				} else {
					for(i=0; i<jsonFiresDash.length; i++) {
	                                        for(j=0; j<jiraPatt.length; j++){
                	                                result = jsonFiresDash[i].eventDesc.match(jiraPatt[j]);
                        	                        if(result){
								for(k=0;k<result.length;k++){
                                	                        	jsonFiresDash[i].eventDesc = jsonFiresDash[i].eventDesc.replace(result[k],"<a href='http://jira.cnvrmedia.net/browse/"+result[k]+"' target='_blank'>"+result[k]+"</a>");
								}
                                        	        }
	                                        }
						switch(jsonFiresDash[i].eventType){
							case 'maint':
								jsonFiresDash[i].eventType = 'Maintenance';
								bgcolor='#ddddff';
								break;
							case 'fire':
								jsonFiresDash[i].eventType = 'Fire';
								bgcolor='#ffdddd';
								break;
							case 'deploy':
								jsonFiresDash[i].eventType = 'Deployment';
								bgcolor='#ddffdd';
								break;
							case 'canary':
								jsonFiresDash[i].eventType = 'Canary';
								bgcolor='#ffffdd';
								break;
						}
						$('#firesDashContainer').append("<div class='panel panel-default col-lg-6 col-md-6 col-sm-12' style='padding-left:0px;padding-right:0px;'><div class='panel-heading'>"+jsonFiresDash[i].eventType+"<button class='close' id='editEvent_"+jsonFiresDash[i].eventID+"' type='button'>Edit</button></div><div class='panel-body'><div class='firesDashEvent'><b>"+jsonFiresDash[i].eventDesc.replace(/"/g, '&quot;')+"</b></div><div class='firesDashEvent'>Created: "+moment.utc(jsonFiresDash[i].eventStart).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss')+"</div><div class='firesDashEvent'>Expected Duration: <b>"+jsonFiresDash[i].est_duration.replace(/"/g, '&quot;')+"</b></div><div class='firesDashEvent'>Last Modified: "+moment.utc(jsonFiresDash[i].eventLastModified).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss')+"</div><div class='firesDashEvent'>Last Update By: <b>"+jsonFiresDash[i].lastModifiedBy+"</b></div></div></div>");
        	                                $('#firesDashTable').append("<tr class='issue' style='background-color:"+bgcolor+"'><td><button class='btn btn-sm btn-default' id='editEvent_"+jsonFiresDash[i].eventID+"' type='button'>Edit</button></td><td id='firesDashTable_eventType_"+jsonFiresDash[i].eventID+"'>"+jsonFiresDash[i].eventType+"</td><td id='firesDashTable_eventDesc_"+jsonFiresDash[i].eventID+"'>"+jsonFiresDash[i].eventDesc.replace(/"/g, '&quot;')+"</td><td>"+moment.utc(jsonFiresDash[i].eventStart).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss')+"</td><td>"+jsonFiresDash[i].est_duration.replace(/"/g, '&quot;')+"</td><td>"+moment.utc(jsonFiresDash[i].eventLastModified).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss')+"</td><td>"+jsonFiresDash[i].lastModifiedBy+"</td></tr>");
                	                }
					$(".issue button").click(function(){
                                                var dbEventID = (this.id).replace("editEvent_","");
                                                console.log(dbEventID);
                                                $(location).attr('href','edit.php?type=firesDash&id='+dbEventID);
                                        });
					$(".panel-heading button").click(function(){
                                                var dbEventID = (this.id).replace("editEvent_","");
                                                console.log(dbEventID);
                                                $(location).attr('href','edit.php?type=firesDash&id='+dbEventID);
                                        });
				}
                        }

                        $(document).ready(function () {
				$(document).attr('title', $(document).attr('title')+' - Fires Dash');
				$('#nav-active-alerts').addClass('active');
                                var jsonFiresDash = getJSONData('firesDash');
                                firesDashIssues(jsonFiresDash);

                                setInterval(function(){
                                        jsonFiresDash = getJSONData('firesDash');
                                        $(".issue").remove();
					$("#firesDashContainer .panel").remove();
                                        firesDashIssues(jsonFiresDash);
                                }, 30000);
                        });
                </script>
	</head>

	<body>
		<?PHP getHeader(); ?>
		<div id="pageContainer" class="container">
			<div class="panel panel-default firesDashDesktop">
				<div class="panel-body">
					<table id="firesDashTable" class="listTable">
						<thead>
	                                		<th>&nbsp;</th>
        	                        		<th>Event Type</th>
                	                		<th>Event Description</th>
                        	        		<th>Event Started</th>
                                			<th>Expected Duration</th>
                                			<th>Last Modified</th>
                                			<th>Last Update By</th>
						</thead>
                        		</table>
				</div>
			</div>
			<div id="firesDashContainer" class="firesDashMobile"></div>
			<div style="text-align:right;margin-right:25px;margin-bottom:10px;">
				<a class="btn btn-sm btn-primary" style="margin-top:10px;margin-right:-10px;" href="create.php?type=firesDash">New...</a>
			</div>
		</div>

		<?PHP addFooter(); ?>
	</body>
</html>
