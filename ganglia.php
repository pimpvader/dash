<?PHP
	$path=$_SERVER['DOCUMENT_ROOT'];
	require_once $path.'/php/dbconn.php';
	include $path.'/php/common.php';

	if(ISSET($_POST['generateAlertEmail_Ganglia_Entry'])){	
		$db_connection_nocdash = new db_nocdash();
		for($i=0;$i<sizeof($_POST['generateAlertEmail_Ganglia_Entry']);$i++){
			$gangliaAlert=explode(",",$_POST[generateAlertEmail_Ganglia_Entry][$i]);
			$db_connection_nocdash -> query("UPDATE Noc.gangliaAlerts SET snoozeUntil=NOW()+INTERVAL 30 MINUTE WHERE alertID=".$gangliaAlert[1]);
		}
	}
?>

<!DOCTYPE HTML>
<html>
	<head>
		<?PHP getHtmlHead(); ?>
		<script>
			function gangliaIssues(jsonGanglia,issuesView){
				$('#gangliaAlertCount').text(jsonGanglia.length);
				$('#gangliaAlertCount-mobile').text(jsonGanglia.length);
				console.log('refreshing '+issuesView+' ganglia alerts...');
				var tz = jstz.determine();
				
				if(jsonGanglia.length>0){
					var a = moment.utc(jsonGanglia[0].alertTimestamp).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss');
					var b = moment(a).from();
					$(' #currentGangliaTimestamp ').text('Alerts as of: '+b);
				}
				
				if(jsonGanglia.length==0){
					console.log('jsonGanglia.length = 0');
					if(issuesView=='desktop'){
						console.log('issuesView desktop');
						$('#gangliaAlertsTableBody').append("<tr class='alert-desktop'><td style='text-align:center;' colspan=9>No Ganglia alerts.</td></tr>");
					}
					if(issuesView=='mobile'){
					}
					if(issuesView=='nocwall'){
						console.log('issuesView nocwall');
						$('#gangliaAlertsNocWall-list').append("<div style='margin-top:500px;text-align:center;'>No Ganglia alerts.</div>");
					}
				}else{
					if(issuesView=='desktop'){
						$('.gangliaAlertsDesktop').append('<div id="submitDiv-desktop" style="text-align:right;margin-right:25px;margin-bottom:10px;"><input class="btn btn-sm btn-warning" style="margin-top:10px;margin-right:10px;" id="btn-gangliaSnooze" type="submit" value="Snooze"><input class="btn btn-sm btn-primary" style="margin-top:10px;margin-right:-10px;" id="btn-gangliaGenerateNotification" type="submit" value="Generate Notification"></div>');

						$(' #btn-gangliaSnooze').on('click',function(){
							console.log('SNOOZE BUTTON');
							console.log( $(' #gangliaAlertsDesktop ')[0].action );
							$(' #gangliaAlertsDesktop ')[0].action = 'ganglia.php';
							console.log( $(' #gangliaAlertsDesktop ')[0].action );
						});
					}
					if(issuesView=='mobile'){
						$('.gangliaAlertsMobile').append('<div id="submitDiv-mobile" style="text-align:right;margin-right:25px;margin-bottom:10px;"><input class="btn btn-sm btn-primary" style="margin-top:10px;margin-right:-10px;" type="submit" value="Generate Notification"></div>');
					}
					if(issuesView=='nocwall'){
						$(' #gangliaAlertsNocWall-list ').append( '<div style="font-size:42px;"><u>Ganglia Alerts as of: '+jsonGanglia[0].alertTimestamp+'</u></div><br>' );
					}
                                	for(var i=0; i<jsonGanglia.length; i++) {
						if(jsonGanglia[i].sentDate==null || jsonGanglia[i].sentDate=='None'){
							b = 'None';
						}else{
							var a = moment.utc(jsonGanglia[i].sentDate).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss');
							var b = moment(a).from();
						}
						if(jsonGanglia[i].sentBy==null || jsonGanglia[i].sentBy=='None'){
							jsonGanglia[i].sentBy = 'None';
						}
						if(jsonGanglia[i].mailID==null || jsonGanglia[i].mailID=='#'){
							jsonGanglia[i].mailID = '#';
						}else{
							jsonGanglia[i].mailID='viewEmail.php?mailID='+jsonGanglia[i].mailID;
						}
						if(issuesView=='desktop'){
							if(jsonGanglia[i].mailID!='#'){
								var mailRecipients = "<div>To:&nbsp;"+jsonGanglia[i].sentTo+"</div>";
								if(!jsonGanglia[i].sentCC==""){
									mailRecipients+="<div>CC:&nbsp;"+jsonGanglia[i].sentCC+"</div>";
								}
								if(!jsonGanglia[i].sentBCC==""){
               		                		        	mailRecipients+="<div>BCC:&nbsp;"+jsonGanglia[i].sentBCC+"</div>";
                               		        	        }
								emailBody = jsonGanglia[i].body.replace(new RegExp('\r?\n','g'),'<br>').replace(new RegExp('\t','g'),'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;').replace(new RegExp('FileSystemSizeUsedAvailUse %Mounted on','g'),'<br>FileSystem&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Size&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Used&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Avail&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Use %&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mounted on<br>');
								jsonGanglia[i].mailID='viewEmail.php?mailID='+jsonGanglia[i].mailID;
               		                		        popoverEmailTitle = b+" | "+jsonGanglia[i].subject;
								popoverEmailContent = "<div style=\"word-wrap:break-word;\">"+mailRecipients+"<br><div>"+emailBody+"</div></div>";
							}else{
								jsonGanglia[i].subject = 'None';
								popoverEmailTitle = "";
								popoverEmailContent = "No emails sent";
							}

							//check hostname, p.dc.dotomi.net to correct Ganglia instance
							var tmpStr = jsonGanglia[i].hostname;
							if( tmpStr.search( 'iad' )>=0 ){
								gngHost = "http://iad.trend.dc.dotomi.net/";
							}else if( tmpStr.search( 'sjc' )>=0 ){
								gngHost = "http://sjc.trend.dc.dotomi.net/gweb/";
							}else if( tmpStr.search( 'ams' )>=0 ){
								gngHost = "http://ams.trend.dc.dotomi.net/gweb/";
							}else if( tmpStr.search( 'ord' )>=0 ){
								gngHost = "http://trend.dc.dotomi.net/gweb/";
							}else{

							}

							if( jsonGanglia[i].snoozeUntil==null ){ 
								snooze_b = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;n/a'; 
							}else{
								snooze_a = moment.utc(jsonGanglia[i].snoozeUntil).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss');
								snooze_b = moment(snooze_a).from();
								//snooze_b = jsonGanglia[i].snoozeUntil;
							}

						shortHostname = jsonGanglia[i].hostname.split('.');
						$('#gangliaAlertsTableBody').append("<tr class=\"alert-desktop\"><td><div class=\"checkbox\" style=\"margin-top:0px;margin-bottom:0px;\"><input type=\"checkbox\" name=\"generateAlertEmail_Ganglia_Entry[]\" id=\"gangliaAlert_"+jsonGanglia[i].alertID+"\" value=\""+jsonGanglia[i].entry+","+jsonGanglia[i].alertID+"\"></div></td><td class=\"sort-highLow\">"+jsonGanglia[i].highLow+" ABS</td><td class=\"sort-checkType\">"+jsonGanglia[i].checkType+"</td><td class=\"sort-hostname\" style=\"color:black;\"><a href='search.php?lookup="+shortHostname[0]+"'>"+jsonGanglia[i].hostname+"</a></td><td class=\"sort-threshold\">T:"+jsonGanglia[i].threshold+"</td><td class=\"sort-absolute\">A:"+jsonGanglia[i].absolute+"</td><td class=\"sort-sentDate\">"+b+"</td><td class=\"sort-sentBy\">"+jsonGanglia[i].sentBy.replace(/"/g, '&quot;')+"</td><td>"+snooze_b+"</td></tr>");
						}
						if(issuesView=='mobile'){
							$('#gangliaAlertsMobileContainer').append("<div class='alert-mobile panel panel-danger' style='padding:0px;'><div class='panel-heading' style='word-wrap:break-word;'><div class='checkbox' style='margin:0px;'><input type='checkbox' name='generateMobileAlertEmail_Ganglia_Entry[]' id='gangliaAlert_"+jsonGanglia[i].alertID+"' value='"+jsonGanglia[i].entry+","+jsonGanglia[i].alertID+"'>&nbsp;"+jsonGanglia[i].entry+"</div></div><div class='panel-body' style='word-wrap:break-word;'><div>Alert Time: <b>"+jsonGanglia[i].alertTimestamp+"</b></div><div>Last Notification Sent: <b>"+b+"</b></div><div>Sent By: <b>"+jsonGanglia[i].sentBy.replace(/"/g, '&quot;')+"</b></div></div>");
						}
						if(issuesView=='nocwall'){
							$(' #gangliaAlertsNocWall-list ').append( '<div>'+jsonGanglia[i].highLow+' '+jsonGanglia[i].checkType+' '+jsonGanglia[i].hostname+' T:'+jsonGanglia[i].threshold+' A:'+jsonGanglia[i].absolute+'</div>' );
						}
					}
				}
				console.log('...refreshing '+issuesView+' complete');
                        }

			$(document).ready(function () {
				$(document).attr('title', $(document).attr('title')+' - Ganglia Alerts');
				
				var gangliaView = window.location.search;
				gangliaView = gangliaView.split("=");
				gangliaView = gangliaView[1];
				if(gangliaView == 'nocwall'){
					$(' body ').css('width','1920px');
					$(' body ').css('height','1080px');
					$(' body ').css('background-color','black');
					$(' body ').css('margin','0px');
					$(' body ').css('overflow','hidden');
					$(' #gangliaAlertsNocWall ').css('color','red');
					$(' #gangliaAlertsNocWall ').css('font-size','40px');
					$(' #gangliaAlertsNocWall ').css('font-family','Verdana');
					$(' #gangliaAlertsNocWall ').css('line-height','1.1');
					$(' #gangliaAlertsNocWall ').css('white-space','nowrap');
					$(' #pageContainer ').css('width','100%');
					$(' #pageContainer ').css('padding','5px');
					$(' .navbar ').css('display','none');
					$(' #footer ').css('display','none');
					$(' #gangliaAlertsDesktop ').css('display','none');
					$(' #gangliaAlertsMobile ').css('display','none');
					$(' #gangliaAlertsNocWall ').show();
					var jsonGanglia_nocwall = getJSONData('ganglia-nocwall');
					gangliaIssues(jsonGanglia_nocwall,'nocwall');
				}else{
					$(' #gangliaAlertsNocWall ').css('display','none');
				}

				$('#nav-active-alerts').addClass('active');
				$(' #loading-modal ').modal('show');
				var jsonGanglia_first = getJSONData('ganglia');
					
				gangliaIssues(jsonGanglia_first,'desktop');
				gangliaIssues(jsonGanglia_first,'mobile');

				$(".alert-desktop td").css('border-top','1px solid #ddd');
				$(".alertCheckbox-desktop td").css('border-top','0px solid #ddd');
				$(".alert-mobile td").css('border-top','1px solid #ddd');
				$(".alertCheckbox-mobile td").css('border-top','0px solid #ddd');

				var RefreshID = enableAutoRefresh();

				function enableAutoRefresh(){
					console.log('AUTO-REFRESH ENABLED');
                                	var autoRefreshInterval = setInterval(function(){
						$(' #loading-modal ').modal('show');
						console.log('AUTO-REFRESH Running');
						console.log('Running Interval ID: '+autoRefreshInterval);
						jsonGanglia = getJSONData('ganglia');
						$(".alert-desktop").remove();
						$("#submitDiv-desktop").remove();
						gangliaIssues(jsonGanglia,'desktop');
						
						$(".alert-desktop td").css('border-top','1px solid #ddd');
						$(".alertCheckbox-desktop td").css('border-top','0px solid #ddd');
						$("#gangliaCheckAll").prop('checked',false);
						$("#searchGangliaAlerts").val('');
						var popoverEl = $('.popover');
						if (popoverEl) {
							popoverEl.remove();
						}
						$("#gangliaAlertsNocWall-list").children().remove();
						gangliaIssues(jsonGanglia,'nocwall');
						$(' #loading-modal ').modal('hide');
               		                }, 60000);
					console.log('Created Interval ID: '+autoRefreshInterval);
					return autoRefreshInterval;
				}
				
				function disableAutoRefresh(){
					clearInterval(RefreshID);
					console.log('AUTO-REFRESH DISABLED');
					console.log('Cleared Interval ID: '+RefreshID);
				}
				
				var sortOptions = {valueNames: ['sort-highLow','sort-checkType','sort-hostname','sort-threshold','sort-absolute',/*'sort-sentDate',*/'sort-sentBy'],page:700};
				var gangliaSortList = new List('gangliaSortTable', sortOptions);
				console.log(gangliaSortList.size());
				$('[data-toggle="popover"]').popover();
				$(' #loading-modal ').modal('hide');
				
				$('#gangliaCheck_AutoRefresh').on('click',function () {
					if($(this).prop("checked")==true){
						RefreshID = enableAutoRefresh();
					}else{
						disableAutoRefresh(RefreshID);
					}
				});

				$('body').on('hidden.bs.popover', function() {
					var popoverEl = $('.popover').not('.in');
					if (popoverEl) {
						popoverEl.remove();
					}
				});

				$('#loading-btn-mobile').on('click',function () {
					console.log('MANUAL MOBILE REFRESH');
					jsonGanglia_btn_mobile = getJSONData('ganglia');
					$(".alert-mobile").remove();
					$("#submitDiv-mobile").remove();
					gangliaIssues(jsonGanglia_btn_mobile,'mobile');
					
					$(".alert-mobile td").css('border-top','1px solid #ddd');
					$(".alertCheckbox-mobile td").css('border-top','0px solid #ddd');
					$("#gangliaCheckAllMobile").prop('checked',false);
				});
			
				$('#gangliaCheckAll').on('click', function () {
                                        console.log('CHECK ALL DESKTOP');
                                        if($(this).prop("checked")==false){
                                                console.log('DESKTOP checkboxes NOT checked');
                                                $(".alert-desktop input[type='checkbox']").prop('checked',false);
                                                //if checked, uncheck all
                                        }else{
                                                console.log('DESKTOP checkboxes ARE checked');
                                                $(".alert-desktop input[type='checkbox']").prop('checked',true);
                                                //if not checked, check all
                                        }
                                });
                        
                                $('#gangliaCheckAllMobile').on('click', function () {
                                        console.log('CHECK ALL MOBILE');
                                        if($(this).prop("checked")==false){
                                                console.log('MOBILE checkboxes NOT checked');
                                                $(".alert-mobile input[type='checkbox']").prop('checked',false);
                                                //if checked, uncheck all
                                        }else{
                                                console.log('MOBILE checkboxes ARE checked');
                                                $(".alert-mobile input[type='checkbox']").prop('checked',true);
                                                //if not checked, check all
                                        }
                                });
                        
                                $('#loading-btn').on('click',function () {
                                        $(' #loading-modal ').modal('show');
                                        console.log(gangliaSortList.size());
                                        gangliaSortList.search();
                                        if($('#gangliaCheck_AutoRefresh').prop("checked")==true){
                                                disableAutoRefresh(RefreshID);
                                                RefreshID = enableAutoRefresh();
                                        }
                                        console.log('MANUAL DESKTOP REFRESH');
                                        jsonGanglia_btn = getJSONData('ganglia');
                                        $(".alert-desktop").remove();
                                        $("#submitDiv-desktop").remove();
                                        gangliaIssues(jsonGanglia_btn,'desktop');
                                        
                                        $(".alert-desktop td").css('border-top','1px solid #ddd');
                                        $(".alertCheckbox-desktop td").css('border-top','0px solid #ddd');
                                        $("#gangliaCheckAll").prop('checked',false);
                                        $("#searchGangliaAlerts").val('');
                                        var popoverEl = $('.popover');
                                        if (popoverEl) {
                                                popoverEl.remove();
                                        }
                                        $(' #loading-modal ').modal('hide');
                                });
	
				$('.sort').on('click',function(){
					console.log('SORT');
				});
	
				$('html').on('click', function (e) {
					$('[data-toggle="popover"]').each(function () {
						if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
							$(this).popover('hide');
						}
					});
				});
			});
		</script>
	</head>

	<body>
		<?PHP getHeader(); ?>
		
                <div id="pageContainer" class="container">
			<form name="passDataEmailForm" id="gangliaAlertsDesktop" class="gangliaAlertsDesktop" role="form" action="generateEmail.php" method="post">
                        	<div id="gangliaSortTable" class="panel panel-default">
					<div class="panel-heading">Current Ganglia Alerts <span id='gangliaAlertCount' class='badge'></span>
						<div class="btn-group pull-right" style="margin-left:5px;">
							<button type="button" id='loading-btn' class="btn btn-xs btn-default">
								<span class='glyphicon glyphicon-refresh'></span>
							</button>
							<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							</button>
							<ul class="dropdown-menu" style="min-width:115px;vertical-align:middle;padding-top:7px;padding-bottom:3px;" role="menu">
								<li>
									<div class="checkbox pull-right" style="padding-left:10px;padding-right:10px;margin:0px;vertical-align:bottom;">
										<label><input type="checkbox" id="gangliaCheck_AutoRefresh" style="margin-top:2px;" checked>Auto-refresh?</label>
									</div>
								</li>
							</ul>
						</div>
						<input id="searchGangliaAlerts" type="text" class="search pull-right" placeholder="Search" autofocus>
					</div>
	                                <div class="panel-body">
	                                        <table id="gangliaAlertsTable" class="table-striped" style="color:red;width:100%;border-bottom:1px solid #ddd;">
							<thead style="color:black;">
								<tr><td id="currentGangliaTimestamp" colspan=9 style="padding-bottom:10px;"></td></tr>
								<tr>
									<th><div class='checkbox' style='margin-bottom:2px;margin-top:0px;'><input type='checkbox' id='gangliaCheckAll'></div></th>
									<th class='sort' data-sort='sort-highLow'><span>Check/Uncheck All</span></th>
									<th class='sort' data-sort='sort-checkType'><span>Check Type</span></th>
									<th class='sort' data-sort='sort-hostname'><span>Hostname</span></th>
									<th class='sort' data-sort='sort-threshold'><span>Threshold</span></th>
									<th class='sort' data-sort='sort-absolute'><span>Absolute</span></th>
									<th><span>Last Notification Sent</span></th>
									<th class='sort' data-sort='sort-sentBy'><span>Sent By</span></th>
									<th><span>Snooze Until</span></th>
								</tr>
							</thead>
							<tbody id="gangliaAlertsTableBody" class="list">
							</tbody>
        	                                </table>
                                	</div>
                        	</div>
				<input type="text" name="generateAlertEmail" value="GangliaAlerts" hidden>
			</form>
			<form name="passDataEmailForm" id="gangliaAlertsMobile" class="gangliaAlertsMobile" role="form" action="generateEmail.php" method="post">
				<input type="text" name="generateAlertEmail" value="GangliaAlerts" hidden>
				<div>
	                        	<div id="gangliaAlertsMobileContainer" class='panel-body'>
						<div class='panel panel-default' style='padding:0px;'>
							<div class='panel-heading'>Ganglia Alerts<button type='button' id='loading-btn-mobile' data-loading-text='Refreshing...' class='btn btn-default btn-xs pull-right'><span class='glyphicon glyphicon-refresh'></span></button></div>
							<div class='panel-body'><div class='checkbox'><input type='checkbox' id='gangliaCheckAllMobile'>&nbsp;Check/Uncheck All Alerts <span id='gangliaAlertCount-mobile' class='badge'></span></div></div>
						</div>
					</div>
				</div>
			</form>
			<div id="gangliaAlertsNocWall">
				<div id="gangliaAlertsNocWall-list"></div>
			</div>
                </div>

		<?PHP addFooter(); ?>
	</body>
</html>
