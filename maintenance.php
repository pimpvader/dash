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
                        function maintIssues(jsonMaint,maintLength){
				var tz = jstz.determine();
				//console.log(jsonMaint);
				//console.log(jsonMaint.length);
				if(jsonMaint.length==0){
					$('#maintDashTable-'+maintLength).append("<tr class='issue'><td colspan=9 style='border-bottom:none;border-right:none;'>No planned vendor maintenance.</td></tr>");
				} else {
					//console.log(jsonMaint);
                                	for(var i=0; i<jsonMaint.length; i++) {
						if(maintLength=='30d'){
							$('#maintDashMobileContainer').append("<div class='panel panel-default col-lg-6 col-md-6 col-sm-12' style='padding-left:0px;padding-right:0px;'><div class='panel-heading'>"+jsonMaint[i].Provider+"<button class='close' id='editMaint_"+jsonMaint[i].ID+"' type='button'>Edit</button></div><div class='panel-body'><div class='maintDashEvent'><b>Start Time:</b> "+moment.utc(jsonMaint[i].Work_Start).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss')+"</div><div class='maintDashEvent'><b>End Time:</b> "+moment.utc(jsonMaint[i].Work_End).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss')+"</div><div class='maintDashEvent'><b>Duration:</b> "+jsonMaint[i].Duration+"</div><div class='maintDashEvent'><b>Circuit ID:</b> "+jsonMaint[i].CKT_ID+"</div><div class='maintDashEvent'><b>Affected Sites:</b> "+jsonMaint[i].Affected_Sites+"</div><div class='maintDashEvent'><b>Provider Ticket #:</b> "+jsonMaint[i].Provider_Ticket_Num+"</div><div class='maintDashEvent'><b>Description:</b> "+jsonMaint[i].Work_Description+"</div></div></div>");
						}
						$('#maintDashTable-'+maintLength).append("<tr class='issue'><td style='vertical-align:middle;' rowspan=2><button class='btn btn-sm btn-default' id='editMaint_"+jsonMaint[i].ID+"' type='button'>Edit</button></td><td>"+jsonMaint[i].Provider+"</td><td>"+moment.utc(jsonMaint[i].Work_Start).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss')+"</td><td>"+moment.utc(jsonMaint[i].Work_End).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss')+"</td><td>"+jsonMaint[i].Duration+"</td><td>"+jsonMaint[i].CKT_ID+"</td><td>"+jsonMaint[i].Affected_Sites+"</td><td>"+jsonMaint[i].Provider_Ticket_Num+"</td><td>"+moment.utc(jsonMaint[i].lastModified).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss')+"</td><td>"+jsonMaint[i].lastModifiedBy+"</td></tr><tr class='issue'><td colspan=10 style='text-align:left;'>"+jsonMaint[i].Work_Description+"</td></tr>");
						if((i+1)<jsonMaint.length){
							$('#maintDashTable-'+maintLength).append("<tr class='issue'><td colspan=10 style='border-right:none;'>&nbsp;</td></tr>");
						}
                                	}
                                	
					$(".issue button").click(function(){
                                        	var dbEventID = (this.id).replace("editMaint_","");
						console.log(dbEventID);
	                                        $(location).attr('href','edit.php?type=maint&id='+dbEventID);
        	                        });
					$(".panel-heading button").click(function(){
                                        	var dbEventID = (this.id).replace("editMaint_","");
						console.log(dbEventID);
	                                        $(location).attr('href','edit.php?type=maint&id='+dbEventID);
        	                        });
				}
                        }

                        $(document).ready(function () {
				$(document).attr('title', $(document).attr('title')+' - Vendor Maintenance');
				$('#nav-active-maint').addClass('active');
                                var jsonMaint = getJSONData('maint-24h');
                                maintIssues(jsonMaint,'24h');
        			$('#maintDash-24h-badge').text(jsonMaint.length);
                                var jsonMaint = getJSONData('maint-7d');
                                maintIssues(jsonMaint,'7d');
        			$('#maintDash-7d-badge').text(jsonMaint.length);
                                var jsonMaint = getJSONData('maint-30d');
                                maintIssues(jsonMaint,'30d');
        			$('#maintDash-30d-badge').text(jsonMaint.length);
                                var jsonMaint = getJSONData('maint-60d');
                                maintIssues(jsonMaint,'60d');
        			$('#maintDash-60d-badge').text(jsonMaint.length);
                                var jsonMaint = getJSONData('maint-archive');
                                maintIssues(jsonMaint,'archive');
        			$('#maintDash-archive-badge').text(jsonMaint.length);

                                setInterval(function(){
                                        jsonMaint = getJSONData('maint-24h');
                                        $(".issue").remove();
					$("#maintDashMobileContainer div").remove();
                                        maintIssues(jsonMaint,'24h');
        				$('#maintDash-24h-badge').text(jsonMaint.length);
                                	var jsonMaint = getJSONData('maint-7d');
                                	maintIssues(jsonMaint,'7d');
        				$('#maintDash-7d-badge').text(jsonMaint.length);
                                	var jsonMaint = getJSONData('maint-30d');
                                	maintIssues(jsonMaint,'30d');
        				$('#maintDash-30d-badge').text(jsonMaint.length);
                                	var jsonMaint = getJSONData('maint-60d');
                                	maintIssues(jsonMaint,'60d');
        				$('#maintDash-60d-badge').text(jsonMaint.length);
                                	var jsonMaint = getJSONData('maint-archive');
                                	maintIssues(jsonMaint,'archive');
        				$('#maintDash-archive-badge').text(jsonMaint.length);
                                }, 30000);
                        });
                </script>
	</head>

	<body>
		<?PHP getHeader(); ?>

		<div id="pageContainer" class="container">
			<div class="panel panel-default maintDashDesktop" style="background:white;">
                                <ul id="maint-dash-tabs" class="nav nav-tabs nav-justified">
                                        <li id="maint-dash-tabs-1" class="active"><a href="#maint-dash-container-1" data-toggle="tab">Next 24 Hours&nbsp;&nbsp;<span class="badge" id="maintDash-24h-badge">0</span></a></li>
                                        <li id="maint-dash-tabs-2"><a href="#maint-dash-container-2" data-toggle="tab">Next 7 Days&nbsp;&nbsp;<span class="badge" id="maintDash-7d-badge">0</span></a></li>
                                        <li id="maint-dash-tabs-3"><a href="#maint-dash-container-3" data-toggle="tab">Next 30 Days&nbsp;&nbsp;<span class="badge" id="maintDash-30d-badge">0</span></a></li>
                                        <li id="maint-dash-tabs-4"><a href="#maint-dash-container-4" data-toggle="tab">Next 60 Days&nbsp;&nbsp;<span class="badge" id="maintDash-60d-badge">0</span></a></li>
                                        <li id="maint-dash-tabs-5"><a href="#maint-dash-container-5" data-toggle="tab">Past 30 Days&nbsp;&nbsp;<span class="badge" id="maintDash-archive-badge">0</span></a></li>
                                </ul>

                                <div id="maint-dash-container" class="tab-content" style="padding:10px;border-left:#ddd 0px solid;border-bottom:#ddd 0px solid;border-right:#ddd 0px solid;">
                                        <div id="maint-dash-container-1" class="row tab-pane fade in active" style="margin-left:0px !important;margin-right:0px !important;">
						<table id="maintDashTable-24h" class="table" style="border-bottom:1px solid #ddd;">
							<thead>
	                	        	        	<th>&nbsp;</th>
        		                	        	<th>Vendor</th>
								<th>Start Time (Local)</th>
								<th>End Time (Local)</th>
								<th>Duration</th>
								<th>Circuit ID</th>
								<th>Affected Sites</th>
                	                			<th>Vendor Ticket #</th>
                	                			<th>Last Updated (Local)</th>
                	                			<th>Updated By</th>
							</thead>
                        			</table>
                                        </div>
                                        <div id="maint-dash-container-2" class="row tab-pane fade" style="margin-left:0px !important;margin-right:0px !important;">
						<table id="maintDashTable-7d" class="table" style="border-bottom:1px solid #ddd;">
							<thead>
	                	        	        	<th>&nbsp;</th>
        		                	        	<th>Vendor</th>
								<th>Start Time (Local)</th>
								<th>End Time (Local)</th>
								<th>Duration</th>
								<th>Circuit ID</th>
								<th>Affected Sites</th>
                	                			<th>Vendor Ticket #</th>
                	                			<th>Last Updated (Local)</th>
                	                			<th>Updated By</th>
							</thead>
                        			</table>
                                        </div>
                                        <div id="maint-dash-container-3" class="row tab-pane fade" style="margin-left:0px !important;margin-right:0px !important;">
						<table id="maintDashTable-30d" class="table" style="border-bottom:1px solid #ddd;">
							<thead>
	                	        	        	<th>&nbsp;</th>
        		                	        	<th>Vendor</th>
								<th>Start Time (Local)</th>
								<th>End Time (Local)</th>
								<th>Duration</th>
								<th>Circuit ID</th>
								<th>Affected Sites</th>
                	                			<th>Vendor Ticket #</th>
                	                			<th>Last Updated (Local)</th>
                	                			<th>Updated By</th>
							</thead>
                        			</table>
                                        </div>
                                        <div id="maint-dash-container-4" class="row tab-pane fade" style="margin-left:0px !important;margin-right:0px !important;">
						<table id="maintDashTable-60d" class="table" style="border-bottom:1px solid #ddd;">
							<thead>
	                	        	        	<th>&nbsp;</th>
        		                	        	<th>Vendor</th>
								<th>Start Time (Local)</th>
								<th>End Time (Local)</th>
								<th>Duration</th>
								<th>Circuit ID</th>
								<th>Affected Sites</th>
                	                			<th>Vendor Ticket #</th>
                	                			<th>Last Updated (Local)</th>
                	                			<th>Updated By</th>
							</thead>
                        			</table>
                                        </div>
                                        <div id="maint-dash-container-5" class="row tab-pane fade" style="margin-left:0px !important;margin-right:0px !important;">
						<table id="maintDashTable-archive" class="table" style="border-bottom:1px solid #ddd;">
							<thead>
	                	        	        	<th>&nbsp;</th>
        		                	        	<th>Vendor</th>
								<th>Start Time (Local)</th>
								<th>End Time (Local)</th>
								<th>Duration</th>
								<th>Circuit ID</th>
								<th>Affected Sites</th>
                	                			<th>Vendor Ticket #</th>
                	                			<th>Last Updated (Local)</th>
                	                			<th>Updated By</th>
							</thead>
                        			</table>
                                        </div>
                                </div>
			</div>
			<div id="maintDashMobileContainer" class="maintDashMobile"></div>
			<div style="text-align:right;margin-right:25px;margin-bottom:10px;">
				<a class="btn btn-sm btn-primary" style="margin-top:10px;margin-right:-10px;" href="create.php?type=maint">New...</a>
			</div>
		</div>

		<?PHP addFooter(); ?>
	</body>
</html>
