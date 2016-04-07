<?PHP
	$path=$_SERVER['DOCUMENT_ROOT'];
	require_once $path.'/php/dbconn.php';
	include $path.'/php/common.php';

	function checkAlertGeneration(){
		if(!empty($_POST)){
			if($_POST['generateAlertEmail']=='GangliaAlerts'){
				echo "$('#email-template-tabs a[href=\"#email-template-container-4\"]').tab('show');";
		
				if(ISSET($_POST['generateAlertEmail_Ganglia_Entry'])){	
					for($i=0;$i<sizeof($_POST['generateAlertEmail_Ganglia_Entry']);$i++){
						$gangliaAlert=explode(",",$_POST[generateAlertEmail_Ganglia_Entry][$i]);
						echo "$('#generateEmailForm-4_appError-5').val($('#generateEmailForm-4_appError-5').val()+'".$gangliaAlert[0]."<br>');";
						echo "$('#generateEmailForm-4_appError-6').val($('#generateEmailForm-4_appError-6').val()+'".$gangliaAlert[1].",');";
					}
				}
				
				if(ISSET($_POST['generateMobileAlertEmail_Ganglia_Entry'])){
					for($i=0;$i<sizeof($_POST['generateMobileAlertEmail_Ganglia_Entry']);$i++){
						$gangliaAlert=explode(",",$_POST[generateMobileAlertEmail_Ganglia_Entry][$i]);
						echo "$('#generateEmailForm-4_appError-5').val($('#generateEmailForm-4_appError-5').val()+'".$gangliaAlert[0]."<br>');";
						echo "$('#generateEmailForm-4_appError-6').val($('#generateEmailForm-4_appError-6').val()+'".$gangliaAlert[1].",');";
					}
				}
			}
		}else{
			echo "$('#email-template-tabs a[href=\"#email-template-container-1\"]').tab('show');";
		}
	}
?>

<!DOCTYPE HTML>
<html>
	<head>
		<?PHP getHtmlHead(); ?>
		<script src="js/jquery.timeentry.js"></script>
	</head>

	<body>
		<?PHP getHeader(); ?>
		<div id="pageContainer" class="container">
			<div class="panel panel-default" style="background:white;padding:10px;">
				<ul id="email-template-tabs" class="nav nav-tabs nav-justified">
					<li id="email-template-tabs-1" class="active"><a href="#email-template-container-1" data-toggle="tab">SEV 1</a></li>
					<li id="email-template-tabs-2"><a href="#email-template-container-2" data-toggle="tab">SEV 2</a></li>
					<li id="email-template-tabs-3"><a href="#email-template-container-3" data-toggle="tab">SEV 3</a></li>
					<li id="email-template-tabs-4"><a href="#email-template-container-4" data-toggle="tab">Application Issues</a></li>
					<li id="email-template-tabs-5"><a href="#email-template-container-5" data-toggle="tab">Downtime</a></li>
					<li id="email-template-tabs-6"><a href="#email-template-container-6" data-toggle="tab">Drive/Swap Notifications</a></li>
					<li id="email-template-tabs-7"><a href="#email-template-container-7" data-toggle="tab">Internap (SJC)</a></li>
					<li id="email-template-tabs-8"><a href="#email-template-container-8" data-toggle="tab">Status Report</a></li>
					<!-- <li id="email-template-tabs-9"><a href="#email-template-container-9" data-toggle="tab">Incident Response [BETA]</a></li> -->
					<li id="email-template-tabs-10"><a href="#email-template-container-10" data-toggle="tab">Turnover [BETA]</a></li>
				</ul>
					
				<div id="email-template-container" class="tab-content"><!-- style="border-left:1px solid #ddd;border-right:1px solid #ddd;border-bottom:1px solid #ddd;"> -->
					<div id="email-template-container-1" class="generateEmailForm row tab-pane fade in active">
        	               			<form class="col-lg-4 col-md-4 col-sm-6 col-xs-12" id="generateEmailForm-1_SEV1" name="generateEmailForm" role="form" action="previewEmail.php" method="post">
							<h4>Outage - Severity Level 1</h4>
                                                        <label for="generateEmailForm-1_SEV1-1" class="control-label">SEV 1 Outage Type</label><br>
							<select id="generateEmailForm-1_SEV1-1" class="form-control" name="generateEmailForm-1_SEV1-1" style="margin-bottom:9px;margin-top:0px;" required autofocus>
                                                        	<option value="Site Hard Down" descBody="Loss of entire data center/site.  All equipment is unusable and unreachable.  This event will cause loss in revenue and severely impact our customers.">Site Down</option>
                                                        	<option value="Complete Front End Outage" descBody="Loss of all Biddys & Nessys at a data center/site.">Complete Front End Outage</option>
                                                        	<option value="Node Down" descBody="Loss of an entire node at a data center/site.  Possible top of rack switch failure.">Node Down</option>
                                                        	<option value="Nessy Failure" descBody="Loss of greater than half the Nessys in a node.">Nessy Failure (&gt; half node)</option>
                                                        	<option value="Nessy Serving Blanks" descBody="Possible LVS issue in front of the Nessys.  Can also be caused by bad LVS network card or bad LVS failover.">Nessy Serving Blanks</option>
                                                        	<option value="Complete Core Switch Failure" descBody="Failure of both core switches at a data center/site.  Traffic must be failed over to opposite coast.">Complete Core Switch Failure</option>
                                                        	<option value="Carrier Failure" descBody="Failure of both carrier lines to a data center/site.  Immediately open up ticket with provider for escalation and resolution.">Carrier Failure</option>
                                                        	<option value="Persistance Failure" descBody="Failure of HDPs, MEMs, DCSs, P-Nodes.  Possible top of rack switch failure or cage-related issue.">Persistence Failure</option>
                                                        	<option value="Greenplum Outage" descBody="A Greenplum outage could be caused by complete power or hardware failure.  As these clusters are maintained by EMC, bugs in software/firmware or issues with queries could cause lose of the Greenplum cluster.  Upon outage, EMC must be contacted <u>immediately</u> to begin service restoration.">Greenplum Outage</option>
                                                        	<option value="Gluster Backup Cluster" descBody="The Gluster servers that make up the ORD backup cluster reside in the same cage (ORD02).  Many applications and users rely on Gluster for file storage.  Complete loss of connectivity to the Gluster network, the cage they reside in or systematic failure with Gluster.">Gluster Backup Cluster</option>
                                                        	<option value="Single Cage Front End Outage" descBody="Loss of all Biddys & Nessys in a cage.  Possible top of rack switch failure or cage-related power issues.  LVS traffic to Biddys/Nessys needs to be sent to other cages.">Single Cage Front End Outage</option>
                                                        	<option value="RES Outage" descBody="The RES servers require specific procedures to be followed to ensure they remain functioning properly.  All of these servers reside in the ORD data center in the same cage.  If all RES servers were to be rebooted, the cage they reside in goes down or if the ORD data center goes down, we can serve through cache only.">Full RES Outage</option>
                                                        	<option value="CHI Network Outage" descBody="Loss of one or more floors at the Chicago office.  This can be attributed to building power/HVAC issues, multiple network switch failure, complete loss of external connectivity.  This will prevent campaign changes through advantage and access to Studio assets on the Creatives file server.  This will also prevent teams in Chicago from communicating with our other data centers/sites.">CHI Network Outage</option>
                                                        	<option value="Full Biddy Failure" descBody="Failure of all Biddys at a data center/site.  Possible bad code push, cage-related issues, issue with Akamai Global Traffic Management (GTM).">Full Biddy Failure</option>
							</select>
                                                        <label for="generateEmailForm-1_SEV1-2" class="control-label">Failure Location</label><br>
							<select id="generateEmailForm-1_SEV1-2" class="form-control" name="generateEmailForm-1_SEV1-2" style="margin-bottom:9px;margin-top:0px;" required>
                                                        	<option value="Chicago Office">CHI</option>
                                                        	<option value="ORD Data Center">ORD</option>
								<option value="SJC Data Center">SJC</option>
								<option value="IAD Data Center">IAD</option>
								<option value="AMS Data Center">AMS</option>
							</select>
                                                        <label class="control-label">Conference bridge information will automatically be added to email.</label><br>
							<!--
                                                        <label for="generateEmailForm-1_SEV1-3" class="control-label">Jira Ticket</label>
                                                        <input id="generateEmailForm-1_SEV1-3" class="form-control" name="generateEmailForm-1_SEV1-3" placeholder="NOC-1234">
							-->
                                                        <input id="generateEmailForm-1_SEV1-4" name="generateEmailForm_ConfBridge" style="margin-bottom:9px;margin-top:0px;" type="checkbox" value="true" checked hidden>
							<input type="text" name="generateEmail_Form" value="generateEmailPreview-1_SEV1" hidden>
							<input type="reset" class="btn btn-sm btn-default form-button" id="formClearButton" value="Clear Form"><input style="margin-left:10px;" class="btn btn-sm btn-primary form-button" type="submit" value="Preview Email" />
						</form>
        	               			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" style="margin-top:10px;">
							<div class="panel panel-default">
								<div class="panel-heading" id="generateEmailForm-1_SEV1_descTitle">&nbsp;</div>
								<div class="panel-body" id="generateEmailForm-1_SEV1_descBody">
								</div>
							</div>
						</div>
        	               			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" style="margin-top:10px;">
							<div class="panel panel-danger">
								<div class="panel-heading">SEV 1 (Hard down/Complete Outage)</div>
								<div class="panel-body">
									<ul>
										<li>10 minute acknowledgement</li>
										<li>15-20 minute notification</li>
										<li>30 to 45 minutes email updates</li>
										<li>Immediately open conference bridge</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div id="email-template-container-2" class="generateEmailForm row tab-pane fade">
        	               			<form class="col-lg-4 col-md-4 col-sm-6 col-xs-12" id="generateEmailForm-2_SEV2" name="generateEmailForm" role="form" action="previewEmail.php" method="post">
							<h4>Outage - Severity Level 2</h4>
                                                        <label for="generateEmailForm-2_SEV2-1" class="control-label">SEV 2 Outage Type</label><br>
							<select id="generateEmailForm-2_SEV2-1" class="form-control" name="generateEmailForm-2_SEV2-1" style="margin-bottom:9px;margin-top:0px;" required autofocus>
                                                        	<option value="Greenplum Performance Impaired" descBody="Possible top of rack switch failure or thermal events in rack/cage.  May also be caused by multiple server hardware failures.  EMC must be contacted immediately to begin service restoration.">Greenplum Impaired</option>
                                                        	<option value="TRK Outage" descBody="Possible causes include a Nessy cage outage, Akamai DNS issues or LVS issues.  Issues with login.dotomi.com will also cause issues with TRK.">TRK Outage</option>
                                                        	<option value="Top of Rack Switch Failure" descBody="A top of rack switch failure will cause all equipment in its cabinet to become unresponsive.  There is no redundancy for top of rack switches.  If additional failures occur that increase severity of outage, escalate to SEV 1.">Top of Rack Switch Failure</option>
								<option value="Widespread Thermal Events" descBody="Partial failure of data center HVAC or high loads on servers within a single cabinet will cause potential thermal issues.  In the event of full failure of data center HVAC, immediately escalate to SEV 1.">Data Center Thermal Events</option>
								<option value="Bid Partner Bidding Issues" descBody="In the event that bidding falls to 0% or bids with wins rises to 100%, there is a code issue that must be resolved immediately.">Bid Partner Bidding Issues</option>
							</select>
                                                        <label for="generateEmailForm-2_SEV2-2" class="control-label">Failure Location</label><br>
							<select id="generateEmailForm-2_SEV2-2" class="form-control" name="generateEmailForm-2_SEV2-2" style="margin-bottom:9px;margin-top:0px;" required>
                                                        	<option value="Chicago Office">CHI</option>
                                                        	<option value="ORD Data Center">ORD</option>
								<option value="SJC Data Center">SJC</option>
								<option value="IAD Data Center">IAD</option>
							</select>
							<!--
                                                        <label for="generateEmailForm-2_SEV2-3" class="control-label">Jira Ticket</label>
                                                        <input id="generateEmailForm-2_SEV2-3" class="form-control" name="generateEmailForm-2_SEV2-3" placeholder="NOC-1234">
							-->
							<div class="checkbox">
								<label><input id="generateEmailForm-2_SEV2-4" name="generateEmailForm_ConfBridge" type="checkbox" value="true"> Include Conference Bridge Information?</label>
							</div>
							<input type="text" name="generateEmail_Form" value="generateEmailPreview-2_SEV2" hidden>
							<input type="reset" class="btn btn-sm btn-default form-button" id="formClearButton" value="Clear Form"><input style="margin-left:10px;" class="btn btn-sm btn-primary form-button" type="submit" value="Preview Email" />
						</form>
        	               			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" style="margin-top:10px;">
							<div class="panel panel-default">
								<div class="panel-heading" id="generateEmailForm-2_SEV2_descTitle">&nbsp;</div>
								<div class="panel-body" id="generateEmailForm-2_SEV2_descBody">
								</div>
							</div>
						</div>
        	               			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" style="margin-top:10px;">
							<div class="panel panel-warning">
								<div class="panel-heading">SEV 2 (Business Impacting, Not Hard Down)</div>
								<div class="panel-body">
									<ul>
										<li>10 minute acknowledgement</li>
										<li>20-30 minute notification</li>
										<li>Hourly email updates</li>
										<li>2.5 hours after acknowledgement escalate if necessary</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div id="email-template-container-3" class="generateEmailForm row tab-pane fade">
        	               			<form class="col-lg-4 col-md-4 col-sm-6 col-xs-12" id="generateEmailForm-3_SEV3" name="generateEmailForm" role="form" action="previewEmail.php" method="post">
							<h4>Outage - Severity Level 3</h4>
                                                        <label for="generateEmailForm-3_SEV3-1" class="control-label">SEV 3 Outage Type</label><br>
							<select id="generateEmailForm-3_SEV3-1" class="form-control" name="generateEmailForm-3_SEV3-1" style="margin-bottom:9px;margin-top:0px;" required autofocus>
                                                        	<option value="LVS Failure" descBody="Failure of any primary LVS should trigger failover to secondary.  Connections must be checked to ensure LVS does not fall into 'split-brain' situation or leave connections stuck open on failed LVS.  Failure of remaining LVS immediately escalates this issue to SEV 1.">Single LVS Failure</option>
                                                        	<option value="Greenplum Backup Cluster" descBody="Greenplum backups run once per week on Saturday.  The Greenplum cluster is not affected directly, but will no longer have any redundancy through backups.  Any Greenplum cluster failure while Greenplum backup cluster is down immediately escalates this issue to SEV 1.">Greenplum Backup Cluster</option>
								<option value="Single Core Switch Failure" descBody="Loss of redundancy with core switches.  Failover to secondary core switch should be automatic.  If the remaining core switch fails while core switch redundancy is down, immediately escalate this issue to SEV 1.">Single Core Switch Failure</option>
								<option value="Data Center Power Issues" descBody="Occurs when a data center has failed over to generator power or is experiencing flapping on electrical circuits.  Issue needs to be monitored closely to ensure problems do not cascade into greater issues.  Escalate to SEV 1 if issues increase in severity and cause node/cage/site outages.">Data Center Power Issues</option>
								<option value="Data Center Network Issues" descBody="Occurs when network connections to/at a data center/site are causing latency or flapping.  These can be caused by faulty carrier network equipment, accidental cable disconnection, human error or fiber cut.  Escalate to SEV 1 if issues increase in severity and cause node/cage/site outages.">Data Center Network Issues</option>
								<option value="CHI Switch Failure" descBody="Failure of a switch in the Chicago office will result in connectivity loss for up to 48 users.  There is an extra switch in inventory that will be utilized in the event of switch failure.  Switch failure on the 22nd floor may disrupt communications with Equivoice, our VOIP services provider.  With a single switch failure, the switch stack will need to reboot, causing up to 5 minutes network disruption to all users.">CHI Single Switch Failure</option>
								<option value="10G WAN Service Interruption" descBody="Loss of redundancy with 10G connection between two data centers/sites.  This can be caused by a flapping connection, planned/unplanned carrier maintenance or fiber cut.  Failure of remaining 10G connection immediately escalates this issue to SEV 1.">10G WAN Service Interruption</option>
							</select>
                                                        <label for="generateEmailForm-3_SEV3-2" class="control-label">Failure Location</label><br>
							<select id="generateEmailForm-3_SEV3-2" class="form-control" name="generateEmailForm-3_SEV3-2" style="margin-bottom:9px;margin-top:0px;" required>
                                                        	<option value="Chicago Office">CHI</option>
                                                        	<option value="ORD Data Center">ORD</option>
								<option value="SJC Data Center">SJC</option>
								<option value="IAD Data Center">IAD</option>
							</select>
                                                        <label for="generateEmailForm-3_SEV3-4" class="control-label">Affected Circuit</label><br>
							<select name="generateEmailForm-3_SEV3-4" id="generateEmailForm-3_SEV3-4" class="form-control" style="width:70%;min-width:200px;max-width:250px;">
                                                                                                <option value="AMS/AMS5-IAD (ASH_AMS_10GE_61)">AMS/AMS5&lt; &gt;IAD (ASH_AMS_10GE_61)</option>
                                                                                                <option value="AMS/AMS5-ORD (OBK_AMS_10GE_51)">AMS/AMS5&lt; &gt;ORD (OBK_AMS_10GE_51)</option>
                                                                                                <option value="DC6-IAD (WA/OGFS/136874/ /TQW /DV1)">DC6&lt; &gt;IAD 1 (WA/OGFS/136874/ /TQW /DV1)</option>
                                                                                                <option value="DC6-IAD (WA/OGFS/136885/ /TQW)">DC6&lt; &gt;IAD 2 (WA/OGFS/136885/ /TQW)</option>
                                                                                                <option value="ORD-CHI (CH/OGGS/121705//TQW/)">ORD&lt; &gt;CHI 1 (CH/OGGS/121705//TQW/)</option>
                                                                                                <option value="ORD-CHI (OGYX/063471//ZYO)">ORD&lt; &gt;CHI 2 (OGYX/063471//ZYO)</option>
                                                                                                <option value="ORD-IAD (CH/OGYX/116046//TQW/)">ORD&lt; &gt;IAD (CH/OGYX/116046//TQW/)</option>
                                                                                                <option value="ORD-SJ2 (CH/OGGS/121705//TQW/)" selected>ORD&lt; &gt;SJ2 (CH/OGGS/121705//TQW/)</option>
                                                                                                <option value="SJ2-DC6 (SF/LZYX/868266/TQW)">SJ2&lt; &gt;DC6 1 (SF/LZYX/868266/TQW)</option>
                                                                                                <option value="SJ2-DC6 (SF/OGYX/993721/TQW)">SJ2&lt; &gt;DC6 2 (SF/OGYX/993721/TQW)</option>
                                                                                                <option value="SJ2-WLV (LA/LUYX/993781/TQW)">SJ2&lt; &gt;WLV (LA/LUYX/993781/TQW)</option>
                                                                                                <option value="SV1-SJ2 (SF/OGXX/114182/TQW)">SV1&lt; &gt;SJ2 1 (SF/OGXX/114182/TQW)</option>
                                                                                                <option value="SV1-SJ2 (SF/OGXX/922604/TQW)">SV1&lt; &gt;SJ2 2 (SF/OGXX/922604/TQW)</option>
                                                                                                // IP Transit Connections
                                                                                                <option value="AMS/AMS5 (BCTB4121)">AMS/AMS5&lt; &gt;Level 3 (BCTB4121)</option>
                                                                                                <option value="AMS/AMS5 (2049/17062014/sof3)">AMS/AMS5&lt; &gt;Hibernia--Telecity2 (2049/17062014/sof3)</option>
                                                                                                <option value="AMS/AMS5 (52295)">AMS/AMS5&lt; &gt;Telecity (52295)</option>
                                                                                                <option value="AMS/AMS5 (BCTB4123)">AMS/AMS5&lt; &gt;Level 3 (BCTB4123)</option>
                                                                                                <option value="AMS/AMS5 (2049/17062014/sof3)">AMS/AMS5&lt; &gt;InteraXiomAMS5 (2049/17062014/sof3)</option>
                                                                                                <option value="AMS/AMS5 (52296)">AMS/AMS5&lt; &gt;Telecity Xcon (52296)</option>
                                                                                                <option value="DC6 (BCCM0693)">DC6&lt; &gt;Level 3 (BCCM0693)</option>
                                                                                                <option value="DC6 (WA/OGXX/997744/TQW)">DC6&lt; &gt;XO (WA/OGXX/997744/TQW)</option>
                                                                                                <option value="DC6 (20021639-A)">DC6&lt; &gt;Equinix IX (20021639-A)</option>
                                                                                                <option value="DC6 (BCCM0697)">DC6&lt; &gt;Level 3 (BCCM0697)</option>
                                                                                                <option value="DC6 (WA/OGXX/997743/TQW)">DC6&lt; &gt;XO (WA/OGXX/997743/TQW)</option>
                                                                                                <option value="DC6 (20021637-A)">DC6&lt; &gt;Equinix IX (20021637-A)</option>
                                                                                                <option value="HK1 (FRO2005203504CA5)">HK1&lt; &gt;Level 3 (FRO2005203504CA5)</option>
                                                                                                <option value="SH5 (IP5103)">SH5&lt; &gt;China Telecom Americas (IP5103)</option>
                                                                                                <option value="SH5 (HKG/CT-SHI/CT EP091)">SH5&lt; &gt;China Telecom Americas (HKG/CT-SHI/CT EP091)</option>
                                                                                                <option value="SJ2 (cs234816:DAS2.SC9:Te3/2)">SJ2&lt; &gt;Savvis (cs234816:DAS2.SC9:Te3/2)</option>
                                                                                                <option value="SJ2 (cs234816:DAS1.SC9:Te3/2)">SJ2&lt; &gt;Savvis (cs234816:DAS1.SC9:Te3/2)</option>
                                                                                                <option value="SV1 (BCNH4205)">SV1&lt; &gt;Level 3 (BCNH4205)</option>
                                                                                                <option value="SV1 (20024194)">SV1&lt; &gt;Equinix IX (20024194)</option>
                                                                                                <option value="SV1 (BCNH4205)">SV1&lt; &gt;Level 3 (BCNH4205)</option>
                                                                                                <option value="SV1 (Equinix)">SV1&lt; &gt;Equinix IX (20024193)</option>
                                                        </select>
							<label for="generateEmailForm-3_SEV3-3" class="control-label">Jira Ticket</label>
                                                        <input id="generateEmailForm-3_SEV3-3" class="form-control" name="generateEmailForm-3_SEV3-3" placeholder="NOC-1234">
							<input type="text" name="generateEmail_Form" value="generateEmailPreview-3_SEV3" hidden>
							<input type="reset" class="btn btn-sm btn-default form-button" id="formClearButton" value="Clear Form"><input style="margin-left:10px;" class="btn btn-sm btn-primary form-button" type="submit" value="Preview Email" />
						</form>
        	               			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" style="margin-top:10px;">
							<div class="panel panel-default">
								<div class="panel-heading" id="generateEmailForm-3_SEV3_descTitle">&nbsp;</div>
								<div class="panel-body" id="generateEmailForm-3_SEV3_descBody">
								</div>
							</div>
						</div>
        	               			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" style="margin-top:10px;">
							<div class="panel panel-info">
								<div class="panel-heading">SEV 3 (Loss of Redundancy)</div>
								<div class="panel-body">
									<ul>
										<li>10 minute acknowledgement</li>
										<li>30-60 minute notification to team leads</li>
										<li>Email updates at least every 2 hours (depending on failure)</li>
										<li>After 4 hours escalate to SEV_2 if deemed necessary</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div id="email-template-container-4" class="generateEmailForm row tab-pane fade">
        	               			<form class="col-lg-6 col-sm-6 col-xs-12" id="generateEmailForm-4_appRestart" name="generateEmailForm" role="form" action="previewEmail.php" method="post">
							<h4>Application Restart Notification</h4>
							<label for="generateEmailForm-4_appRestart-1" class="control-label">Teams/users to notify</label>
							<input id="generateEmailForm-4_appRestart-1" class="form-control" name="generateEmailForm-4_appRestart-1" required placeholder="Separate teams/email addresses with commas" autofocus>
                                                        <label for="generateEmailForm-4_appRestart-2" class="control-label">Type of server</label><br>
							<select id="generateEmailForm-4_appRestart-2" class="form-control" name="generateEmailForm-4_appRestart-2" style="width:150px;margin-bottom:9px;margin-top:0px;" required>
								<option value="DMA">DMA</option>
                                                        	<option value="DCS">DCS</option>
								<option value="NODA">Noda</option>
								<option value="YOTTA">Yotta</option>
								<option value="NESSY">Nessy</option>
								<option value="BIDDY">Biddy</option>
								<option value="HDP">Hadoop</option>
								<option value="HFL">Flume</option>
								<option value="LVS">LVS</option>
								<option value="GLUSTER">Gluster</option>
								<option value="MAESTRO">Maestro</option>
								<option value="RES">RES</option>
								<option value="OTHER">Other</option>
							</select>
							<label for="generateEmailForm-4_appRestart-3" class="control-label">Hostname</label>
							<input id="generateEmailForm-4_appRestart-3" class="form-control" name="generateEmailForm-4_appRestart-3" placeholder="ServerName" style="width:75%;" required>
							<label for="generateEmailForm-4_appRestart-4" class="control-label">Error observed</label>
							<input id="generateEmailForm-4_appRestart-4" class="form-control" name="generateEmailForm-4_appRestart-4" style="width:75%;" required>
                                                        <label for="generateEmailForm-4_appRestart-5" class="control-label">Jira PROD ticket (if open)</label>
                                                        <input id="generateEmailForm-4_appRestart-5" class="form-control" style="width:75%;" name="generateEmailForm-4_appRestart-5" placeholder="PROD-12345">
                                                        <label for="generateEmailForm-4_appRestart-6" class="control-label">Notification type</label><br>
							<select id="generateEmailForm-4_appRestart-6" class="form-control" name="generateEmailForm-4_appRestart-6" style="width:150px;margin-bottom:9px;margin-top:0px;" required>
                                                        	<option value="CONFIRM">Confirmation</option>
								<option value="RESTART">Restarting</option>
								<option value="COMPLETE">Complete</option>
							</select>
							<input type="text" name="generateEmail_Form" value="generateEmailPreview-4_appRestart" hidden>
							<input type="reset" class="btn btn-sm btn-default form-button" id="formClearButton" value="Clear Form"><input style="margin-left:10px;" class="btn btn-sm btn-primary form-button" type="submit" value="Preview Email" />
						</form>
        	               			<form class="col-lg-6 col-sm-6 col-xs-12" id="generateEmailForm-4_appError" name="generateEmailForm" role="form" action="previewEmail.php" method="post">
							<h4>Application Error Notification</h4>
							<label for="generateEmailForm-4_appError-1" class="control-label">Teams/users to notify</label>
							<input id="generateEmailForm-4_appError-1" class="form-control" name="generateEmailForm-4_appError-1" required placeholder="Separate teams/email addresses with commas">
                                                        <label for="generateEmailForm-4_appError-2" class="control-label">Type of server</label><br>
							<select id="generateEmailForm-4_appError-2" class="form-control" name="generateEmailForm-4_appError-2" style="width:150px;margin-bottom:9px;margin-top:0px;" required>
								<option value="DMA">DMA</option>
                                                        	<option value="DCS">DCS</option>
								<option value="NODA">Noda</option>
								<option value="YOTTA">Yotta</option>
								<option value="NESSY">Nessy</option>
								<option value="BIDDY">Biddy</option>
								<option value="HDP">Hadoop</option>
								<option value="HFL">Flume</option>
								<option value="LVS">LVS</option>
								<option value="GLUSTER">Gluster</option>
								<option value="MAESTRO">Maestro</option>
								<option value="RES">RES</option>
								<option value="OTHER">Other</option>
							</select>
							<label for="generateEmailForm-4_appError-3" class="control-label">Type of error</label>
							<select id="generateEmailForm-4_appError-3" class="form-control" name="generateEmailForm-4_appError-3" style="width:100px;margin-bottom:9px;margin-top:0px;" required>
								<option value="high">High</option>
								<option value="low">Low</option>
							</select>
							<label for="generateEmailForm-4_appError-4" class="control-label">Ganglia error check</label>
							<input id="generateEmailForm-4_appError-4" class="form-control" name="generateEmailForm-4_appError-4" style="width:75%;" placeholder="CPU IDLE, load_fifteen" required>
							<label for="generateEmailForm-4_appError-5" class="control-label">Ganglia alerts</label>
							<textarea id="generateEmailForm-4_appError-5" class="form-control" rows="8" name="generateEmailForm-4_appError-5" required></textarea>
							<input type="text" name="generateEmail_Form" value="generateEmailPreview-4_appError" hidden>
							<input type="text" id="generateEmailForm-4_appError-6" name="generateEmailForm-4_appError-6" hidden>
							<input type="reset" class="btn btn-sm btn-default form-button" id="formClearButton" value="Clear Form"><input style="margin-left:10px;" class="btn btn-sm btn-primary form-button" type="submit" value="Preview Email" />
						</form>
					</div>
					<div id="email-template-container-5" class="generateEmailForm row tab-pane fade">
        	               			<form class="col-lg-6 col-sm-6 col-xs-12" id="generateEmailForm-5_downtime" name="generateEmailForm" role="form" action="previewEmail.php" method="post">
							<h4>Server Downtime Notification</h4>
							<label for="generateEmailForm-5_downtime-1" class="control-label">Teams/users to notify</label>
							<input id="generateEmailForm-5_downtime-1" class="form-control" name="generateEmailForm-5_downtime-1" required placeholder="Separate teams/email addresses with commas" autofocus>
							<label for="generateEmailForm-5_downtime-2" class="control-label">Hostname</label>
							<input id="generateEmailForm-5_downtime-2" class="form-control" name="generateEmailForm-5_downtime-2" placeholder="ServerName" style="width:75%;" required>
							<label for="generateEmailForm-5_downtime-3" class="control-label">Faulty Component(s)</label>
							<input id="generateEmailForm-5_downtime-3" class="form-control" name="generateEmailForm-5_downtime-3" placeholder="OS drive, DRAC, mobo, RAID battery, etc." style="width:75%;" required>
                                                        <label for="generateEmailForm-5_downtime-4" class="control-label">Jira ticket</label>
                                                        <input id="generateEmailForm-5_downtime-4" class="form-control" style="width:75%;" name="generateEmailForm-5_downtime-4" placeholder="NOC-1234" required>
                                                        <label for="generateEmailForm-5_downtime-5" class="control-label">Date</label>
                                                        <input type="text" style="width:50%;" class="form-control" id="generateEmailForm-5_downtime-5" name="generateEmailForm-5_downtime-5" placeholder="mm/dd/yyyy" required>
                                                        <label for="generateEmailForm-5_downtime-6" class="control-label">Start Time</label>
                                                        <input type="text" style="width:40%;" class="form-control" id="generateEmailForm-5_downtime-6" name="generateEmailForm-5_downtime-6" placeholder="00:00" required>
                                                        <label for="generateEmailForm-5_downtime-7" class="control-label">End Time</label>
                                                        <input type="text" style="width:40%;" class="form-control" id="generateEmailForm-5_downtime-7" name="generateEmailForm-5_downtime-7" placeholder="00:00" required>
							<input type="text" name="generateEmail_Form" value="generateEmailPreview-5_downtime" hidden>
							<input type="reset" class="btn btn-sm btn-default form-button" id="formClearButton" value="Clear Form"><input style="margin-left:10px;" class="btn btn-sm btn-primary form-button" type="submit" value="Preview Email" />
						</form>
        	               			<form class="col-lg-6 col-sm-6 col-xs-12" id="generateEmailForm-5_serverRestart" name="generateEmailForm" role="form" action="previewEmail.php" method="post">
							<h4>Server Restart Notification</h4>
							<label for="generateEmailForm-5_serverRestart-1" class="control-label">Teams/users to notify</label>
							<input id="generateEmailForm-5_serverRestart-1" class="form-control" name="generateEmailForm-5_serverRestart-1" required placeholder="Separate teams/email addresses with commas">
							<label for="generateEmailForm-5_serverRestart-2" class="control-label">Affected server(s)</label>
							<input id="generateEmailForm-5_serverRestart2" class="form-control" name="generateEmailForm-5_serverRestart-2" placeholder="ServerName, ServerName2" style="width:75%;" required>
							<label for="generateEmailForm-5_serverRestart-3" class="control-label">Error observed</label>
							<input id="generateEmailForm-5_serverRestart-3" class="form-control" name="generateEmailForm-5_serverRestart-3" style="width:75%;" required>
                                                        <label for="generateEmailForm-5_serverRestart-4" class="control-label">Jira ticket</label>
                                                        <input id="generateEmailForm-5_serverRestart-4" class="form-control" style="width:75%;" name="generateEmailForm-5_serverRestart-4" placeholder="NOC-1234" required>
                                                        <label for="generateEmailForm-5_serverRestart-5" class="control-label">Date</label>
                                                        <input type="text" style="width:50%;" class="form-control" id="generateEmailForm-5_serverRestart-5" name="generateEmailForm-5_serverRestart-5" placeholder="mm/dd/yyyy" required>
                                                        <label for="generateEmailForm-5_serverRestart-6" class="control-label">Time of reboot</label>
                                                        <input type="text" style="width:40%;" class="form-control" id="generateEmailForm-5_serverRestart-6" name="generateEmailForm-5_serverRestart-6" placeholder="00:00" required>
							<input type="text" name="generateEmail_Form" value="generateEmailPreview-5_serverRestart" hidden>
							<input type="reset" class="btn btn-sm btn-default form-button" id="formClearButton" value="Clear Form"><input style="margin-left:10px;" class="btn btn-sm btn-primary form-button" type="submit" value="Preview Email" />
						</form>
					</div>
					<div id="email-template-container-6" class="generateEmailForm row tab-pane fade">
        	               			<form class="col-lg-6 col-sm-6 col-xs-12" id="generateEmailForm-6_driveCapacity" name="generateEmailForm" role="form" action="previewEmail.php" method="post">
							<h4>Drive Capacity Notification</h4>
							<label for="generateEmailForm-6_driveCapacity-1" class="control-label">Teams/users to notify</label>
							<input id="generateEmailForm-6_driveCapacity-1" class="form-control" name="generateEmailForm-6_driveCapacity-1" required placeholder="Separate teams/email addresses with commas" autofocus>
							<label for="generateEmailForm-6_driveCapacity-2" class="control-label">Hostname</label>
							<input id="generateEmailForm-6_driveCapacity-2" class="form-control" name="generateEmailForm-6_driveCapacity-2" placeholder="ServerName" style="width:75%;" required>
							<label for="generateEmailForm-6_driveCapacity-3" class="control-label">Affected folder(s)</label>
							<input id="generateEmailForm-6_driveCapacity-3" class="form-control" name="generateEmailForm-6_driveCapacity-3" placeholder="/opt, /root, /tmp" style="width:50%;" required>
							<label for="generateEmailForm-6_driveCapacity-4" class="control-label">df -h Output</label>
							<textarea id="generateEmailForm-6_driveCapacity-4" class="form-control" rows="8" name="generateEmailForm-6_driveCapacity-4" placeholder="/dev/sda             9.7G  2.7G  6.6G  29% /" required></textarea>
							<label for="generateEmailForm-6_driveCapacity-5" class="control-label"><input id="generateEmailForm-6_driveCapacity-5-check" name="generateEmailForm-6_driveCapacity-5-check" type="checkbox"> du -h Output?</label>
							<textarea id="generateEmailForm-6_driveCapacity-5" class="form-control" rows="8" name="generateEmailForm-6_driveCapacity-5" placeholder="64K     ./path/file" required disabled></textarea>
							<input type="text" name="generateEmail_Form" value="generateEmailPreview-6_driveCapacity" hidden>
							<input type="reset" class="btn btn-sm btn-default form-button" id="formClearButton" value="Clear Form"><input style="margin-left:10px;" class="btn btn-sm btn-primary form-button" type="submit" value="Preview Email" />
						</form>
        	               			<form class="col-lg-6 col-sm-6 col-xs-12" id="generateEmailForm-6_hotSwap" name="generateEmailForm" role="form" action="previewEmail.php" method="post">
							<h4>Notification of Hot Swap</h4>
							<label for="generateEmailForm-6_hotSwap-1" class="control-label">Teams/users to notify</label>
                                                        <input id="generateEmailForm-6_hotSwap-1" class="form-control" name="generateEmailForm-6_hotSwap-1" required placeholder="Separate teams/email address with commas">
                                                        <label for="generateEmailForm-6_hotSwap-2" class="control-label">Hostname</label>
                                                        <input id="generateEmailForm-6_hotSwap-2" class="form-control" name="generateEmailForm-6_hotSwap-2" placeholder="ServerName" style="width:75%;" required>
                                                        <label for="generateEmailForm-6_hotSwap-3" class="control-label">Component</label><br>
                                                        <input id="generateEmailForm-6_hotSwap-3" name="generateEmailForm-6_hotSwap-3" type="radio" value="PSU" required> PSU&nbsp;&nbsp;&nbsp;<input id="generateEmailForm-6_hotSwap-3" name="generateEmailForm-6_hotSwap-3" type="radio" value="hard drive" required> Disk<br>
                                                        <label for="generateEmailForm-6_hotSwap-4" class="control-label" style="margin-top:5px;">Jira ticket</label>
                                                        <input id="generateEmailForm-6_hotSwap-4" class="form-control" style="width:75%;" name="generateEmailForm-6_hotSwap-4" placeholder="NOC-1234" required>
                                                        <label for="generateEmailForm-6_hotSwap-5" class="control-label">Date Performing Hot Swap</label>
                                                        <input type="text" style="width:50%;" class="form-control" id="generateEmailForm-6_hotSwap-5" name="generateEmailForm-6_hotSwap-5" placeholder="mm/dd/yyyy" required>
                                                        <label for="generateEmailForm-6_hotSwap-6" class="control-label">Maintenance Window Start</label>
                                                        <input type="text" style="width:40%;" class="form-control" id="generateEmailForm-6_hotSwap-6" name="generateEmailForm-6_hotSwap-6" placeholder="00:00" required>
                                                        
							<label for="generateEmailForm-6_hotSwap-7" class="control-label">Maintenance Window End</label>
                                                        <input type="text" style="width:40%;" class="form-control" id="generateEmailForm-6_hotSwap-7" name="generateEmailForm-6_hotSwap-7" placeholder="00:00" required>

							<input type="text" name="generateEmail_Form" value="generateEmailPreview-6_hotSwap" hidden>
							<input type="reset" class="btn btn-sm btn-default form-button" id="formClearButton" value="Clear Form"><input style="margin-left:10px;" class="btn btn-sm btn-primary form-button" type="submit" value="Preview Email" />
						</form>
					</div>
					<div id="email-template-container-7" class="generateEmailForm row tab-pane fade">
						<div class="container col-6 col-sm-6 col-lg-6" style="padding-left:0px;padding-right:0px;">
        	               			<form class="col-lg-12 col-sm-12 col-xs-12" id="generateEmailForm-7_tech" name="generateEmailForm" role="form" action="previewEmail.php" method="post">
							<h4>Internap Access Request</h4>
							<label for="generateEmailForm-7_tech-1" class="control-label">Technician Name</label>
							<input type="text" style="width:75%;" class="form-control" id="generateEmailForm-7_tech-1" name="generateEmailForm-7_tech-1" placeholder="Tech first and last name" required autofocus>
							<label for="generateEmailForm-7_tech-2" class="control-label">Date of Access</label>
							<input type="text" style="width:50%;" class="form-control" id="generateEmailForm-7_tech-2" name="generateEmailForm-7_tech-2" placeholder="mm/dd/yyyy" required>
							<label for="generateEmailForm-7_tech-3" class="control-label">Arrival Time (PST)</label>
							<input type="text" style="width:40%;" class="form-control" id="generateEmailForm-7_tech-3" name="generateEmailForm-7_tech-3" placeholder="00:00" required>
							<label for="generateEmailForm-7_tech-4" class="control-label">Departure Time (PST)</label>
							<input type="text" style="width:40%;" class="form-control" id="generateEmailForm-7_tech-4" name="generateEmailForm-7_tech-4" placeholder="00:00" required>
							<label for="generateEmailForm-7_tech-5" class="control-label">Server Location</label>
							<input type="text" style="width:50%;" class="form-control" id="generateEmailForm-7_tech-5" name="generateEmailForm-7_tech-5" placeholder="Cage | Cabinet | Rack | RU" required>
							<label for="generateEmailForm-7_tech-6" class="control-label">Which Data Center?</label><br>
                                                        <input id="generateEmailForm-7_tech-6" name="generateEmailForm-7_tech-6" type="radio" value="SJE011" required> SJE011&nbsp;&nbsp;&nbsp;<input id="generateEmailForm-7_tech-6" name="generateEmailForm-7_tech-6" type="radio" value="SJE014" required> SJE014<br>
							<input type="text" name="generateEmail_Form" value="generateEmailPreview-7_tech" hidden>
							<input type="reset" class="btn btn-sm btn-default form-button" id="formClearButton" value="Clear Form"><input style="margin-left:10px;" class="btn btn-sm btn-primary form-button" type="submit" value="Preview Email" />
						</form>
        	               			<form class="col-lg-12 col-sm-12 col-xs-12" id="generateEmailForm-7_package" name="generateEmailForm" role="form" action="previewEmail.php" method="post">
							<h4>Internap Inbound Package Notification</h4>
							<label for="generateEmailForm-7_package-1" class="control-label">Package DPS #</label>
							<input type="text" style="width:75%;" class="form-control" id="generateEmailForm-7_package-1" name="generateEmailForm-7_package-1" placeholder="DPS # of inbound package(s)" required>
							<label for="generateEmailForm-7_package-2" class="control-label">Expected # of packages</label>
							<input type="text" style="width:40%;" class="form-control" id="generateEmailForm-7_package-2" name="generateEmailForm-7_package-2" placeholder="How many?" required>
                                                        <label for="generateEmailForm-7_package-3" class="control-label">Which Data Center?</label><br>
                                                        <input id="generateEmailForm-7_package-3" name="generateEmailForm-7_package-3" type="radio" value="SJE011" required> SJE011&nbsp;&nbsp;&nbsp;<input id="generateEmailForm-7_package-3" name="generateEmailForm-7_package-3" type="radio" value="SJE014" required> SJE014<br>
							<input type="text" name="generateEmail_Form" value="generateEmailPreview-7_package" hidden>
							<input type="reset" class="btn btn-sm btn-default form-button" id="formClearButton" value="Clear Form"><input style="margin-left:10px;" class="btn btn-sm btn-primary form-button" type="submit" value="Preview Email" />
						</form>
						</div>
						<div class="container col-6 col-sm-6 col-lg-6" style="padding-left:0px;padding-right:0px;">
        	               			<form class="col-lg-12 col-sm-12 col-xs-12" id="generateEmailForm-7_remoteHands" name="generateEmailForm" role="form" action="previewEmail.php" method="post">
							<h4>Internap Remote Hands Request</h4>
							<label for="generateEmailForm-7_remoteHands-1" class="control-label">Task</label>
							<input type="text" style="width:65%;" class="form-control" id="generateEmailForm-7_remoteHands-1" name="generateEmailForm-7_remoteHands-1" placeholder="Task description" required>
							<label for="generateEmailForm-7_remoteHands-2" class="control-label">Date of Request (PST)</label>
							<input type="text" style="width:50%;" class="form-control" id="generateEmailForm-7_remoteHands-2" name="generateEmailForm-7_remoteHands-2" placeholder="mm/dd/yyyy" required>
							<label for="generateEmailForm-7_remoteHands-3" class="control-label">Time of Request (PST)</label>
							<input type="text" style="width:40%;" class="form-control" id="generateEmailForm-7_remoteHands-3" name="generateEmailForm-7_remoteHands-3" placeholder="00:00" required>
							<div class="col-lg-12" style="padding-left:0px;">
								<div class="col-lg-6" id="generateEmailForm-7_remoteHands-equipInfo" style="padding-left:0px;">
									<label for="generateEmailForm-7_remoteHands-4a" class="control-label">Equipment Info</label>
									<input type="text" class="form-control" id="generateEmailForm-7_remoteHands-4a" name="generateEmailForm-7_remoteHands-4a" placeholder="Model (ex. PowerEdge R420, Cisco 3750-X, etc.)">
									<input type="text" style="display:inline-block;" class="form-control" id="generateEmailForm-7_remoteHands-4b" name="generateEmailForm-7_remoteHands-4b" placeholder="Service Tag/Serial #"><br>
									<input type="text" style="display:inline-block;" class="form-control" id="generateEmailForm-7_remoteHands-4c" name="generateEmailForm-7_remoteHands-4c" placeholder="Location (ex. CG.005.001.001)">
									<div style="margin-bottom:10px;" class="btn btn-sm btn-default" id="generateEmailForm-7_remoteHands-equipInfo-formClearButton">Clear Info</div><div style="margin-left:10px;margin-bottom:10px;" class="btn btn-sm btn-success" id="generateEmailForm-7_remoteHands-equipInfo-addToListButton">Add to List</div>
								</div>
								<div class="col-lg-6" style="padding-left:0px;">
									<label class="control-label">Equipment List</label>
									<div id="generateEmailForm-7_remoteHands-equipList"><div id="generateEmailForm-7_remoteHands-equipList-msg">No equipment added.</div></div>
									<input type="text" id="generateEmailForm-7_remoteHands-equipList-count" name="generateEmailForm-7_remoteHands-equipList-count" value="0" hidden>
								</div>
							</div>
							<label for="generateEmailForm-7_remoteHands-5" class="control-label">Additional Information</label>
							<!-- <input type="text" style="width:75%;" class="form-control" id="generateEmailForm-7_remoteHands-5" name="generateEmailForm-7_remoteHands-5" placeholder="Additional information about the request" required> -->
							<textarea style="width:75%;" class="form-control" id="generateEmailForm-7_remoteHands-5" name="generateEmailForm-7_remoteHands-5" rows="4" placeholder="Additional information about the request" required></textarea>
							<!-- <label for="generateEmailForm-7_remoteHands-6" class="control-label">Your Phone Number</label>
							<input type="text" style="width:30%;" class="form-control" id="generateEmailForm-7_remoteHands-6" name="generateEmailForm-7_remoteHands-6" placeholder="(xxx)-xxx-xxxx" required> -->
                                                        <label for="generateEmailForm-7_remoteHands-7" class="control-label">Which Data Center?</label><br>
                                                        <input id="generateEmailForm-7_remoteHands-7" name="generateEmailForm-7_remoteHands-7" type="radio" value="SJE011" required> SJE011&nbsp;&nbsp;&nbsp;<input id="generateEmailForm-7_remoteHands-7" name="generateEmailForm-7_remoteHands-7" type="radio" value="SJE014" required> SJE014<br>
							<input type="text" name="generateEmail_Form" value="generateEmailPreview-7_remoteHands" hidden>
							<input type="reset" class="btn btn-sm btn-default form-button" id="formClearButton" value="Clear Form"><input style="margin-left:10px;" class="btn btn-sm btn-primary form-button" type="submit" value="Preview Email" />
						</form>
						</div>
					</div>
					<div id="email-template-container-8" class="generateEmailForm row tab-pane fade">
        	               			<div class="col-lg-6 col-sm-6 col-xs-12">
							<h4>Add Items to Status Report</h4>
        	               				<form id="generateEmailForm-8_statusReport-A" name="generateEmailForm" role="form" onsubmit="return addToStatusReport('generateEmailForm-8_statusReport-A')">
								<label for="generateEmailForm-8_statusReport-A-1" class="control-label">Hot Items from Today</label>
								<textarea class="form-control" rows="2" id="generateEmailForm-8_statusReport-A-1_input" name="generateEmailForm-8_statusReport-A-1" placeholder="Hot item description" required></textarea>
								<input type="text" style="width:50%;" class="form-control" id="generateEmailForm-8_statusReport-A-5_input" name="generateEmailForm-8_statusReport-A-5" placeholder="Time issue manifested" required>
								<textarea class="form-control" rows="2" id="generateEmailForm-8_statusReport-A-2_input" name="generateEmailForm-8_statusReport-A-2" placeholder="Impact observed/experienced" required></textarea>
								<textarea class="form-control" rows="2" id="generateEmailForm-8_statusReport-A-3_input" name="generateEmailForm-8_statusReport-A-3" placeholder="Steps taken to resolve issue" required></textarea>
								<textarea class="form-control" rows="2" id="generateEmailForm-8_statusReport-A-4_input" name="generateEmailForm-8_statusReport-A-4" placeholder="Current status of hot item" required></textarea>
								<button type="reset" class="btn btn-sm btn-default form-button" id="formClearButton">Clear Hot Item Fields</button><button style="margin-left:10px;" class="btn btn-sm btn-success form-button" type="submit">Add to Report</button>
							</form><hr>
        	               				<form id="generateEmailForm-8_statusReport-B" name="generateEmailForm" role="form" onsubmit="return addToStatusReport('generateEmailForm-8_statusReport-B')">
								<label for="generateEmailForm-8_statusReport-B-1" class="control-label">Scheduled Maintenance and Deployments</label>
								<input type="text" class="form-control" id="generateEmailForm-8_statusReport-B-1_input" name="generateEmailForm-8_statusReport-B-1" placeholder="JIRA ticket number" required>
								<input type="text" class="form-control" id="generateEmailForm-8_statusReport-B-2_input" name="generateEmailForm-8_statusReport-B-2" placeholder="Deployment/maintenance summary" required>
								<input type="text" style="width:50%;" class="form-control datetimeRange" id="generateEmailForm-8_statusReport-B-3_input" name="generateEmailForm-8_statusReport-B-3" placeholder="Start time" required>
								<input type="text" style="width:50%;" class="form-control datetimeRange" id="generateEmailForm-8_statusReport-B-4_input" name="generateEmailForm-8_statusReport-B-4" placeholder="End time" required>
								<input type="text" style="width:50%;" class="form-control" id="generateEmailForm-8_statusReport-B-5_input" name="generateEmailForm-8_statusReport-B-5" placeholder="Current status" required>
								<button type="reset" class="btn btn-sm btn-default form-button" id="formClearButton">Clear Deployment Fields</button><button style="margin-left:10px;" class="btn btn-sm btn-success form-button" type="submit">Add to Report</button>
							</form><!-- <hr>
        	               				<form id="generateEmailForm-8_statusReport-C" name="generateEmailForm" role="form" onsubmit="return addToStatusReport('generateEmailForm-8_statusReport-C')">
								<label for="generateEmailForm-8_statusReport-C-1" class="control-label">Planned Vendor Maintenance</label>
								<input type="text" class="form-control" id="generateEmailForm-8_statusReport-C-1_input" name="generateEmailForm-8_statusReport-C-1" placeholder="Vendor ticket number" required>
								<button type="reset" class="btn btn-sm btn-default form-button" id="formClearButton">Clear Vendor Fields</button><button style="margin-left:10px;" class="btn btn-sm btn-success form-button" type="submit">Add to Report</button>
							</form> -->
						</div>
        	               			<form class="col-lg-6 col-sm-6 col-xs-12" id="generateEmailForm-8_statusReport" name="generateEmailForm" role="form" action="previewEmail.php" method="post">
							<h4>Daily Status Report</h4>
							<label class="control-label">Hot Items from Today</label>
							<div id="generateEmailForm-8_statusReport-hotItems"><div id="generateEmailForm-8_statusReport-hotItems-msg">No hot items today.</div></div>
							<input type="text" id="generateEmailForm-8_statusReport-hotItems-count" name="generateEmailForm-8_statusReport-hotItems-count" value="0" hidden>
							<hr><label class="control-label">Scheduled Maintenance and Deployments</label>
							<div id="generateEmailForm-8_statusReport-deployments"><div id="generateEmailForm-8_statusReport-deployments-msg">No scheduled maintenance/deployments today.</div></div>
							<input type="text" id="generateEmailForm-8_statusReport-deployments-count" name="generateEmailForm-8_statusReport-deployments-count" value="0" hidden>
							<hr><label class="control-label">Planned Vendor Maintenance</label>
							<div id="generateEmailForm-8_statusReport-vendorMaint"><div id="generateEmailForm-8_statusReport-vendorMaint-msg">No planned vendor maintenance today.</div></div>
							<input type="text" id="generateEmailForm-8_statusReport-vendorMaint-count" name="generateEmailForm-8_statusReport-vendorMaint-count" value="0" hidden>
							<hr><input type="text" name="generateEmail_Form" value="generateEmailPreview-8_statusReport" hidden>
							<input type="text" id="generateEmailForm-8_statusReport-tz" name="generateEmailForm-8_statusReport-tz" hidden>
							<button class="btn btn-sm btn-primary form-button" type="submit">Preview Email</button>
						</form>
					</div>
					<div id="email-template-container-9" class="generateEmailForm row tab-pane fade">
                                                <div class="col-lg-6 col-sm-6 col-xs-12">
                                                        <h4>Incident Response Communication</h4>
                                                        <form id="generateEmailForm-9_incidentResponse" name="generateEmailForm" role="form" onsubmit="return addToStatusReport('generateEmailForm-8_statusReport-A')">
                                                                <label for="generateEmailForm-9_incidentResponse-1" class="control-label">Incident Details</label>
                                                                <textarea class="form-control" rows="2" id="generateEmailForm-8_statusReport-A-1_input" name="generateEmailForm-8_statusReport-A-1" placeholder="Incident description" required></textarea>
                                                                <input type="text" style="width:50%;" class="form-control" id="generateEmailForm-8_statusReport-A-5_input" name="generateEmailForm-8_statusReport-A-5" placeholder="Time incident manifested" required>
								<input type="text" style="width:50%;" class="form-control" id="generateEmailForm-8_statusReport-B-1_input" name="generateEmailForm-8_statusReport-B-1" placeholder="NOC Incident JIRA ticket" required>
                                                                <textarea class="form-control" rows="2" id="generateEmailForm-8_statusReport-A-2_input" name="generateEmailForm-8_statusReport-A-2" placeholder="Impact observed/experienced" required></textarea>
                                                                <textarea class="form-control" rows="2" id="generateEmailForm-8_statusReport-A-3_input" name="generateEmailForm-8_statusReport-A-3" placeholder="Steps taken to resolve issue" required></textarea>
                                                                <textarea class="form-control" rows="2" id="generateEmailForm-8_statusReport-A-4_input" name="generateEmailForm-8_statusReport-A-4" placeholder="Current status of hot item" required></textarea>
                                                                <button type="reset" class="btn btn-sm btn-default form-button" id="formClearButton">Clear Fields</button><button style="margin-left:10px;" class="btn btn-sm btn-primary form-button" type="submit">Preview Email</button>
                                                        </form>
						</div>
                                                <div class="col-lg-6 col-sm-6 col-xs-12">
                                                        <h4>Incident Follow-Up Communication</h4>
                                                        <form id="generateEmailForm-9_statusReport-A" name="generateEmailForm" role="form" onsubmit="return addToStatusReport('generateEmailForm-8_statusReport-A')">
                                                                <label for="generateEmailForm-8_statusReport-A-1" class="control-label">Incident Details</label>
                                                                <textarea class="form-control" rows="2" id="generateEmailForm-8_statusReport-A-1_input" name="generateEmailForm-8_statusReport-A-1" placeholder="Incident description" required></textarea>
                                                                <input type="text" style="width:50%;" class="form-control" id="generateEmailForm-8_statusReport-A-5_input" name="generateEmailForm-8_statusReport-A-5" placeholder="Time incident manifested" required>
								<input type="text" style="width:50%;" class="form-control" id="generateEmailForm-8_statusReport-B-1_input" name="generateEmailForm-8_statusReport-B-1" placeholder="NOC Incident JIRA ticket" required>
                                                                <textarea class="form-control" rows="2" id="generateEmailForm-8_statusReport-A-2_input" name="generateEmailForm-8_statusReport-A-2" placeholder="Impact observed/experienced" required></textarea>
                                                                <textarea class="form-control" rows="2" id="generateEmailForm-8_statusReport-A-3_input" name="generateEmailForm-8_statusReport-A-3" placeholder="Steps taken to resolve issue" required></textarea>
                                                                <textarea class="form-control" rows="2" id="generateEmailForm-8_statusReport-A-4_input" name="generateEmailForm-8_statusReport-A-4" placeholder="Current status of hot item" required></textarea>
                                                                <button type="reset" class="btn btn-sm btn-default form-button" id="formClearButton">Clear Fields</button><button style="margin-left:10px;" class="btn btn-sm btn-primary form-button" type="submit">Preview Email</button>
                                                        </form>
						</div>
					</div>
					<div id="email-template-container-10" class="generateEmailForm row tab-pane fade">
        	               			<div class="col-lg-6 col-sm-6 col-xs-12">
							<h4>Add Items to Turnover Email</h4>
							<form id="generateEmailForm-10_turnover" name="generateEmailForm" role="form" onsubmit="return addToTurnover('generateEmailForm-10_turnover')">
								<label for="generateEmailForm-10_turnover-1" class="control-label">Jira Issues</label>
								<div id="generateEmailForm-10_turnover-jiraIssues"><div id="generateEmailForm-10_turnover-jiraIssues-msg">No open Jira issues found.</div></div>
								<!-- <button type="reset" class="btn btn-sm btn-default form-button" id="formClearButton">Clear Deployment Fields</button><button class="btn btn-sm btn-success form-button" type="submit">Add to Turnover</button> -->
							</form><hr>
        	               				<form id="generateEmailForm-10_turnover-B" name="generateEmailForm" role="form" onsubmit="return addToTurnover(this)">
								<label for="generateEmailForm-10_turnover-B-1" class="control-label">Events That Occurred</label>
								<textarea class="form-control" rows="2" id="generateEmailForm-10_turnover-B-1_input" name="generateEmailForm-10_turnover-B-1" placeholder="Hot item description" required></textarea>
								<input type="text" style="width:50%;" class="form-control" id="generateEmailForm-10_turnover-B-5_input" name="generateEmailForm-10_turnover-B-5" placeholder="Time issue manifested" required>
								<textarea class="form-control" rows="2" id="generateEmailForm-10_turnover-B-2_input" name="generateEmailForm-10_turnover-B-2" placeholder="Impact observed/experienced" required></textarea>
								<textarea class="form-control" rows="2" id="generateEmailForm-10_turnover-B-3_input" name="generateEmailForm-10_turnover-B-3" placeholder="Steps taken to resolve issue" required></textarea>
								<textarea class="form-control" rows="2" id="generateEmailForm-10_turnover-B-4_input" name="generateEmailForm-10_turnover-B-4" placeholder="Current status of hot item" required></textarea>
								<!-- <button type="reset" class="btn btn-sm btn-default form-button" id="formClearButton">Clear Hot Item Fields</button> --><button class="btn btn-sm btn-success form-button" type="submit">Add to Turnover</button>
							</form><hr>
							<form id="generateEmailForm-10_turnover-C" name="generateEmailForm" role="form" onsubmit="return addToTurnover('generateEmailForm-10_turnover-C')">
								<label for="generateEmailForm-10_turnover-C-1" class="control-label">Ongoing Issues</label>
								<div id="generateEmailForm-10_turnover-C_ongoingIssues"><div id="generateEmailForm-10_turnover-C_ongoingIssues-msg">No ongoing issues found on FireBoard.</div></div>
								<!-- <input type="text" class="form-control" id="generateEmailForm-10_turnover-C-1_input" name="generateEmailForm-10_turnover-C-1" placeholder="Vendor ticket number" required> -->
								<!-- <button type="reset" class="btn btn-sm btn-default form-button" id="formClearButton">Clear Vendor Fields</button> --><button class="btn btn-sm btn-success form-button" type="submit">Add to Turnover</button>
							</form>
						</div>
        	               			<form class="col-lg-6 col-sm-6 col-xs-12" id="generateEmailForm-10_turnover" name="generateEmailForm" role="form" action="previewEmail.php" method="post">
							<h4>Shift Turnover Email</h4>
							<label class="control-label">Jira Issues</label>
							<div id="generateEmailForm-10_turnover-jiraIssuesAdded"><div id="generateEmailForm-10_turnover-jiraIssuesAdded-msg">No Jira issues to handoff.</div></div>
							<input type="text" id="generateEmailForm-10_turnover-jiraIssuesAdded-count" name="generateEmailForm-10_turnover-jiraIssuesAdded-count" value="0" hidden>
							<hr><label class="control-label">Events That Occurred</label>
							<div id="generateEmailForm-10_turnover-eventsAdded"><div id="generateEmailForm-10_turnover-eventsAdded-msg">No events occurred.</div></div>
							<input type="text" id="generateEmailForm-10_turnover-eventsAdded-count" name="generateEmailForm-10_turnover-eventsAdded-count" value="0" hidden>
							<hr><label class="control-label">Ongoing Issues</label>
							<div id="generateEmailForm-10_turnover-ongoingAdded"><div id="generateEmailForm-10_turnover-ongoingAdded-msg">No ongoing issues at this time.</div></div>
							<input type="text" id="generateEmailForm-10_turnover-ongoingAdded-count" name="generateEmailForm-10_turnover-ongoingAdded-count" value="0" hidden>
							<hr><input type="text" name="generateEmail_Form" value="generateEmailPreview-10_turnover" hidden>
							<input type="text" id="generateEmailForm-10_turnover-tz" name="generateEmailForm-10_turnover-tz" hidden>
							<button class="btn btn-sm btn-primary form-button" type="submit">Preview Email</button>
						</form>
					</div>
				</div>
			</div>
		</div>

		<script>
			var tz=jstz.determine();
			$(document).ready(function(){
				$(document).attr('title', $(document).attr('title')+' - Send NOC Email');
				$('#nav-active-communication').addClass('active');
				<?PHP checkAlertGeneration(); ?>

				var mailSuccess = window.location.search;
				mailSuccess = mailSuccess.split("=");
				mailSuccess = mailSuccess[1];
				if(mailSuccess == 'success'){
					$('#myModal').modal('show');
				}

				$('#generateEmailForm-6_driveCapacity-5-check').on('click', function(){
                                        if($(this).prop("checked")==true){
                                                $("#generateEmailForm-6_driveCapacity-5").prop('disabled',false);
                                        }else{
                                                $("#generateEmailForm-6_driveCapacity-5").prop('disabled',true);
                                        }
                                });

				$('#generateEmailForm-1_SEV1-1').change(function(){
                                        $('#generateEmailForm-1_SEV1_descTitle').html($('#generateEmailForm-1_SEV1-1 option:selected').text());
                                        $('#generateEmailForm-1_SEV1_descBody').html($('#generateEmailForm-1_SEV1-1 option:selected').attr('descBody'));
                                });
				$('#generateEmailForm-1_SEV1_descTitle').html($('#generateEmailForm-1_SEV1-1 option:selected').text());
				$('#generateEmailForm-1_SEV1_descBody').html($('#generateEmailForm-1_SEV1-1 option:selected').attr('descBody'));

				$('#generateEmailForm-2_SEV2-1').change(function(){
                                        $('#generateEmailForm-2_SEV2_descTitle').html($('#generateEmailForm-2_SEV2-1 option:selected').text());
                                        $('#generateEmailForm-2_SEV2_descBody').html($('#generateEmailForm-2_SEV2-1 option:selected').attr('descBody'));
                                });
				$('#generateEmailForm-2_SEV2_descTitle').html($('#generateEmailForm-2_SEV2-1 option:selected').text());
				$('#generateEmailForm-2_SEV2_descBody').html($('#generateEmailForm-2_SEV2-1 option:selected').attr('descBody'));

				$('#generateEmailForm-3_SEV3-1').change(function(){
                                        $('#generateEmailForm-3_SEV3_descTitle').html($('#generateEmailForm-3_SEV3-1 option:selected').text());
                                        $('#generateEmailForm-3_SEV3_descBody').html($('#generateEmailForm-3_SEV3-1 option:selected').attr('descBody'));
                                });
				$('#generateEmailForm-3_SEV3_descTitle').html($('#generateEmailForm-3_SEV3-1 option:selected').text());
				$('#generateEmailForm-3_SEV3_descBody').html($('#generateEmailForm-3_SEV3-1 option:selected').attr('descBody'));
				if( $('#generateEmailForm-3_SEV3-1').val()==='10G WAN Service Interruption' ) {
                                        $("#generateEmailForm-3_SEV3-4").prop('disabled',false);
					$("#generateEmailForm-3_SEV3-2").prop('disabled',true);
				}else{
                                        $("#generateEmailForm-3_SEV3-4").prop('disabled',true);
                                        $("#generateEmailForm-3_SEV3-2").prop('disabled',false);
				}
				$('#generateEmailForm-3_SEV3-1').change(function(){
					if( $('#generateEmailForm-3_SEV3-1').val()==='10G WAN Service Interruption' ) {
                                                $("#generateEmailForm-3_SEV3-4").prop('disabled',false);
                                                $("#generateEmailForm-3_SEV3-2").prop('disabled',true);
					}else{
                                                $("#generateEmailForm-3_SEV3-4").prop('disabled',true);
                                                $("#generateEmailForm-3_SEV3-2").prop('disabled',false);
					}
					console.log( $('#generateEmailForm-3_SEV3-1').val() );
				});
				
				$('#generateEmailForm-5_serverRestart-5').datepicker();
                                $('#generateEmailForm-5_serverRestart-6').timeEntry({spinnerImage: '', showSeconds: false, show24Hours: false, useMouseWheel: true});
                                $('#generateEmailForm-5_serverRestart-7').timeEntry({spinnerImage: '', showSeconds: false, show24Hours: false, useMouseWheel: true});
				$('#generateEmailForm-5_downtime-5').datepicker();
                                $('#generateEmailForm-5_downtime-6').timeEntry({spinnerImage: '', showSeconds: false, show24Hours: false, useMouseWheel: true});
                                $('#generateEmailForm-5_downtime-7').timeEntry({spinnerImage: '', showSeconds: false, show24Hours: false, useMouseWheel: true});
				$('#generateEmailForm-6_hotSwap-5').datepicker();
                                $('#generateEmailForm-6_hotSwap-6').timeEntry({spinnerImage: '', showSeconds: false, show24Hours: false, useMouseWheel: true});
                                $('#generateEmailForm-6_hotSwap-7').timeEntry({spinnerImage: '', showSeconds: false, show24Hours: false, useMouseWheel: true});
				$('#generateEmailForm-7_tech-2').datepicker();
                                $('#generateEmailForm-7_tech-3').timeEntry({spinnerImage: '', showSeconds: false, show24Hours: false, useMouseWheel: true});
                                $('#generateEmailForm-7_tech-4').timeEntry({spinnerImage: '', showSeconds: false, show24Hours: false, useMouseWheel: true});
				function datetimeRange(input) {
					return {minDatetime: (input.id === 'generateEmailForm-8_statusReport-B-4_input' ?
					$('#generateEmailForm-8_statusReport-B-3_input').datetimeEntry('getDatetime') : null),
					maxDatetime: (input.id === 'generateEmailForm-8_statusReport-B-3_input' ?
					$('#generateEmailForm-8_statusReport-B-4_input').datetimeEntry('getDatetime') : null)};
                                }
				$('input.datetimeRange').datetimeEntry({spinnerImage: '',beforeShow: datetimeRange,datetimeFormat: 'H:Ma'});
                                //$('#generateEmailForm-8_statusReport-B-3_input').timeEntry({spinnerImage: '', showSeconds: false, show24Hours: false, useMouseWheel: true});
                                //$('#generateEmailForm-8_statusReport-B-4_input').timeEntry({spinnerImage: '', showSeconds: false, show24Hours: false, useMouseWheel: true});
                                $('#generateEmailForm-8_statusReport-A-5_input').timeEntry({spinnerImage: '', showSeconds: false, show24Hours: false, useMouseWheel: true});
				$('#generateEmailForm-7_remoteHands-2').datepicker();
                                $('#generateEmailForm-7_remoteHands-3').timeEntry({spinnerImage: '', showSeconds: false, show24Hours: false, useMouseWheel: true});

				function split( val ) {
					return val.split( /,\s*/ );
				}
				function extractLast( term ) {
					return split( term ).pop();
				}

				$( "#generateEmailForm-6_hotSwap-1,#generateEmailForm-6_driveCapacity-1,#generateEmailForm-5_downtime-1,#generateEmailForm-5_serverRestart-1,#generateEmailForm-4_appError-1,#generateEmailForm-4_appRestart-1" )
					// don't navigate away from the field on tab when selecting an item
				.bind( "keydown", function( event ) {
					if ( event.keyCode === $.ui.keyCode.TAB &&
					$( this ).data( "ui-autocomplete" ).menu.active ) {
						event.preventDefault();
					}
				})
				.autocomplete({
					minLength: 0,
					source: function( request, response ) {
						// delegate back to autocomplete, but extract the last term
					response( $.ui.autocomplete.filter(
						emailTeamList, extractLast( request.term ) ) );
					},
					focus: function() {
						// prevent value inserted on focus
						return false;
					},
					select: function( event, ui ) {
						var terms = split( this.value );
						// remove the current input
						terms.pop();
						// add the selected item
						terms.push( ui.item.value );
						// add placeholder to get the comma-and-space at the end
						terms.push( "" );
						this.value = terms.join( ", " );
						return false;
					}
				});
				
				$( "#generateEmailForm-4_appError-4" )
                                        // don't navigate away from the field on tab when selecting an item
                                .bind( "keydown", function( event ) {
                                        if ( event.keyCode === $.ui.keyCode.TAB &&
                                        $( this ).data( "ui-autocomplete" ).menu.active ) {
                                                event.preventDefault();
                                        }
                                })
                                .autocomplete({
                                        minLength: 0,
                                        source: function( request, response ) {
                                                // delegate back to autocomplete, but extract the last term
                                        response( $.ui.autocomplete.filter(
                                                gangliaErrorCheckList, extractLast( request.term ) ) );
                                        },
                                        focus: function() {
                                                // prevent value inserted on focus
                                                return false;
                                        },
                                        select: function( event, ui ) {
                                                var terms = split( this.value );
                                                // remove the current input
                                                terms.pop();
                                                // add the selected item
                                                terms.push( ui.item.value );
                                                // add placeholder to get the comma-and-space at the end
                                                terms.push( "" );
                                                this.value = terms.join( "" );
                                                return false;
                                        }
                                });

			});

                        var count_serverList=1;

			$(" #generateEmailForm-7_remoteHands-equipInfo-formClearButton ").on('click',function(){
                                $(" #generateEmailForm-7_remoteHands-equipInfo input ").val('');
			});
			
			$(" #generateEmailForm-7_remoteHands-equipInfo-addToListButton ").on('click',function(){
                        	console.log(count_serverList);
				var equipInfoValid = 0;
				if( $( "#generateEmailForm-7_remoteHands-4a" ).val().trim()!=''){
					equipInfoValid++;
				}
				if( $( "#generateEmailForm-7_remoteHands-4b" ).val().trim()!=''){
					equipInfoValid++;
				}
				if( $( "#generateEmailForm-7_remoteHands-4c" ).val().trim()!='' ){
					equipInfoValid++;
				}
				if(equipInfoValid == 3){
					if(count_serverList>0){
                        	        	$(' #generateEmailForm-7_remoteHands-equipList-msg ').hide();
	                        	}
                                	$(' #generateEmailForm-7_remoteHands-equipList ').append('<div id="generateEmailForm-7_remoteHands-equipList_'+count_serverList+'" style="margin-bottom:5px;"><button class="btn btn-danger btn-xs remove-report-item" id="generateEmailForm-7_remoteHands-equipList_'+count_serverList+'-remove" type="button">Remove</button> <button class="btn btn-default btn-xs" id="generateEmailForm-7_remoteHands-equipList_'+count_serverList+'-edit" type="button">Edit</button> '+$(' #generateEmailForm-7_remoteHands-4b ').val().trim()+'<input type="text" name="generateEmailForm-7_remoteHands-equipList_'+count_serverList+'-model" value="'+$(' #generateEmailForm-7_remoteHands-4a ').val().trim()+'" hidden><input type="text" name="generateEmailForm-7_remoteHands-equipList_'+count_serverList+'-svctag" value="'+$(' #generateEmailForm-7_remoteHands-4b ').val().toUpperCase().trim()+'" hidden><input type="text" name="generateEmailForm-7_remoteHands-equipList_'+count_serverList+'-location" value="'+$(' #generateEmailForm-7_remoteHands-4c ').val().toUpperCase().trim()+'" hidden>');
                                	
					$(' #generateEmailForm-7_remoteHands-equipList_'+count_serverList+'-remove').on('click',function(){
                                	        console.log('remove serverList item');
                                	        serverListItemContainer = $(this).parent().parent();
                                	        $(this).parent().remove();
                                	        console.log('number of serverList items: '+serverListItemContainer.children().length);
                                	        if(serverListItemContainer.children().length==1){
                                	                console.log('no serverList items, show msg');
                                	                $(' #generateEmailForm-7_remoteHands-equipList-msg ').show();
                                	        }
                                	});

                                	$(' #generateEmailForm-7_remoteHands-equipList_'+count_serverList+'-edit').on('click',function(){
                                	        console.log('edit serverList item');
                                	        console.log(this);
                                	        editClicked = (this.id).replace("edit","");
                                	        console.log(editClicked+'svctag');
                                	        $(' #generateEmailForm-7_remoteHands-4a ').val( $(' input[name="'+editClicked+'model"] ').val() );
                                	        $(' #generateEmailForm-7_remoteHands-4b ').val( $(' input[name="'+editClicked+'svctag"] ').val() );
                                	        $(' #generateEmailForm-7_remoteHands-4c ').val( $(' input[name="'+editClicked+'location"] ').val() );
                                	        serverListItemContainer = $(this).parent().parent();
                                	        $(this).parent().remove();
                                	        console.log('number of serverList items: '+serverListItemContainer.children().length);
                                	        if(serverListItemContainer.children().length==1){
                                	                console.log('no serverList items, show msg');
                                	                $(' #generateEmailForm-7_remoteHands-equipList-msg ').show();
                                	        }
                                	});
                                	$(' #generateEmailForm-7_remoteHands-equipList-count ').val(count_serverList);
                                	$(' #generateEmailForm-7_remoteHands-equipInfo input ').val('');
                                	count_serverList++;
				} else {
					alert('Equipment Info fields cannot be blank.');
				}
                        });

			$(' #generateEmailForm-8_statusReport-tz ').val( tz.name() );

			var count_hotItems=1;
			var count_deployments=1;
			var count_vendorMaint=1;

			function addToStatusReport(fieldAdd){
				console.log('addToStatusReport called');
				console.log(fieldAdd);

				if(fieldAdd=='generateEmailForm-8_statusReport-A'){
					if(count_hotItems>0){
						$(' #generateEmailForm-8_statusReport-hotItems-msg ').hide();
					}
					$(' #generateEmailForm-8_statusReport-hotItems ').append('<div id="hotItem_'+count_hotItems+'" style="margin-bottom:5px;"><button class="btn btn-danger btn-xs remove-report-item" id="hotItem_'+count_hotItems+'-remove" type="button">Remove</button>&nbsp;<button class="btn btn-default btn-xs" id="generateEmailForm-8_statusReport-hotItems'+count_hotItems+'-edit" type="button">Edit</button> '+$(' #generateEmailForm-8_statusReport-A-1_input ').val()+'<input type="text" name="generateEmailForm-8_statusReport-hotItems'+count_hotItems+'-desc" value="'+$(' #generateEmailForm-8_statusReport-A-1_input ').val()+'" hidden><input type="text" name="generateEmailForm-8_statusReport-hotItems'+count_hotItems+'-time" value="'+$(' #generateEmailForm-8_statusReport-A-5_input ').val()+'" hidden><input type="text" name="generateEmailForm-8_statusReport-hotItems'+count_hotItems+'-impact" value="'+$(' #generateEmailForm-8_statusReport-A-2_input ').val()+'" hidden><input type="text" name="generateEmailForm-8_statusReport-hotItems'+count_hotItems+'-steps" value="'+$(' #generateEmailForm-8_statusReport-A-3_input ').val()+'" hidden><input type="text" name="generateEmailForm-8_statusReport-hotItems'+count_hotItems+'-status" value="'+$(' #generateEmailForm-8_statusReport-A-4_input ').val()+'" hidden></div>');
					//popoverEmailContent.replace(/"/g, '&quot;')
				
					$(' #hotItem_'+count_hotItems+'-remove').on('click',function(){
						console.log('remove report item');
						//console.log($(this).parent().parent());
						reportItemContainer = $(this).parent().parent();
						$(this).parent().remove();
						console.log('number of hot items: '+reportItemContainer.children().length);
						if(reportItemContainer.children().length==1){
							console.log('no hot items, show msg');
							$(' #generateEmailForm-8_statusReport-hotItems-msg ').show();
						}
					});

					$(' #generateEmailForm-8_statusReport-hotItems'+count_hotItems+'-edit').on('click',function(){
						console.log('edit report item');
						console.log(this);
						editClicked = (this.id).replace("edit","");
						//console.log(editClicked+'desc');
						$(' #generateEmailForm-8_statusReport-A-1_input ').val( $(' input[name="'+editClicked+'desc"] ').val() );
						$(' #generateEmailForm-8_statusReport-A-2_input ').val( $(' input[name="'+editClicked+'impact"] ').val() );
						$(' #generateEmailForm-8_statusReport-A-3_input ').val( $(' input[name="'+editClicked+'steps"] ').val() );
						$(' #generateEmailForm-8_statusReport-A-4_input ').val( $(' input[name="'+editClicked+'status"] ').val() );
						$(' #generateEmailForm-8_statusReport-A-5_input ').val( $(' input[name="'+editClicked+'time"] ').val() );
						reportItemContainer = $(this).parent().parent();
						$(this).parent().remove();
						console.log('number of hot items: '+reportItemContainer.children().length);
						if(reportItemContainer.children().length==1){
							console.log('no hot items, show msg');
							$(' #generateEmailForm-8_statusReport-hotItems-msg ').show();
						}
					});
					$(' #generateEmailForm-8_statusReport-hotItems-count ').val(count_hotItems);
					$(' #generateEmailForm-8_statusReport-A textarea,#generateEmailForm-8_statusReport-A input ').val('');
					count_hotItems++;
				}

				if(fieldAdd=='generateEmailForm-8_statusReport-B'){
					if(count_deployments>0){
						$(' #generateEmailForm-8_statusReport-deployments-msg ').hide();
					}
					$(' #generateEmailForm-8_statusReport-deployments ').append('<div id="deployment_'+count_deployments+'" style="margin-bottom:5px;"><button class="btn btn-danger btn-xs remove-report-item" id="generateEmailForm-8_statusReport-deployments_'+count_deployments+'-remove" type="button">Remove</button>&nbsp;<button class="btn btn-default btn-xs" id="generateEmailForm-8_statusReport-deployments'+count_deployments+'-edit" type="button">Edit</button> '+$(' #generateEmailForm-8_statusReport-B-1_input ').val()+'<input type="text" name="generateEmailForm-8_statusReport-deployments'+count_deployments+'-ticket" value="'+$(' #generateEmailForm-8_statusReport-B-1_input ').val()+'" hidden><input type="text" name="generateEmailForm-8_statusReport-deployments'+count_deployments+'-summary" value="'+$(' #generateEmailForm-8_statusReport-B-2_input ').val()+'" hidden><input type="text" name="generateEmailForm-8_statusReport-deployments'+count_deployments+'-startTime" value="'+$(' #generateEmailForm-8_statusReport-B-3_input ').val()+'" hidden><input type="text" name="generateEmailForm-8_statusReport-deployments'+count_deployments+'-endTime" value="'+$(' #generateEmailForm-8_statusReport-B-4_input ').val()+'" hidden><input type="text" name="generateEmailForm-8_statusReport-deployments'+count_deployments+'-status" value="'+$(' #generateEmailForm-8_statusReport-B-5_input ').val()+'" hidden></div>');
				
					$(' #generateEmailForm-8_statusReport-deployments_'+count_deployments+'-remove').on('click',function(){
						console.log('remove report item');
						//console.log($(this).parent().parent());
						reportItemContainer = $(this).parent().parent();
						$(this).parent().remove();
						console.log('number of deployments: '+reportItemContainer.children().length);
						if(reportItemContainer.children().length==1){
							console.log('no deployments, show msg');
							$(' #generateEmailForm-8_statusReport-deployments-msg ').show();
						}
					});

					$(' #generateEmailForm-8_statusReport-deployments'+count_deployments+'-edit').on('click',function(){
						console.log('edit report item');
						console.log(this);
						editClicked = (this.id).replace("edit","");
						//console.log(editClicked+'desc');
						$(' #generateEmailForm-8_statusReport-B-1_input ').val( $(' input[name="'+editClicked+'ticket"] ').val() );
						$(' #generateEmailForm-8_statusReport-B-2_input ').val( $(' input[name="'+editClicked+'summary"] ').val() );
						$(' #generateEmailForm-8_statusReport-B-3_input ').val( $(' input[name="'+editClicked+'startTime"] ').val() );
						$(' #generateEmailForm-8_statusReport-B-4_input ').val( $(' input[name="'+editClicked+'endTime"] ').val() );
						$(' #generateEmailForm-8_statusReport-B-5_input ').val( $(' input[name="'+editClicked+'status"] ').val() );
						reportItemContainer = $(this).parent().parent();
						$(this).parent().remove();
						console.log('number of deployments: '+reportItemContainer.children().length);
						if(reportItemContainer.children().length==1){
							console.log('no deployments, show msg');
							$(' #generateEmailForm-8_statusReport-deployments-msg ').show();
						}
					});
					$(' #generateEmailForm-8_statusReport-deployments-count ').val(count_deployments);
					$(' #generateEmailForm-8_statusReport-B input ').val('');
					count_deployments++;
				}
				
				return false;
			}

			function addToTurnover(fieldAdd){
				console.log('addToTurnover called');
				console.log(fieldAdd);
				
				//$(' #generateEmailForm-8_statusReport-hotItems-count ').val(count_hotItemsTurnover);
				//$(' #generateEmailForm-8_statusReport-A textarea,#generateEmailForm-8_statusReport-A input ').val('');
				//count_hotItemsTurnover++;

				return false;
			}
				
			//if(fieldAdd=='generateEmailForm-8_statusReport-C'){
				//var vendorData = getJSONData('vendorMaint', $(' #generateEmailForm-8_statusReport-C-1_input ').val() );
				var vendorData = getJSONData('maint-24h');
				//console.log(vendorData);
				if(vendorData.length>0){
					for(jj=0;jj<vendorData.length;jj++){
						if(count_vendorMaint>0){
							$(' #generateEmailForm-8_statusReport-vendorMaint-msg ').hide();
						}
						$(' #generateEmailForm-8_statusReport-vendorMaint ').append('<div id="generateEmailForm-8_statusReport-vendorMaint_'+count_vendorMaint+'" style="margin-bottom:5px;"><button class="btn btn-danger btn-xs remove-report-item" id="generateEmailForm-8_statusReport-vendorMaint_'+count_vendorMaint+'-remove" type="button">Remove</button>  '+vendorData[jj].Provider+' | '+vendorData[jj].Provider_Ticket_Num+'<input type="text" name="generateEmailForm-8_statusReport-vendorMaint'+count_vendorMaint+'-vendor" value="'+vendorData[jj].Provider+'" hidden><input type="text" name="generateEmailForm-8_statusReport-vendorMaint'+count_vendorMaint+'-ticket" value="'+vendorData[jj].Provider_Ticket_Num+'" hidden><input type="text" name="generateEmailForm-8_statusReport-vendorMaint'+count_vendorMaint+'-sites" value="'+vendorData[jj].Affected_Sites+'" hidden><input type="text" name="generateEmailForm-8_statusReport-vendorMaint'+count_vendorMaint+'-type" value="'+vendorData[jj].Work_Type+'" hidden><input type="text" name="generateEmailForm-8_statusReport-vendorMaint'+count_vendorMaint+'-desc" value="'+vendorData[jj].Work_Description+'" hidden><input type="text" name="generateEmailForm-8_statusReport-vendorMaint'+count_vendorMaint+'-startTime" value="'+moment.utc(vendorData[jj].Work_Start).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss')+'" hidden><input type="text" name="generateEmailForm-8_statusReport-vendorMaint'+count_vendorMaint+'-endTime" value="'+moment.utc(vendorData[jj].Work_End).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss')+'" hidden></div>');
						$(' #generateEmailForm-8_statusReport-vendorMaint_'+count_vendorMaint+'-remove').on('click',function(){
							console.log('remove report item');
							var confirmRemove = confirm('Are you sure you want to remove?');
							if(confirmRemove){
								//console.log($(this).parent().parent());
								reportItemContainer = $(this).parent().parent();
								$(this).parent().remove();
								console.log('number of vendor maint: '+reportItemContainer.children().length);
								if(reportItemContainer.children().length==1){
									console.log('no vendor maint, show msg');
									$(' #generateEmailForm-8_statusReport-vendorMaint-msg ').show();
								}
							}
						});

						$(' #generateEmailForm-8_statusReport-vendorMaint_'+count_vendorMaint+'-edit').on('click',function(){
							console.log('edit report item');
							console.log(this);
						});
						$(' #generateEmailForm-8_statusReport-vendorMaint-count ').val(count_vendorMaint);
						//console.log('hotItem count: '+$(' #generateEmailForm-8_statusReport-hotItems-count ').val() );
						//console.log('deploy count: '+$(' #generateEmailForm-8_statusReport-deployments-count ').val() );
						//console.log('vendorMaint count: '+$(' #generateEmailForm-8_statusReport-vendorMaint-count ').val() );
						count_vendorMaint++;
						//$(' #generateEmailForm-8_statusReport-C-1_input ').val('');
					}
				}
			//}

			var count_turnover_jiraIssues=1;
			var turnover_jiraIssues = getJSONData('activity-jira');
			//console.log(vendorData);

			if(turnover_jiraIssues.length>0){
				for(jj=0;jj<turnover_jiraIssues.length;jj++){
					if(count_turnover_jiraIssues>0){
						$(' #generateEmailForm-10_turnover-jiraIssues-msg ').hide();
					}
					$(' #generateEmailForm-10_turnover-jiraIssues ').append('<form id="generateEmailForm-10_turnover-jiraIssues_'+count_turnover_jiraIssues+'" style="margin-bottom:5px;" onsubmit="return addToTurnover(\'generateEmailForm-10_turnover-jiraIssues_'+count_turnover_jiraIssues+'\')"><button class="btn btn-success btn-xs remove-report-item" id="generateEmailForm-10_turnover-jiraIssues'+count_turnover_jiraIssues+'-add" type="submit">Add</button>  '+turnover_jiraIssues[jj].issueNum+' | '+turnover_jiraIssues[jj].issueType+' | '+turnover_jiraIssues[jj].issueSummary+'<input type="text" name="generateEmailForm-10_turnover-jiraIssues'+count_turnover_jiraIssues+'-issueNum" value="'+turnover_jiraIssues[jj].issueNum+'" hidden><input type="text" name="generateEmailForm-10_turnover-jiraIssues'+count_turnover_jiraIssues+'-issueType" value="'+turnover_jiraIssues[jj].issueType+'" hidden><input type="text" name="generateEmailForm-10_turnover-jiraIssues'+count_turnover_jiraIssues+'-issueSummary" value="'+turnover_jiraIssues[jj].issueSummary+'" hidden><br><input class="form-control" style="width:75%;margin-top:5px;" id="generateEmailForm-10_turnover-jiraIssues'+count_turnover_jiraIssues+'-nextSteps_input" name="generateEmailForm-10_turnover-jiraIssues'+count_turnover_jiraIssues+'-nextSteps" placeholder="Next Steps..." required/></form>');
					$(' #generateEmailForm-10_turnover-jiraIssues'+count_turnover_jiraIssues+'-add').on('click',function(){
						console.log('add turnover-jiraIssues item');
						console.log( this.id );
						addJiraIssueTarget = (this.id).replace("-add","");
						addJiraIssueTargetID = ( addJiraIssueTarget ).replace("generateEmailForm-10_turnover-jiraIssues","");
						console.log( addJiraIssueTarget );
						console.log( addJiraIssueTargetID );
						$(' #'+addJiraIssueTarget+'-nextSteps_input ').val( $(' #'+addJiraIssueTarget+'-nextSteps_input ').val().trim() );
						if( $(' #'+addJiraIssueTarget+'-nextSteps_input ').val().trim() !== '' ){
							//$(' #'+addJiraIssueTarget+'-nextSteps_input ').val('testing123');
							//$(' #generateEmailForm-10_turnover-jiraIssuesAdded ').append('<div id="generateEmailForm-10_turnover-jiraIssues_'+count_turnover_jiraIssues+'" style="margin-bottom:5px;"><button class="btn btn-danger btn-xs remove-report-item" id="generateEmailForm-10_turnover-jiraIssuesAdded'+count_turnover_jiraIssues+'-remove" type="button">Remove</button><button class="btn btn-default btn-xs remove-report-item" id="generateEmailForm-10_turnover-jiraIssuesAdded'+count_turnover_jiraIssues+'-edit" type="button">Remove</button>  '+turnover_jiraIssues[jj].issueNum+' | '+turnover_jiraIssues[jj].issueType+' | '+turnover_jiraIssues[jj].issueSummary+'<input type="text" name="generateEmailForm-10_turnover-jiraIssues'+count_turnover_jiraIssues+'-issueNum" value="'+turnover_jiraIssues[jj].issueNum+'" hidden><hr>');
							$(' #generateEmailForm-10_turnover-jiraIssuesAdded ').append('<div id="generateEmailForm-10_turnover-jiraIssuesAdded'+addJiraIssueTargetID+'" style="margin-bottom:5px;"><button class="btn btn-danger btn-xs remove-report-item" id="generateEmailForm-10_turnover-jiraIssuesAdded'+addJiraIssueTargetID+'-remove" type="button">Remove</button>&nbsp;<button class="btn btn-default btn-xs turnover-jiraIssues-btn" id="generateEmailForm-10_turnover-jiraIssuesAdded'+addJiraIssueTargetID+'-edit" type="button">Edit</button>  '+$(' input[name="'+addJiraIssueTarget+'-issueNum"] ').val()+' | '+$(' input[name="'+addJiraIssueTarget+'-issueType"] ').val()+' | '+$(' input[name="'+addJiraIssueTarget+'-issueSummary"] ').val()+'<input type="text" name="generateEmailForm-10_turnover-jiraIssuesAdded'+addJiraIssueTargetID+'-issueNum" value="'+$(' input[name="'+addJiraIssueTarget+'-issueNum"] ').val()+'" hidden><input type="text" name="generateEmailForm-10_turnover-jiraIssuesAdded'+addJiraIssueTargetID+'-issueType" value="'+$(' input[name="'+addJiraIssueTarget+'-issueType"] ').val()+'" hidden><input type="text" name="generateEmailForm-10_turnover-jiraIssuesAdded'+addJiraIssueTargetID+'-issueSummary" value="'+$(' input[name="'+addJiraIssueTarget+'-issueSummary"] ').val()+'" hidden><br><input class="form-control" style="width:75%;margin-top:5px;" id="generateEmailForm-10_turnover-jiraIssuesAdded'+addJiraIssueTargetID+'-nextSteps_input" name="generateEmailForm-10_turnover-jiraIssuesAdded'+addJiraIssueTargetID+'-nextSteps" value="'+$('#generateEmailForm-10_turnover-jiraIssues'+addJiraIssueTargetID+'-nextSteps_input').val()+'" placeholder="Next Steps..." required disabled/></div>');
							$(' #generateEmailForm-10_turnover-jiraIssuesAdded-msg ').hide();
							reportItemContainer = $(this).parent().parent();
							//$(this).parent().remove();
							$(this).parent().hide(); //hide jiraIssue on left for adding back to list
							console.log('number of turnover-jiraIssues: '+reportItemContainer.children(':visible').length);
							if(reportItemContainer.children(':visible').length==0){
								console.log('no turnover-jiraIssues, show msg');
								$(' #generateEmailForm-10_turnover-jiraIssues-msg ').show();
							}
						}else{
							//alert('fail!');
						}
						
						$(' #generateEmailForm-10_turnover-jiraIssuesAdded'+addJiraIssueTargetID+'-remove').on('click',function(){
							console.log('remove jira issue from added list');
							//console.log($(this).parent().parent());
							reportItemContainer = $(this).parent().parent();
							removeJiraIssueTarget = (this.id).replace("-remove","");
							removeJiraIssueTargetID = ( removeJiraIssueTarget ).replace("generateEmailForm-10_turnover-jiraIssuesAdded","");
							console.log( removeJiraIssueTarget );
							console.log( removeJiraIssueTargetID );
							$(' #generateEmailForm-10_turnover-jiraIssues'+removeJiraIssueTargetID+'-nextSteps_input' ).val('');
							$(' #generateEmailForm-10_turnover-jiraIssues_'+removeJiraIssueTargetID ).show();
							$(this).parent().remove();
							console.log('number of turnover-jiraIssuesAdded: '+reportItemContainer.children().length);
							if(reportItemContainer.children().length==1){
								console.log('no added jira issues, show msg');
								$(' #generateEmailForm-10_turnover-jiraIssuesAdded-msg ').show();
							}
							$(' #generateEmailForm-10_turnover-jiraIssues-msg ').hide();
							reportItemContainer = $('#generateEmailForm-10_turnover-jiraIssues');
							console.log('number of turnover-jiraIssues: '+reportItemContainer.children(':visible').length);
							/*if(reportItemContainer.children(':visible').length==0){
								console.log('no turnover-jiraIssues, show msg');
								$(' #generateEmailForm-10_turnover-jiraIssues-msg ').show();
							}*/
						});

						$(' #generateEmailForm-10_turnover-jiraIssuesAdded'+addJiraIssueTargetID+'-edit').on('click',function(){
							console.log('edit jira issue on edit list');
							console.log(this);
							editClicked = $(this);
							console.log(editClicked);
							editJiraIssueTarget = (this.id).replace("-edit","");
							editJiraIssueTargetID = ( editJiraIssueTarget ).replace("generateEmailForm-10_turnover-jiraIssuesAdded","");
							if( $(' #generateEmailForm-10_turnover-jiraIssuesAdded'+editJiraIssueTargetID+'-nextSteps_input ').prop('disabled') == true){
								$(' #generateEmailForm-10_turnover-jiraIssuesAdded'+editJiraIssueTargetID+'-nextSteps_input ').prop('disabled',false);
								$(this).text('Update');
							}else{
								$('#generateEmailForm-10_turnover-jiraIssuesAdded'+editJiraIssueTargetID+'-nextSteps_input').val( $('#generateEmailForm-10_turnover-jiraIssuesAdded'+editJiraIssueTargetID+'-nextSteps_input').val().trim() );
								if( $('#generateEmailForm-10_turnover-jiraIssuesAdded'+editJiraIssueTargetID+'-nextSteps_input').val() !== '' ){
									$(' #generateEmailForm-10_turnover-jiraIssuesAdded'+editJiraIssueTargetID+'-nextSteps_input ').prop('disabled',true);
									$(this).text('Edit');
								}else{
									$('#generateEmailForm-10_turnover-jiraIssuesAdded'+editJiraIssueTargetID+'-nextSteps_input').focus();
								}
							}
							//$(' #generateEmailForm-8_statusReport-A-2_input ').val( $(' input[name="'+editClicked+'impact"] ').val() );
							//$(' #generateEmailForm-8_statusReport-A-3_input ').val( $(' input[name="'+editClicked+'steps"] ').val() );
							//$(' #generateEmailForm-8_statusReport-A-4_input ').val( $(' input[name="'+editClicked+'status"] ').val() );
							//$(' #generateEmailForm-8_statusReport-A-5_input ').val( $(' input[name="'+editClicked+'time"] ').val() );
							//reportItemContainer = $(this).parent().parent();
							//$(this).parent().remove();
							//console.log('number of hot items: '+reportItemContainer.children().length);
							//if(reportItemContainer.children().length==1){
							//	console.log('no hot items, show msg');
							//	$(' #generateEmailForm-8_statusReport-hotItems-msg ').show();
							//}
						});
					});

					$(' #generateEmailForm-10_turnover-jiraIssuesAdded-count ').val(count_turnover_jiraIssues);
					//console.log('hotItem count: '+$(' #generateEmailForm-8_statusReport-hotItems-count ').val() );
					//console.log('deploy count: '+$(' #generateEmailForm-8_statusReport-deployments-count ').val() );
					//console.log('vendorMaint count: '+$(' #generateEmailForm-8_statusReport-vendorMaint-count ').val() );
					count_turnover_jiraIssues++;
					//$(' #generateEmailForm-8_statusReport-C-1_input ').val('');
				}
			}
                </script>

		<?PHP addFooter(); ?>

		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-sm">
    				<div class="modal-content">
      					<div class="modal-header">
        					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        					<h4 class="modal-title" id="myModalLabel">Success</h4>
      					</div>
      					<div class="modal-body">Email successfully sent.</div>
					<div class="modal-footer">
        					<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
      					</div>
    				</div>
			</div>
		</div>
	</body>
</html>
