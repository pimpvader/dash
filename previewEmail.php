<?PHP
	$path=$_SERVER['DOCUMENT_ROOT'];
	require_once $path.'/php/dbconn.php';
	include $path.'/php/common.php';

	function buildPreviewForm(){
		$previewEmailForm_Signature = "<br><br>Please contact the Conversant NOC with any questions.<br><br>Thank you,<br><br>".$_SESSION['displayName']."<br>Conversant Network Operations Center<br><a href='mailto:noc@conversantmedia.com'>noc@conversantmedia.com</a><br>800.566.3316";
		$previewEmailForm_ConfBridge = "<br><br>A conference bridge has been opened for further discussion of this issue.  You are encouraged to join to receive the latest status updates:<br><br><b>Call-in toll-free number (US/Canada):</b> 1-877-668-4493<br><b>Primary call-in toll number (US/Canada):</b> 1-650-479-3208<br><b>Secondary call-in toll number (US/Canada):</b> 1-650-479-3208 (This number should be used only if the primary number does not work)<br><b>Attendee access code:</b> 23327793";
	
		if($_REQUEST['blank']=='true'){
			generateEmailPreview_blank($previewEmailForm_Signature);
		}
		if($_POST['generateEmailForm_ConfBridge']=='true'){
			$previewEmailForm_Signature = $previewEmailForm_ConfBridge.$previewEmailForm_Signature;
		}

		if($_POST['generateEmail_Form']=='generateEmailPreview-1_SEV1'){
			generateEmailPreview1_SEV1($previewEmailForm_Signature);
		}
		if($_POST['generateEmail_Form']=='generateEmailPreview-2_SEV2'){
			generateEmailPreview2_SEV2($previewEmailForm_Signature);
		}
		if($_POST['generateEmail_Form']=='generateEmailPreview-3_SEV3'){
			generateEmailPreview3_SEV3($previewEmailForm_Signature);
		}
		if($_POST['generateEmail_Form']=='generateEmailPreview-4_appError'){
			generateEmailPreview4_appError($previewEmailForm_Signature,$_POST['generateEmailForm-4_appError-6']);
		}
		if($_POST['generateEmail_Form']=='generateEmailPreview-4_appRestart'){
			generateEmailPreview4_appRestart($previewEmailForm_Signature,$_POST['generateEmailForm-4_appRestart-6']);
		}
		if($_POST['generateEmail_Form']=='generateEmailPreview-5_downtime'){
			generateEmailPreview5_downtime($previewEmailForm_Signature);
		}
		if($_POST['generateEmail_Form']=='generateEmailPreview-5_serverRestart'){
			generateEmailPreview5_serverRestart($previewEmailForm_Signature);
		}
		if($_POST['generateEmail_Form']=='generateEmailPreview-6_driveCapacity'){
			generateEmailPreview6_driveCapacity($previewEmailForm_Signature);
		}
		if($_POST['generateEmail_Form']=='generateEmailPreview-6_hotSwap'){
			generateEmailPreview6_hotSwap($previewEmailForm_Signature);
		}
		if($_POST['generateEmail_Form']=='generateEmailPreview-7_tech'){
			generateEmailPreview7_tech($previewEmailForm_Signature);
		}
		if($_POST['generateEmail_Form']=='generateEmailPreview-7_package'){
			generateEmailPreview7_package($previewEmailForm_Signature);
		}
		if($_POST['generateEmail_Form']=='generateEmailPreview-7_remoteHands'){
			generateEmailPreview7_remoteHands($previewEmailForm_Signature);
		}
		if($_POST['generateEmail_Form']=='generateEmailPreview-8_statusReport'){
			generateEmailPreview8_statusReport($previewEmailForm_Signature);
		}
	}

	function generateEmailPreview1_SEV1($previewEmailForm_Signature){
                $previewEmailForm_From = 'noc@conversantmedia.com';
                $previewEmailForm_To = 'Engineering Updates, Software Support, Network Engineering, Systems Engineering';
                $previewEmailForm_CC = '';
                $previewEmailForm_BCC = 'noc@conversantmedia.com';
		$previewEmailForm_Subject = 'SEV 1 Outage | '.$_POST['generateEmailForm-1_SEV1-1'].' | '.$_POST['generateEmailForm-1_SEV1-2'];
		$previewEmailForm_Body = "Hello,<br><br>The NOC has observed and is currently investigating the following issue:<br><br><b><i>".$_POST['generateEmailForm-1_SEV1-1']." in our ".$_POST['generateEmailForm-1_SEV1-2']."</i></b><br><br>We will be opening a NOC Incident ticket in Jira to track this issue and will provide the ticket number in a subsequent email.  We ask that you add yourself as a watcher to be notified of all progress and updates surrounding this incident.<br><br><u>NOC will send notification once services affected by this outage have been restored.</u>".$previewEmailForm_Signature;

                generatePreviewForm($previewEmailForm_From,$previewEmailForm_To,$previewEmailForm_CC,$previewEmailForm_BCC,$previewEmailForm_Subject,$previewEmailForm_Body);
        }

	function generateEmailPreview2_SEV2($previewEmailForm_Signature){
                $previewEmailForm_From = 'noc@conversantmedia.com';
                $previewEmailForm_To = 'Engineering Updates, Software Support, Network Engineering, Systems Engineering';
                $previewEmailForm_CC = '';
                $previewEmailForm_BCC = 'noc@conversantmedia.com';
		$previewEmailForm_Subject = 'SEV 2 Outage | '.$_POST['generateEmailForm-2_SEV2-1'].' | '.$_POST['generateEmailForm-2_SEV2-2'];
		$previewEmailForm_Body = "Hello,<br><br>The NOC has observed and is currently investigating the following issue:<br><br><b><i>".$_POST['generateEmailForm-2_SEV2-1']." in our ".$_POST['generateEmailForm-2_SEV2-2']."</i></b><br><br>We will be opening a NOC Incident ticket in Jira to track this issue and will provide the ticket number in a subsequent email.  We ask that you add yourself as a watcher to be notified of all progress and updates surrounding this incident.<br><br><u>NOC will send notification once services affected by this outage have been restored.</u>".$previewEmailForm_Signature;

                generatePreviewForm($previewEmailForm_From,$previewEmailForm_To,$previewEmailForm_CC,$previewEmailForm_BCC,$previewEmailForm_Subject,$previewEmailForm_Body);
        }

	function generateEmailPreview3_SEV3($previewEmailForm_Signature){
                $previewEmailForm_From = 'noc@conversantmedia.com';
                $previewEmailForm_To = 'Engineering Updates, Software Support, Network Engineering, Systems Engineering';
                $previewEmailForm_CC = '';
                $previewEmailForm_BCC = 'noc@conversantmedia.com';
		$previewEmailForm_Subject = 'SEV 3 Outage | '.$_POST['generateEmailForm-3_SEV3-1'].' | '.$_POST['generateEmailForm-3_SEV3-2'];
		if( $_POST['generateEmailForm-3_SEV3-2']=='' ){$previewEmailForm_Subject.=$_POST['generateEmailForm-3_SEV3-4'];}
		$previewEmailForm_Body = "Hello,<br><br>The NOC is currently investigating the following issue:<br><br><b><i>".$_POST['generateEmailForm-3_SEV3-1'];
		if( $_POST['generateEmailForm-3_SEV3-1']=='10G WAN Service Interruption' ){
			$previewEmailForm_Body.= " | ".$_POST['generateEmailForm-3_SEV3-4'];
		}else{
			$previewEmailForm_Body.= " in our ".$_POST['generateEmailForm-3_SEV3-2'];
		}
		if( ($_POST['generateEmailForm-3_SEV3-1']=='Single Core Switch Failure' || $_POST['generateEmailForm-3_SEV3-1']=='10G WAN Service Interruption' || $_POST['generateEmailForm-3_SEV3-1']=='Data Center Network Issues' || $_POST['generateEmailForm-3_SEV3-1']=='CHI Single Switch Failure') ){
			$previewEmailForm_To.= ", VCC Network";
		}
		$previewEmailForm_Body.="</i></b><br><br>We have opened the following Jira ticket to track this issue:<br><br><a href='http://jira.cnvrmedia.net/browse/".strtoupper($_POST['generateEmailForm-3_SEV3-3'])."'>http://jira.cnvrmedia.net/browse/".strtoupper($_POST['generateEmailForm-3_SEV3-3'])."</a><br><br>The NOC will be following up with the necessary vendor(s) to investigate.  The next email communication will be upon restoration of service.  We ask that you add yourself as a watcher to the Jira ticket above to receive the latest updates.  If you are unable to add yourself as a watcher, at this time, please contact us and we will do so for you.".$previewEmailForm_Signature;

                generatePreviewForm($previewEmailForm_From,$previewEmailForm_To,$previewEmailForm_CC,$previewEmailForm_BCC,$previewEmailForm_Subject,$previewEmailForm_Body);
        }
	
	function generateEmailPreview4_appError($previewEmailForm_Signature,$alert_ID_list){
                $previewEmailForm_From = 'noc@conversantmedia.com';
                $previewEmailForm_To = 'noc@conversantmedia.com';
                $previewEmailForm_CC = $_POST['generateEmailForm-4_appError-1'];
                $previewEmailForm_BCC = '';
		$previewEmailForm_Subject = $_POST['generateEmailForm-4_appError-2']."s with ".strtoupper($_POST['generateEmailForm-4_appError-3'])." ABS ".$_POST['generateEmailForm-4_appError-4']." alerts";
		$previewEmailForm_Body='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" /><style>.oddrow{background-color:#eee;}.evenrow{background-color:#fff;}.table-container{border:1px solid #aaa;border-radius:4px;background-color:#fff;width:100%;margin-left:1px;margin-right:1px;}.table-issue{width:100%;padding:10px;background-color:#fff;}.table-issue-head{width:25%;text-decoration:bold;}.table-issue-body{width:75%;}.border-bottom{border-bottom:1px solid #aaa;}.item-header{font-size:20px;color:#fff;text-align:center;background-color:#101820;border-radius:4px;margin-left:1px;margin-right:1px;}.server-head{background-color:#e1523d !important;}.deployments-issue-head{background-color:#00af66 !important;}.vendorMaint-issue-head{background-color:#34657f !important;}.header-img{border:0px;margin:0px;padding:0px;}</style></head><body>';
		$previewEmailForm_Body.='<table style="width:100%;margin:0px;padding:0px;"><tr><td style="background-color:#101820;border-bottom:3px solid #ffb500;padding:5px;width:100%;text-align:right;"><img style="border:0px;margin:0px;padding:0px;" src="http://www.conversantmedia.com/sites/all/themes/my_theme/images/conversant_logo.png" /></td></tr></table>';
		$previewEmailForm_Body.="<table style='padding-left:10px;padding-right:10px;'><tr><td>Hello,<br><br>The NOC has been alerted that the following ".$_POST['generateEmailForm-4_appError-2']."s are having ".strtoupper($_POST['generateEmailForm-4_appError-3'])." ABS ".$_POST['generateEmailForm-4_appError-4']." issues:</td></tr></table><table style='width:100%;padding-left:15px;padding-right:10px;'><tr><td>".$_POST['generateEmailForm-4_appError-5'];
		$previewEmailForm_Body.="</td></tr></table><table style='width:100%;padding-left:10px;padding-right:10px;'><tr><td>".$previewEmailForm_Signature."</td></tr></table></body></html>";
                
		generatePreviewForm($previewEmailForm_From,$previewEmailForm_To,$previewEmailForm_CC,$previewEmailForm_BCC,$previewEmailForm_Subject,$previewEmailForm_Body,$alert_ID_list);
        }

	function generateEmailPreview4_appRestart($previewEmailForm_Signature,$notificationType){
                $previewEmailForm_From = 'noc@conversantmedia.com';
                $previewEmailForm_To = $_POST['generateEmailForm-4_appRestart-1'];
                $previewEmailForm_CC = '';
                $previewEmailForm_BCC = 'noc@conversantmedia.com';

		switch($notificationType){
			case 'CONFIRM':
				$previewEmailForm_Subject = $_POST['generateEmailForm-4_appRestart-3']." | ".$_POST['generateEmailForm-4_appRestart-2']." Alert";
				$previewEmailForm_Body = "Hello,<br><br>The NOC would like to confirm if ".$_POST['generateEmailForm-4_appRestart-3']." is being worked on.  If not, we will be creating a PROD ticket and restarting the ".strtoupper($_POST['generateEmailForm-4_appRestart-2'])." app on this box.  The follow error has been observed:<br><br>".$_POST['generateEmailForm-4_appRestart-4'].$previewEmailForm_Signature;
				break;
			case 'RESTART':
				$previewEmailForm_Subject = $_POST['generateEmailForm-4_appRestart-3']." | ".$_POST['generateEmailForm-4_appRestart-2']." Restart | ".strtoupper($_POST['generateEmailForm-4_appRestart-5']);
				$previewEmailForm_Body = "Hello,<br><br>NOC is about to restart the ".strtoupper($_POST['generateEmailForm-4_appRestart-2'])." app on ".$_POST['generateEmailForm-4_appRestart-3'].".  We will notify you when the restart is complete.  The following error has been observed:<br><br>".$_POST['generateEmailForm-4_appRestart-4']."<br><br>Please reference the following ticket for this issue:<br><br><a href='http://jira.cnvrmedia.net/browse/".strtoupper($_POST['generateEmailForm-4_appRestart-5'])."'>http://jira.cnvrmedia.net/browse/".strtoupper($_POST['generateEmailForm-4_appRestart-5'])."</a>".$previewEmailForm_Signature;
				break;
			case 'COMPLETE':
				$previewEmailForm_Subject = $_POST['generateEmailForm-4_appRestart-3']." | ".$_POST['generateEmailForm-4_appRestart-2']." Restart Complete | ".strtoupper($_POST['generateEmailForm-4_appRestart-5']);
				$previewEmailForm_Body = "Hello,<br><br>The NOC has completed the restart of the ".strtoupper($_POST['generateEmailForm-4_appRestart-2'])." app on ".$_POST['generateEmailForm-4_appRestart-3'].".  The jstack and jmap files can be found attached to the open PROD ticket for this issue:<br><br><a href='http://jira.cnvrmedia.net/browse/".strtoupper($_POST['generateEmailForm-4_appRestart-5'])."'>http://jira.cnvrmedia.net/browse/".strtoupper($_POST['generateEmailForm-4_appRestart-5'])."</a>".$previewEmailForm_Signature;
				break;
		}

                generatePreviewForm($previewEmailForm_From,$previewEmailForm_To,$previewEmailForm_CC,$previewEmailForm_BCC,$previewEmailForm_Subject,$previewEmailForm_Body);
        }
	
	function generateEmailPreview5_downtime($previewEmailForm_Signature){
                $previewEmailForm_From = 'noc@conversantmedia.com';
                $previewEmailForm_To = $_POST['generateEmailForm-5_downtime-1'];
                $previewEmailForm_CC = '';
                $previewEmailForm_BCC = 'noc@conversantmedia.com';
                $previewEmailForm_Subject = "Request for downtime on ".$_POST['generateEmailForm-5_downtime-2']." | ".strtoupper($_POST['generateEmailForm-5_downtime-4']);
                $previewEmailForm_Body = "Hello,<br><br>The NOC has determined that ".$_POST['generateEmailForm-5_downtime-2']." currently has a faulty ".$_POST['generateEmailForm-5_downtime-3'].".  We will need to power down this server in order to perform maintenance.  Maintenance is currently scheduled on ".$_POST['generateEmailForm-5_downtime-5']." from ".$_POST['generateEmailForm-5_downtime-6']." to ".$_POST['generateEmailForm-5_downtime-7'].".<br><br><a href='http://jira.cnvrmedia.net/browse/".strtoupper($_POST['generateEmailForm-5_downtime-4'])."'>http://jira.cnvrmedia.net/browse/".strtoupper($_POST['generateEmailForm-5_downtime-4'])."</a><br><br>If this maintenance window will not work, let us know.".$previewEmailForm_Signature;

                generatePreviewForm($previewEmailForm_From,$previewEmailForm_To,$previewEmailForm_CC,$previewEmailForm_BCC,$previewEmailForm_Subject,$previewEmailForm_Body);
        }

	function generateEmailPreview5_serverRestart($previewEmailForm_Signature){
                $previewEmailForm_From = 'noc@conversantmedia.com';
                $previewEmailForm_To = $_POST['generateEmailForm-5_serverRestart-1'];
                $previewEmailForm_CC = '';
                $previewEmailForm_BCC = 'noc@conversantmedia.com';
                $previewEmailForm_Subject = "Server reboot required on ".$_POST['generateEmailForm-5_serverRestart-2']." | ".strtoupper($_POST['generateEmailForm-5_serverRestart-3']);
		$previewEmailForm_Body = "Hello,<br><br>The NOC would like to perform a server reboot on ".$_POST['generateEmailForm-5_serverRestart-5']." at ".$_POST['generateEmailForm-5_serverRestart-6']." for the following server:<br><br>".$_POST['generateEmailForm-5_serverRestart-2']."<br><br>We have observed the following error:<br><br>".$_POST['generateEmailForm-5_serverRestart-3']."<br><br>Information about this scheduled restart can be found here:<br><br><a href='http://jira.cnvrmedia.net/browse/".strtoupper($_POST['generateEmailForm-5_serverRestart-4'])."'>http://jira.cnvrmedia.net/browse/".strtoupper($_POST['generateEmailForm-5_serverRestart-4'])."</a>".$previewEmailForm_Signature;

                generatePreviewForm($previewEmailForm_From,$previewEmailForm_To,$previewEmailForm_CC,$previewEmailForm_BCC,$previewEmailForm_Subject,$previewEmailForm_Body);
        }

	function generateEmailPreview6_driveCapacity($previewEmailForm_Signature){
                $previewEmailForm_From = 'noc@conversantmedia.com';
                $previewEmailForm_To = $_POST['generateEmailForm-6_driveCapacity-1'];
                $previewEmailForm_CC = '';
                $previewEmailForm_BCC = 'noc@conversantmedia.com';
                $previewEmailForm_Subject = "High disk space usage for ".$_POST['generateEmailForm-6_driveCapacity-2']." - ".$_POST['generateEmailForm-6_driveCapacity-3'];

		$generateEmailForm6_driveCapacity_df = trim($_POST['generateEmailForm-6_driveCapacity-4']);
		$generateEmailForm6_driveCapacity_df = explode("\n",$generateEmailForm6_driveCapacity_df);
		$generateEmailForm6_driveCapacity_df = implode(" ",$generateEmailForm6_driveCapacity_df);
		$generateEmailForm6_driveCapacity_df = explode(" ",$generateEmailForm6_driveCapacity_df);
		$generateEmailForm6_driveCapacity_df_size = sizeof($generateEmailForm6_driveCapacity_df);
		for($i=0;$i<$generateEmailForm6_driveCapacity_df_size;$i++){
			if(strlen($generateEmailForm6_driveCapacity_df[$i])==0){
				unset($generateEmailForm6_driveCapacity_df[$i]);
			}
		}
		$generateEmailForm6_driveCapacity_df = implode(',',$generateEmailForm6_driveCapacity_df);
		$generateEmailForm6_driveCapacity_df = explode(',',$generateEmailForm6_driveCapacity_df);
		
		$generateEmailForm6_driveCapacity_du = trim($_POST['generateEmailForm-6_driveCapacity-5']);
		$generateEmailForm6_driveCapacity_du = explode("\n",$generateEmailForm6_driveCapacity_du);
		$generateEmailForm6_driveCapacity_du_size = sizeof($generateEmailForm6_driveCapacity_du);
                for($i=0;$i<$generateEmailForm6_driveCapacity_du_size;$i++){
                        if(strlen($generateEmailForm6_driveCapacity_du[$i])==0){
                                unset($generateEmailForm6_driveCapacity_du[$i]);
                        }
                }

		$previewEmailForm_Body='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" /><style>.oddrow{background-color:#eee;}.evenrow{background-color:#fff;}.table-container{border:1px solid #aaa;border-radius:4px;background-color:#fff;width:100%;margin-left:1px;margin-right:1px;}.table-issue{width:100%;padding:10px;background-color:#fff;}.table-issue-head{width:25%;text-decoration:bold;}.table-issue-body{width:75%;}.border-bottom{border-bottom:1px solid #aaa;}.item-header{font-size:20px;color:#fff;text-align:center;background-color:#101820;border-radius:4px;margin-left:1px;margin-right:1px;}.server-head{background-color:#e1523d !important;}.deployments-issue-head{background-color:#00af66 !important;}.vendorMaint-issue-head{background-color:#34657f !important;}.header-img{border:0px;margin:0px;padding:0px;}</style></head><body>';
		$previewEmailForm_Body.='<table style="min-width:550px;width:100%;margin:0px;padding:0px;"><tr><td style="background-color:#101820;border-bottom:3px solid #ffb500;padding:5px;width:100%;text-align:right;"><img style="border:0px;margin:0px;padding:0px;" src="http://www.conversantmedia.com/sites/all/themes/my_theme/images/conversant_logo.png" /></td></tr></table>';
		$previewEmailForm_Body.="<table><tr><td>Hello,<br><br>The following host is currently alerting for low available disk space.  Please delete, move or compress some files on the mount with high utilization to ensure there are no issues due to low available space.</td></tr></table><table style='min-width:550px;width:100%;margin-top:10px;' cellpadding=0 cellspacing=0><tr><td style='font-size:20px;color:#fff;text-align:center;border-radius:4px;margin-left:1px;margin-right:1px;background-color:#e1523d;'>".$_POST['generateEmailForm-6_driveCapacity-2']." - ".$_POST['generateEmailForm-6_driveCapacity-3']."</td></tr></table><table style='min-width:550px;border:1px solid #aaa;border-radius:4px;background-color:#fff;width:100%;margin-left:1px;margin-right:1px;margin-top:10px;'><tr><td><table style='min-width:550px;width:100%;' cellpadding=0 cellspacing=0><tr><td style='border-bottom:1px solid #aaa;'>Filesystem</td><td style='border-bottom:1px solid #aaa;'>Size</td><td style='border-bottom:1px solid #aaa;'>Used</td><td style='border-bottom:1px solid #aaa;'>Avail</td><td style='border-bottom:1px solid #aaa;'>Use %</td><td style='border-bottom:1px solid #aaa;'>Mounted on</td></tr>";

		$j=0;
		$i=0;
		if($generateEmailForm6_driveCapacity_df[0]=="Filesystem"){
			$i=7;
		}
		for($i;$i<sizeof($generateEmailForm6_driveCapacity_df);$i++){
			if($j==0){
				$previewEmailForm_Body.="<tr>";
			}
			$previewEmailForm_Body.="<td>".$generateEmailForm6_driveCapacity_df[$i]."</td>";
			if($j==5){
				$previewEmailForm_Body.="</tr>";
				$j=0;
			}else{
				$j++;
			}
		}
		$previewEmailForm_Body.="</table></td></tr></table>";
	
		if($_POST['generateEmailForm-6_driveCapacity-5-check']=='on'){
			$previewEmailForm_Body.="<table style='min-width:550px;border:1px solid #aaa;border-radius:4px;background-color:#fff;width:100%;margin-left:1px;margin-right:1px;margin-top:10px;'><tr><td>";
			$previewEmailForm_Body.="<table style='min-width:550px;width:100%;' cellpadding=0 cellspacing=0><tr><td colspan=1 style='border-bottom:1px solid #aaa;'>Used</td><td colspan=5 style='border-bottom:1px solid #aaa;'>Directory</td></tr>";
			for($i=0;$i<sizeof($generateEmailForm6_driveCapacity_du);$i++){
				$dirMount = strstr($generateEmailForm6_driveCapacity_du[$i],"/");
				$dirUsed = strstr($generateEmailForm6_driveCapacity_du[$i],"/",TRUE);
				$generateEmailForm6_driveCapacity_du[$i] = trim($dirUsed)."</td><td colspan=5>".trim($dirMount);
				$previewEmailForm_Body.="<tr><td colspan=1>".$generateEmailForm6_driveCapacity_du[$i]."</td></tr>";
			}
		}

		$previewEmailForm_Body.="</table></td></tr></table><table style='min-width:550px;width:100%;'><tr><td>".$previewEmailForm_Signature."</td></tr></table></body></html>";

                generatePreviewForm($previewEmailForm_From,$previewEmailForm_To,$previewEmailForm_CC,$previewEmailForm_BCC,$previewEmailForm_Subject,$previewEmailForm_Body);
        }
	
	function generateEmailPreview6_hotSwap($previewEmailForm_Signature){
                $previewEmailForm_From = 'noc@conversantmedia.com';
                $previewEmailForm_To = $_POST['generateEmailForm-6_hotSwap-1'];
                $previewEmailForm_CC = '';
                $previewEmailForm_BCC = 'noc@conversantmedia.com';
                $previewEmailForm_Subject = "Notification of ".$_POST['generateEmailForm-6_hotSwap-3']." hot swap on ".$_POST['generateEmailForm-6_hotSwap-2']." | ".strtoupper($_POST['generateEmailForm-6_hotSwap-4']);
                $previewEmailForm_Body = "Hello,<br><br>The NOC has determined that ".$_POST['generateEmailForm-6_hotSwap-2']." currently has a faulty ".$_POST['generateEmailForm-6_hotSwap-3'].".  We would like to perform a ".$_POST['generateEmailForm-6_hotSwap-3']." swap on ".$_POST['generateEmailForm-6_hotSwap-5'].", from ".$_POST['generateEmailForm-6_hotSwap-6']." to ".$_POST['generateEmailForm-6_hotSwap-7'].". There is no need to power down the server since it is a hot swappable, but we would like to inform you of the work.<br><br><a href='http://jira.cnvrmedia.net/browse/".strtoupper($_POST['generateEmailForm_hotSwap-4'])."'>http://jira.cnvrmedia.net/browse/".strtoupper($_POST['generateEmailForm-6_hotSwap-4'])."</a>".$previewEmailForm_Signature;

                generatePreviewForm($previewEmailForm_From,$previewEmailForm_To,$previewEmailForm_CC,$previewEmailForm_BCC,$previewEmailForm_Subject,$previewEmailForm_Body);
        }

	function generateEmailPreview7_tech($previewEmailForm_Signature){
                $previewEmailForm_From = 'noc@conversantmedia.com';
                $previewEmailForm_To = 'noc@internap.com';
                $previewEmailForm_CC = '';
                $previewEmailForm_BCC = 'noc@conversantmedia.com';
		$previewEmailForm_Subject = "[".$_POST['generateEmailForm-7_tech-6']."] Technician Access for Conversant Media";
                $previewEmailForm_Body = "Please allow technician ".$_POST['generateEmailForm-7_tech-1']." access to the ".$_POST['generateEmailForm-7_tech-6']." facility from ".$_POST['generateEmailForm-7_tech-3']." to ".$_POST['generateEmailForm-7_tech-4']." on ".$_POST['generateEmailForm-7_tech-2'].".  This technician will be working on equipment located in our cage at the following location:<br><br>".$_POST['generateEmailForm-7_tech-5'].$previewEmailForm_Signature;

                generatePreviewForm($previewEmailForm_From,$previewEmailForm_To,$previewEmailForm_CC,$previewEmailForm_BCC,$previewEmailForm_Subject,$previewEmailForm_Body);
        }

	function generateEmailPreview7_package($previewEmailForm_Signature){
                $previewEmailForm_From = 'noc@conversantmedia.com';
		$previewEmailForm_To = 'noc@internap.com';
		$previewEmailForm_CC = '';
                $previewEmailForm_BCC = 'noc@conversantmedia.com';
		$previewEmailForm_Subject = "[".$_POST['generateEmailForm-7_package-3']."] Notification of inbound package for Conversant Media";
		$previewEmailForm_Body = "Please accept ".$_POST['generateEmailForm-7_package-2']." package(s) from Dell with a DPS# ".$_POST['generateEmailForm-7_package-1']." on behalf of Conversant Media and notify us when available.  Package(s) are being sent to the ".$_POST['generateEmailForm-7_package-3']." facility.".$previewEmailForm_Signature;

		generatePreviewForm($previewEmailForm_From,$previewEmailForm_To,$previewEmailForm_CC,$previewEmailForm_BCC,$previewEmailForm_Subject,$previewEmailForm_Body);
	}

	function generateEmailPreview7_remoteHands($previewEmailForm_Signature){
                $count_equipList=intval($_POST['generateEmailForm-7_remoteHands-equipList-count']);
		$previewEmailForm_From = 'noc@conversantmedia.com';
		$previewEmailForm_To = 'noc@internap.com';
		$previewEmailForm_CC = '';
                $previewEmailForm_BCC = 'noc@conversantmedia.com';
		$previewEmailForm_Subject = "[".$_POST['generateEmailForm-7_remoteHands-7']."] Remote Hands Request for Conversant Media";
		$previewEmailForm_Body="Hello,<br><br>We would like to request remote hands at the ".$_POST['generateEmailForm-7_remoteHands-7']." facility to assist us with the following task, on ".$_POST['generateEmailForm-7_remoteHands-2']." at ".$_POST['generateEmailForm-7_remoteHands-3'].":<br><br>".$_POST['generateEmailForm-7_remoteHands-1']."<br><br>The equipment being addressed with this task are as follows:<br>";
		$added_equip=0;
                for($ii=1;$ii<=$count_equipList;$ii++){
			if( $_POST['generateEmailForm-7_remoteHands-equipList_'.$ii.'-model'] != ''){
                        	$previewEmailForm_Body.="<br>".$_POST['generateEmailForm-7_remoteHands-equipList_'.$ii.'-model']."<br>Serial: ".strtoupper($_POST['generateEmailForm-7_remoteHands-equipList_'.$ii.'-svctag'])."<br>Location: ".strtoupper($_POST['generateEmailForm-7_remoteHands-equipList_'.$ii.'-location'])."<br>";
			}
                        $added_equip++;
                }

		$previewEmailForm_Body.="<br>Additional info:<br><br>";
		if( substr($_POST['generateEmailForm-7_remoteHands-5'],-1)!="." ){
			$_POST['generateEmailForm-7_remoteHands-5'].=".";
		}
		$previewEmailForm_Body.=$_POST['generateEmailForm-7_remoteHands-5'].$previewEmailForm_Signature;

		generatePreviewForm($previewEmailForm_From,$previewEmailForm_To,$previewEmailForm_CC,$previewEmailForm_BCC,$previewEmailForm_Subject,$previewEmailForm_Body);
	}

	function generateEmailPreview8_statusReport($previewEmailForm_Signature){
		$count_hotItems=intval($_POST['generateEmailForm-8_statusReport-hotItems-count']);
		$count_deployments=intval($_POST['generateEmailForm-8_statusReport-deployments-count']);
		$count_vendorMaint=intval($_POST['generateEmailForm-8_statusReport-vendorMaint-count']);

		$date = new DateTime();
		$date->setTimezone(new DateTimeZone($_POST['generateEmailForm-8_statusReport-tz']));
		
                $previewEmailForm_From = 'noc@conversantmedia.com';
		$previewEmailForm_To = 'Information Technology, ';
		$previewEmailForm_CC = '';
                $previewEmailForm_BCC = 'noc@conversantmedia.com';
		$previewEmailForm_Subject = 'Status Report for '.$date->format('D F d, Y');
		$previewEmailForm_Body = '';

		$previewEmailForm_Body.="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" /><style>.oddrow{background-color:#eee;}.evenrow{background-color:#fff;}.table-container{border:1px solid #aaa;border-radius:4px;background-color:#fff;width:100%;}.page-padding{width:100%;padding-left:10px;padding-right:10px;}.table-issue{width:100%;padding:10px;background-color:#fff;}.table-issue-head{width:25%;text-decoration:bold;}.table-issue-body{width:75%;}.border-bottom{border-bottom:1px solid #aaa;}.item-header{font-size:20px;color:#fff;text-align:center;background-color:#101820;border-radius:4px;margin-left:1px;margin-right:1px;}.hotItems-issue-head{background-color:#e1523d !important;}.deployments-issue-head{background-color:#00af66 !important;}.vendorMaint-issue-head{background-color:#34657f !important;}.header-img{border:0px;margin:0px;padding:0px;}</style></head><body><table style='min-width:550px;width:100%;padding-left:10px;padding-right:10px;'><tr><td style='background-color:#101820;border-bottom:3px solid #ffb500;padding:5px;width:100%;text-align:right;'><img style='border:0px;margin:0px;padding:0px;' src='http://www.conversantmedia.com/sites/all/themes/my_theme/images/conversant_logo.png' /></td></tr></table><table style='min-width:550px;padding-left:10px;padding-right:10px;'><tr><td>Hello,<br><br>The NOC has observed the following events over the past 12 hours.  For updates regarding the listed events shown, please subscribe to the following assigned Jira tickets or contact the Conversant NOC for further updates.</td></tr></table>";

		$previewEmailForm_Body.="<table style='min-width:550px;width:100%;padding-left:10px;padding-right:10px;'><tr><td style='background-color:#e1523d;font-size:20px;color:#fff;text-align:center;border-radius:4px;margin-left:1px;margin-right:1px;'>Hot Items</td></tr></table>";

		$added_hotItems=0;
		if($count_hotItems>0){
			for($ii=1;$ii<=$count_hotItems;$ii++){
				if( strlen($_POST['generateEmailForm-8_statusReport-hotItems'.$ii.'-desc'])!=0 ){
					$previewEmailForm_Body.="<table style='min-width:550px;width:100%;padding-left:10px;padding-right:10px;'><tr><td><table style='min-width:550px;border:1px solid #aaa;border-radius:4px;background-color:#fff;width:100%;'><tr><td><table style='min-width:550px;width:100%;padding:10px;background-color:#fff;' border=0 cellpadding=0 cellspacing=0><tr><td style='border-bottom:1px solid #aaa;width:25%;text-decoration:bold;'>Issue</td><td style='border-bottom:1px solid #aaa;width:75%;'>".$_POST['generateEmailForm-8_statusReport-hotItems'.$ii.'-desc']."</td></tr><tr><td style='border-bottom:1px solid #aaa;width:25%;text-decoration:bold;'>Time Issue Manifested</td><td style='border-bottom:1px solid #aaa;width:75%;'>".$_POST['generateEmailForm-8_statusReport-hotItems'.$ii.'-time']."</td></tr><tr><td style='border-bottom:1px solid #aaa;width:25%;text-decoration:bold;'>Impact Observed</td><td style='border-bottom:1px solid #aaa;width:75%;'>".$_POST['generateEmailForm-8_statusReport-hotItems'.$ii.'-impact']."</td></tr><tr><td style='border-bottom:1px solid #aaa;width:25%;text-decoration:bold;'>Resolution Steps Taken</td><td style='border-bottom:1px solid #aaa;width:75%;'>".$_POST['generateEmailForm-8_statusReport-hotItems'.$ii.'-steps']."</td></tr><tr><td style='width:25%;text-decoration:bold;'>Current Status</td><td style='width:75%;'>".$_POST['generateEmailForm-8_statusReport-hotItems'.$ii.'-status']."</td></tr></table></td></tr></table></td></tr></table>";
					$added_hotItems++;
				}
			}
			if($added_hotItems==0){
				$previewEmailForm_Body.="<table style='min-width:550px;width:100%;padding-left:10px;padding-right:10px;'><tr><td><table style='min-width:550px;border:1px solid #aaa;border-radius:4px;background-color:#fff;width:100%;'><tr><td><table style='min-width:550px;width:100%;padding:10px;background-color:#fff;' border=0 cellpadding=0 cellspacing=0><tr><td>No unplanned network or production outages were observed.</td></tr></table></td></tr></table></td></tr></table>";
			}
		}else{
			$previewEmailForm_Body.="<table style='min-width:550px;width:100%;padding-left:10px;padding-right:10px;'><tr><td><table style='min-width:550px;border:1px solid #aaa;border-radius:4px;background-color:#fff;width:100%;'><tr><td><table style='min-width:550px;width:100%;padding:10px;background-color:#fff;' border=0 cellpadding=0 cellspacing=0><tr><td>No unplanned network or production outages were observed.</td></tr></table></td></tr></table></td></tr></table>";
		}

		$previewEmailForm_Body.="<table style='min-width:550px;width:100%;padding-left:10px;padding-right:10px;'><tr><td style='font-size:20px;color:#fff;text-align:center;border-radius:4px;margin-left:1px;margin-right:1px;background-color:#00af66;'>Scheduled Deployments/Maintenance</td></tr></table>";
		
		$added_deployments=0;
		if($count_deployments>0){
			for($ii=1;$ii<=$count_deployments;$ii++){
				if( strlen($_POST['generateEmailForm-8_statusReport-deployments'.$ii.'-ticket'])!=0 ){
					$previewEmailForm_Body.="<table style='min-width:550px;width:100%;padding-left:10px;padding-right:10px;'><tr><td><table style='min-width:550px;border:1px solid #aaa;border-radius:4px;background-color:#fff;width:100%;'><tr><td><table style='min-width:550px;width:100%;padding:10px;background-color:#fff;' border=0 cellpadding=0 cellspacing=0><tr><td style='border-bottom:1px solid #aaa;width:25%;text-decoration:bold;'>Jira Ticket</td><td style='border-bottom:1px solid #aaa;width:75%;'><a href='http://jira.cnvrmedia.net/browse/".strtoupper($_POST['generateEmailForm-8_statusReport-deployments'.$ii.'-ticket'])."' target='_blank'>".strtoupper($_POST['generateEmailForm-8_statusReport-deployments'.$ii.'-ticket'])."</a></td></tr><tr><td style='border-bottom:1px solid #aaa;width:25%;text-decoration:bold;'>Summary</td><td style='border-bottom:1px solid #aaa;style='width:75%;'>".$_POST['generateEmailForm-8_statusReport-deployments'.$ii.'-summary']."</td></tr><tr><td style='border-bottom:1px solid #aaa;width:25%;text-decoration:bold;'>Start Time</td><td style='border-bottom:1px solid #aaa;width:75%;'>".$_POST['generateEmailForm-8_statusReport-deployments'.$ii.'-startTime']."</td></tr><tr><td style='border-bottom:1px solid #aaa;width:25%;text-decoration:bold;'>End Time</td><td style='border-bottom:1px solid #aaa;width:75%;'>".$_POST['generateEmailForm-8_statusReport-deployments'.$ii.'-endTime']."</td></tr><tr><td style='width:25%;text-decoration:bold;'>Current Status</td><td style='width:75%;'>".$_POST['generateEmailForm-8_statusReport-deployments'.$ii.'-status']."</td></tr></table></td></tr></table></td></tr></table>";
					$added_deployments++;
				}
			}
			if($added_deployments==0){
				$previewEmailForm_Body.="<table style='min-width:550px;width:100%;padding-left:10px;padding-right:10px;'><tr><td><table style='min-width:550px;border:1px solid #aaa;border-radius:4px;background-color:#fff;width:100%;'><tr><td><table style='min-width:550px;width:100%;padding:10px;background-color:#fff;' border=0 cellpadding=0 cellspacing=0><tr><td>No scheduled maintenance or deployments occurred.</td></tr></table></td></tr></table></td></tr></table>";
			}
		}else{
			$previewEmailForm_Body.="<table style='min-width:550px;width:100%;padding-left:10px;padding-right:10px;'><tr><td><table style='min-width:550px;border:1px solid #aaa;border-radius:4px;background-color:#fff;width:100%;'><tr><td><table style='min-width:550px;width:100%;padding:10px;background-color:#fff;' border=0 cellpadding=0 cellspacing=0><tr><td>No scheduled maintenance or deployments occurred.</td></tr></table></td></tr></table></td></tr></table>";
		}
		
		$previewEmailForm_Body.="<table style='min-width:550px;width:100%;padding-left:10px;padding-right:10px;'><tr><td style='font-size:20px;color:#fff;text-align:center;border-radius:4px;margin-left:1px;margin-right:1px;background-color:#34657f;'>Planned Vendor Maintenance</td></tr></table>";

		$added_vendorMaint=0;
		if($count_vendorMaint>0){
			for($ii=1;$ii<=$count_vendorMaint;$ii++){
				if( strlen($_POST['generateEmailForm-8_statusReport-vendorMaint'.$ii.'-vendor'])!=0 ){
					$previewEmailForm_Body.="<table style='min-width:550px;width:100%;padding-left:10px;padding-right:10px;'><tr><td><table style='min-width:550px;border:1px solid #aaa;border-radius:4px;background-color:#fff;width:100%;'><tr><td><table style='min-width:550px;width:100%;padding:10px;background-color:#fff;' border=0 cellpadding=0 cellspacing=0><tr><td style='border-bottom:1px solid #aaa;width:25%;text-decoration:bold;'>Vendor</td><td style='border-bottom:1px solid #aaa;width:75%;'>".$_POST['generateEmailForm-8_statusReport-vendorMaint'.$ii.'-vendor']."</td></tr><tr><td style='border-bottom:1px solid #aaa;width:25%;text-decoration:bold;'>Vendor Ticket</td><td style='border-bottom:1px solid #aaa;width:75%;'>".$_POST['generateEmailForm-8_statusReport-vendorMaint'.$ii.'-ticket']."</td></tr><tr><td style='border-bottom:1px solid #aaa;width:25%;text-decoration:bold;'>Affected Sites</td><td style='border-bottom:1px solid #aaa;width:75%;'>".$_POST['generateEmailForm-8_statusReport-vendorMaint'.$ii.'-sites']."</td></tr><tr><td style='border-bottom:1px solid #aaa;width:25%;text-decoration:bold;'>Type of Work</td><td style='border-bottom:1px solid #aaa;width:75%;'>".$_POST['generateEmailForm-8_statusReport-vendorMaint'.$ii.'-type']."</td></tr><tr><td style='border-bottom:1px solid #aaa;width:25%;text-decoration:bold;'>Description of Work</td><td style='border-bottom:1px solid #aaa;width:75%;'>".$_POST['generateEmailForm-8_statusReport-vendorMaint'.$ii.'-desc']."</td></tr><tr><td style='border-bottom:1px solid #aaa;width:25%;text-decoration:bold;'>Start Time</td><td style='border-bottom:1px solid #aaa;width:75%;'>".$_POST['generateEmailForm-8_statusReport-vendorMaint'.$ii.'-startTime']."</td></tr><tr><td style='width:25%;text-decoration:bold;'>End Time</td><td style='width:75%;'>".$_POST['generateEmailForm-8_statusReport-vendorMaint'.$ii.'-endTime']."</td></tr></table></td></tr></table></td></tr></table>";
					$added_vendorMaint++;
				}
			}
			if($added_vendorMaint==0){
				$previewEmailForm_Body.="<table style='min-width:550px;width:100%;padding-left:10px;padding-right:10px;'><tr><td><table style='min-width:550px;border:1px solid #aaa;border-radius:4px;background-color:#fff;width:100%;'><tr><td><table style='min-width:550px;width:100%;padding:10px;background-color:#fff;' border=0 cellpadding=0 cellspacing=0><tr><td>No scheduled vendor maintenance occurred.</td></tr></table></td></tr></table></td></tr></table>";
			}
		}else{
			$previewEmailForm_Body.="<table style='min-width:550px;width:100%;padding-left:10px;padding-right:10px;'><tr><td><table style='min-width:550px;border:1px solid #aaa;border-radius:4px;background-color:#fff;width:100%;'><tr><td><table style='min-width:550px;width:100%;padding:10px;background-color:#fff;' border=0 cellpadding=0 cellspacing=0><tr><td>No scheduled vendor maintenance occurred.</td></tr></table></td></tr></table></td></tr></table>";
		}
		
		$previewEmailForm_Body.="<table style='min-width:550px;padding-left:10px;padding-right:10px;'><tr><td>If you have any questions about any items in today's report, please contact the Conversant NOC.<br><br>Thank you,<br><br>Conversant Network Operations Center<br><a href='mailto:noc@conversantmedia.com'>noc@conversantmedia.com</a><br>800.566.3316</td></tr></table></body></html>";

		generatePreviewForm($previewEmailForm_From,$previewEmailForm_To,$previewEmailForm_CC,$previewEmailForm_BCC,$previewEmailForm_Subject,$previewEmailForm_Body);
	}

	function generateEmailPreview_blank($previewEmailForm_Signature){
                $previewEmailForm_From = 'noc@conversantmedia.com';
		$previewEmailForm_To = '';
		$previewEmailForm_CC = '';
                $previewEmailForm_BCC = 'noc@conversantmedia.com';
		$previewEmailForm_Subject = '';
		$previewEmailForm_Body="<table style='width:100%;padding-left:10px;padding-right:10px;'><tr><td style='background-color:#101820;border-bottom:3px solid #ffb500;padding:5px;width:100%;text-align:right;'><img style='border:0px;margin:0px;padding:0px;' src='http://www.conversantmedia.com/sites/all/themes/my_theme/images/conversant_logo.png' /></td></tr></table><table style='padding-left:10px;padding-right:10px;'><tr><td>";
		$previewEmailForm_Body.=$previewEmailForm_Signature;
		$previewEmailForm_Body.="</td></tr></table>";

		generatePreviewForm($previewEmailForm_From,$previewEmailForm_To,$previewEmailForm_CC,$previewEmailForm_BCC,$previewEmailForm_Subject,$previewEmailForm_Body);
	}

	function generatePreviewForm($previewEmailForm_From,$previewEmailForm_To,$previewEmailForm_CC,$previewEmailForm_BCC,$previewEmailForm_Subject,$previewEmailForm_Body,$alert_ID_list=""){
		echo '
			<div id="previewEmailFormContainer" class="panel panel-default formContainer">
				<div class="panel-heading"><div style="font-size:18px;">Email Preview</div></div>
                                <div class="panel-body">
                                        <div style="float:left;height:100%;"><div style="margin:4px 0px 11px 0px;">From:</div><div style="margin-bottom:11px;">To:</div><div style="margin-bottom:11px;">CC:</div><div style="margin-bottom:11px;">BCC:</div><div style="margin-bottom:5px;">Subject:</div></div>
                                        <form name="previewEmailForm" action="php/smtpMailer.php" onsubmit="return checkEmailPreview()" method="post">
                                                <input class="previewEmailForm" name="mailerFrom" id="previewEmailForm_From" type="text" value="'.$previewEmailForm_From.'" required disabled /><br>
                                                <input class="previewEmailForm" name="mailerTo" id="previewEmailForm_To" type="text" value="'.$previewEmailForm_To.'" required autofocus/><br>
                                                <input class="previewEmailForm" name="mailerCC" id="previewEmailForm_CC" type="text" value="'.$previewEmailForm_CC.'" /><br>
                                                <input class="previewEmailForm" name="mailerBCC" id="previewEmailForm_BCC" type="text" value="'.$previewEmailForm_BCC.'" /><br>
                                                <input class="previewEmailForm" name="mailerSubject" id="previewEmailForm_Subject" type="text" value="'.$previewEmailForm_Subject.'" required /><br>
                                                <textarea class="previewEmailForm" style="width:100%;height:250px;" name="mailerBody" id="previewEmailForm_Body">'.$previewEmailForm_Body.'</textarea><br>
						<input type="text" name="alert_ID_list" value="'.$alert_ID_list.'" hidden>
                                                <input style="margin-left:10px;float:right;" class="btn btn-success btn-sm form-button" type="submit" value="Send Email" /><input style="float:right;" class="btn btn-danger btn-sm form-button" type="button" id="previewEmail_Cancel" value="Cancel Email">
                                        </form>
                                </div>
                        </div>
		';
	}
?>

<!DOCTYPE HTML>
<html>
	<head>
		<?PHP getHtmlHead(); ?>
		<script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>
	</head>

	<body>
		<?PHP getHeader(); ?>
		<?PHP buildPreviewForm(); ?>

		<script>
			$(document).ready(function(){
				$(document).attr('title', $(document).attr('title')+' - Preview Email');
				$('#nav-active-communication').addClass('active');
				$('#previewEmail_Cancel').click(function(){
					$(location).attr('href','generateEmail.php');
				});

                                function split( val ) {
                                        return val.split( /,\s*/ );
                                }
                                function extractLast( term ) {
                                        return split( term ).pop();
                                }

                                $( "#previewEmailForm_To,#previewEmailForm_CC,#previewEmailForm_BCC" )
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

			});

			function checkEmailPreview(){
				var getURL = window.location.search;
				splitURL = getURL.split("?");
				getURL = splitURL[1];
				if(getURL == 'blank=true'){
					mailerBody = document.forms["previewEmailForm"]["mailerBody"].value;
				}
			}

			tinymce.init({
				selector: "textarea#previewEmailForm_Body",
				plugins: [
					"advlist autolink lists link image charmap print preview anchor",
					"searchreplace visualblocks code fullscreen",
					"insertdatetime media table contextmenu paste"
				],
				toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
			});
                </script>

		<?PHP addFooter(); ?>
	</body>
</html>
