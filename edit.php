<?php
	$path=$_SERVER['DOCUMENT_ROOT'];
	require_once $path.'/php/dbconn.php';
	include $path.'/php/common.php';

	if (!empty($_POST)) {
		$db_connection_nocdash = new db_nocdash();

		if($_REQUEST['type']=='maint'){
			$siteList = implode(",",$_POST['editMaintForm_Sites']);
			$result = $db_connection_nocdash -> query('UPDATE Noc.maintenance SET Provider='.$db_connection_nocdash->quote($_POST[editMaintForm_Provider]).',CKT_ID='.$db_connection_nocdash->quote($_POST[editMaintForm_CKT]).',Work_Start=CONVERT_TZ("'.date("Y-m-d H:i:s",strtotime($_POST[editMaintForm_DateTimeStart])).'","'.$_POST[form_timezone].'","UTC"),Work_End=CONVERT_TZ("'.date('Y-m-d H:i:s',strtotime($_POST[editMaintForm_DateTimeEnd])).'","'.$_POST[form_timezone].'","UTC"),Duration='.$db_connection_nocdash->quote($_POST[editMaintForm_Duration]).',Affected_Sites='.$db_connection_nocdash->quote($siteList).',Provider_Ticket_Num='.$db_connection_nocdash->quote($_POST[editMaintForm_Provider_Ticket]).',Work_Type='.$db_connection_nocdash->quote($_POST[editMaintForm_Work_Type]).',Work_Description='.$db_connection_nocdash->quote($_POST[editMaintForm_Work_Description]).',lastModified=NOW(),lastModifiedBy='.$db_connection_nocdash->quote($_SESSION['displayName']).' WHERE ID='.$db_connection_nocdash->quote($_POST[editMaintForm_ID]));

			if($_POST['editMaintForm_Cancelled']){
				$result = $db_connection_nocdash -> query('UPDATE Noc.maintenance SET Cancelled=1,cancelledBy='.$db_connection_nocdash->quote($_SESSION['displayName']).' WHERE ID='.$db_connection_nocdash->quote($_POST['editMaintForm_ID']));
			}
			if($_POST['editMaintForm_Completed']){
				$result = $db_connection_nocdash -> query('UPDATE Noc.maintenance SET Completed=1,Mark_CompletedBy='.$db_connection_nocdash->quote($_SESSION['displayName']).' WHERE ID='.$db_connection_nocdash->quote($_POST['editMaintForm_ID']));
			}

			if($result === false){
				echo '[Error]: Unable to update eventID '.$_REQUEST['id'].'.<br>';
				$db_connection_nocdash->error();
			}else{
				header("location:maintenance.php");
			}
		}

		if($_REQUEST['type']=='firesDash'){
			$result = $db_connection_nocdash -> query('UPDATE Noc.fireBoard SET eventDesc='.$db_connection_nocdash->quote($_POST[editEventForm_Desc]).',Est_Duration='.$db_connection_nocdash->quote($_POST[editEventForm_Est_Duration]).',eventLastModified=NOW() WHERE eventID='.$db_connection_nocdash->quote($_POST[editEventForm_ID]));
			if($_POST['editEventForm_Concluded']){
				$result = $db_connection_nocdash->query('UPDATE Noc.fireBoard SET eventEnd=NOW(),eventLastModified=NOW(),eventActive=0,closedBy='.$db_connection_nocdash->quote($_SESSION['displayName']).',lastModifiedBy='.$db_connection_nocdash->quote($_SESSION['displayName']).' WHERE eventID='.$db_connection_nocdash->quote($_POST[editEventForm_ID]));
			}else{
				$result = $db_connection_nocdash->query('UPDATE Noc.fireBoard SET eventType='.$db_connection_nocdash->quote($_POST[editEventForm_Type]).',eventLastModified=NOW(),eventActive=1,closedBy=NULL,lastModifiedBy='.$db_connection_nocdash->quote($_SESSION['displayName']).' WHERE eventID='.$db_connection_nocdash->quote($_POST[editEventForm_ID]));
			}
		
			if($result === false){
				echo '[Error]: Unable to update eventID '.$_REQUEST['id'].'.<br>';
				$db_connection_nocdash->error();
			}else{
				header("location:firesDash.php");
			}
		}
	}

	function createEditForm($formType){
		$db_connection_nocdash = new db_nocdash();

		if($formType == 'maint'){
			$query = "SELECT * FROM Noc.maintenance WHERE ID=".$db_connection_nocdash->quote($_REQUEST['id']);
		}

		if($formType == 'firesDash'){
			$query = "SELECT eventID,eventType,eventDesc,est_duration,eventActive FROM Noc.fireBoard WHERE eventID=".$db_connection_nocdash->quote($_REQUEST['id']);
		}

		$result = $db_connection_nocdash->query($query);
			
		if($result === false){
			echo '[Error]: Cannot find eventID '.$_REQUEST['id'].'.<br>';
			$db_connection_nocdash->error();
		} else {
			$eventInfo = $result->fetch_assoc();
		}
		
		if($formType=='maint'){
			echo '
				<form class="col-lg-12 col-md-12 col-sm-12 col-xs-12" name="editMaintForm" role="form" action="edit.php?type=maint" method="post">
                                        <h4>Edit Upcoming Vendor Maintenance</h4>
                                        <label for="editMaintForm_Provider" class="control-label">Vendor</label>
					<select name="editMaintForm_Provider" id="editMaintForm_Provider" class="form-control" style="width:70%;min-width:200px;max-width:250px;" autofocus>
                        			<option value="Zayo"
			';
			if($eventInfo['Provider']=='Zayo'){
				echo ' selected';
			}
			echo '>Zayo</option>';
                        echo '<option value="XO"';
			if($eventInfo['Provider']=='XO'){
				echo ' selected';
			}
			echo '>XO</option>';
                        echo '<option value="Latisys"';
			if($eventInfo['Provider']=='Latisys'){
				echo ' selected';
			}
			echo '>Latisys</option>';
                        echo '<option value="Internap"';
			if($eventInfo['Provider']=='Internap'){
				echo ' selected';
			}
			echo '>Internap</option>';
                        echo '<option value="Akamai"';
			if($eventInfo['Provider']=='Akamai'){
				echo ' selected';
			}
			echo '>Akamai</option>';
                        echo '<option value="Keynote"';
			if($eventInfo['Provider']=='Keynote'){
				echo ' selected';
			}
			echo '>Keynote</option>';
                        echo '<option value="Hibernia"';
			if($eventInfo['Provider']=='Hibernia'){
				echo ' selected';
			}
			echo '>Hibernia</option>';
                        echo '<option value="Savvis"';
			if($eventInfo['Provider']=='Savvis'){
				echo ' selected';
			}
			echo '>Savvis</option>';
                        echo '<option value="Equinix"';
			if($eventInfo['Provider']=='Equinix'){
				echo ' selected';
			}
			echo '>Equinix</option>';
                        echo '</select>
					<label for="editMaintForm_DateTimeStart" class="control-label">Start Time (Local)</label>
					<input name="editMaintForm_DateTimeStart" id="editMaintForm_DateTimeStart" class="form-control datetimeRange" type="text" style="width:70%;min-width:200px;max-width:500px;" required value="'.$eventInfo['Work_Start'].'">
                                        <label for="editMaintForm_DateTimeEnd" class="control-label">Estimated End Time (Local)</label>
                                        <input name="editMaintForm_DateTimeEnd" id="editMaintForm_DateTimeEnd" class="form-control datetimeRange" type="text" style="width:70%;min-width:200px;max-width:500px;" required value="'.$eventInfo['Work_End'].'">
                                        <label for="editMaintForm_Duration" class="control-label">Duration</label>
                                        <input name="editMaintForm_Duration" id="editMaintForm_Duration" class="form-control" type="text" style="width:70%;min-width:200px;max-width:500px;" required value="'.$eventInfo['Duration'].'">
                                        <label for="editMaintForm_Work_Type" class="control-label">Type of Work</label>
                                        <input name="editMaintForm_Work_Type" id="editMaintForm_Work_Type" class="form-control" type="text" style="width:70%;min-width:200px;max-width:500px;" required value="'.$eventInfo['Work_Type'].'">
                                        <label for="editMaintForm_CKT" class="control-label">Circuit</label>
                                        <select name="editMaintForm_CKT" id="editMaintForm_CKT" class="form-control" style="width:70%;min-width:265px;max-width:275px;">
                                                <option value="n/a">n/a</option>
						<option value="OGYX/066617//ZYO">ORD&lt; &gt;SJC (OGYX/066617//ZYO)</option>
                                                <option value="OGYX/066625//ZYO">ORD&lt; &gt;IAD (OGYX/066625//ZYO)</option>
                                                <option value="OGYX/066598//ZYO">SJC&lt; &gt;IAD (OGYX/066598//ZYO)</option>
                                                <option value="OBK_AMS_10GE_51">AMS&lt; &gt;ORD (OBK_AMS_10GE_51)</option>
                                                <option value="ASH_AMS_10GE_61">AMS&lt; &gt;IAD (ASH_AMS_10GE_61)</option>
                                                <option value="OGYX/068097//ZYO">ORD&lt; &gt;CHI 1 (OGYX/068097//ZYO)</option>
                                                <option value="OGYX/063471//ZYO">ORD&lt; &gt;CHI 2 (OGYX/063471//ZYO)</option>
                                                <option value="SF/LUYX/819682/TQW">SJ2&lt; &gt;ORD (SF/LUYX/819682/TQW)</option>
                                                <option value="SF/OGXX/922509/TQW">SJ2&lt; &gt;SJC 1 (SF/OGXX/922509/TQW)</option>
                                                <option value="SF/OGYX/922563/TQW">SJ2&lt; &gt;SJC 2 (SF/OGYX/922563/TQW)</option>
                                                <option value="WA/OGGS/923909/TQW">DC6&lt; &gt;IAD 1 (WA/OGGS/923909/TQW)</option>
                                                <option value="WA/OGGS/924165/TQW">DC6&lt; &gt;IAD 2 (WA/OGGS/924165/TQW)</option>
                                        </select>
                                        <label for="editMaintForm_CKT_ID" class="control-label">CKT ID</label>
                                        <input name="editMaintForm_CKT_ID" id="editMaintForm_CKT_ID" class="form-control" type="text" style="width:70%;min-width:175px;max-width:185px;" value="'.$eventInfo['CKT_ID'].'" disabled>
                                        <label class="control-label">Affected Sites</label><br>
					<label for="editMaintForm_Sites_IAD" class="control-label"><input type="checkbox" name="editMaintForm_Sites[]" id="editMaintForm_Sites_IAD" value="IAD"';
			if(strstr($eventInfo['Affected_Sites'],"IAD")){
				echo ' checked';
			}
			echo '> IAD</label>
					<label for="editMaintForm_Sites_SJC" class="control-label"><input type="checkbox" name="editMaintForm_Sites[]" id="editMaintForm_Sites_SJC" value="SJC"';
			if(strstr($eventInfo['Affected_Sites'],"SJC")){
				echo ' checked';
			}
			echo '> SJC</label>
					<label for="editMaintForm_Sites_ORD" class="control-label"><input type="checkbox" name="editMaintForm_Sites[]" id="editMaintForm_Sites_ORD" value="ORD"';
			if(strstr($eventInfo['Affected_Sites'],"ORD")){
				echo ' checked';
			}
			echo '> ORD</label>
					<label for="editMaintForm_Sites_AMS" class="control-label"><input type="checkbox" name="editMaintForm_Sites[]" id="editMaintForm_Sites_AMS" value="AMS"';
			if(strstr($eventInfo['Affected_Sites'],"AMS")){
				echo ' checked';
			}
			echo '> AMS</label>
					<label for="editMaintForm_Sites_CHI" class="control-label"><input type="checkbox" name="editMaintForm_Sites[]" id="editMaintForm_Sites_CHI" value="CHI"';
			if(strstr($eventInfo['Affected_Sites'],"CHI")){
				echo ' checked';
			}
			echo '> CHI</label>
					<label for="editMaintForm_Sites_SJ2" class="control-label"><input type="checkbox" name="editMaintForm_Sites[]" id="editMaintForm_Sites_SJ2" value="SJ2"';
			if(strstr($eventInfo['Affected_Sites'],"SJ2")){
				echo ' checked';
			}
			echo '> SJ2</label>
					<label for="editMaintForm_Sites_DC6" class="control-label"><input type="checkbox" name="editMaintForm_Sites[]" id="editMaintForm_Sites_DC6" value="DC6"';
			if(strstr($eventInfo['Affected_Sites'],"DC6")){
				echo ' checked';
			}
			echo '> DC6</label><br>';
			echo '
				<label for="editMaintForm_Provider_Ticket" class="control-label">Maintenance Ticket #</label>
				<input name="editMaintForm_Provider_Ticket" id="editMaintForm_Provider_Ticket" class="form-control" type="text" style="width:70%;min-width:200px;max-width:500px;" required value="'.$eventInfo['Provider_Ticket_Num'].'">
				<label for="editMaintForm_Work_Description" class="control-label">Description of Work</label>
				<textarea id="editMaintForm_Work_Description" class="form-control" rows="8" name="editMaintForm_Work_Description" required>'.$eventInfo['Work_Description'].'</textarea>
				<label><input type="checkbox" name="editMaintForm_Cancelled" value="1"> Cancel This Scheduled Maintenance?</label><br>
				<label><input type="checkbox" name="editMaintForm_Completed" value="1"> Maintenance Complete?</label><br>
				<input type="hidden" name="editMaintForm_ID" value="'.$eventInfo['ID'].'"/>
			';
                }

		if($formType=='firesDash'){
			echo '
                                <form class="col-lg-12 col-md-12 col-sm-12 col-xs-12" name="editEventForm" role="form" action="edit.php?type=firesDash" method="post">
                                        <h4>Edit FireBoard Event</h4>
                                        <label for="editEventForm_Type" class="control-label">Event Type</label>
                                        <select name="editEventForm_Type" id="editEventForm_Type" class="form-control" style="width:50%;min-width:200px;max-width:250px;">';
                        echo '<option value="maint"';
                        if($eventInfo['eventType']=='maint'){
                                echo ' selected';
                        }
                        echo '>Maintenance</option>';
                        echo '<option value="deploy"';
                        if($eventInfo['eventType']=='deploy'){
                                echo ' selected';
                        }
                        echo '>Deployment</option>';
                        echo '<option value="canary"';
                        if($eventInfo['eventType']=='canary'){
                                echo ' selected';
                        }
                        echo '>Canary</option>';
                        echo '<option value="fire"';
                        if($eventInfo['eventType']=='fire'){
                                echo ' selected';
                        }
                        echo '>Fire</option><br>';
                        echo '</select>';
			echo '
                                <label for="editEventForm_Desc" class="control-label">Event Description</label>
                                <input name="editEventForm_Desc" id="editEventForm_Desc" class="form-control" type="text" style="width:85%;min-width:250px;max-width:450px;" placeholder="Description" required value="'.$eventInfo['eventDesc'].'">
                                <label for="editEventForm_Est_Duration" class="control-label">Estimated Duration</label>
                                <input name="editEventForm_Est_Duration" id="editEventForm_Est_Duration" class="form-control" type="text" style="width:50%;min-width:200px;max-width:300px;" required placeholder="30 minutes, 1-2 hours, etc." value="'.$eventInfo['est_duration'].'">
                        ';
			echo '<label><input type="checkbox" name="editEventForm_Concluded" value="1"';
			if($eventInfo['eventActive']=='0'){
				echo ' checked';
			}
			echo '> Event Concluded?</label><br>';
			echo '<input type="hidden" name="editEventForm_ID" value="'.$eventInfo['eventID'].'"/>';
		}
		echo '<input class="btn btn-sm btn-danger form-button" type="button" id="formBackButton_'.$_REQUEST['type'].'" value="Back"><input name="form_timezone" id="form_timezone" type="hidden" value=""><input class="btn btn-sm btn-success form-button" style="margin-left:10px;" type="submit" value="Update Event" /><br><br></form>';
	}
?>

<html>
        <head>
		<?PHP getHtmlHead(); ?>
		<script src="js/jquery.timeentry.js"></script>
        </head>

	 <body>
                <?PHP getHeader(); ?>

                <div id="pageContainer" class="container">
                        <div id="editFormContainer" class="panel panel-default formContainer">
				<div class="panel-body">
	                                <?PHP createEditForm($_REQUEST['type']); ?>
				</div>
                        </div>
                </div>

		<script>
                        $(document).ready(function() {
				$(document).attr('title', $(document).attr('title')+' - Edit Entry');
				var tz = jstz.determine();
				$('#form_timezone').val(tz.name());

                                $('#formBackButton_maint').click(function(){
                                        $(location).attr('href','maintenance.php');
                                });

                                $('#formBackButton_firesDash').click(function(){
                                        $(location).attr('href','firesDash.php');
                                });

			        $('#editMaintForm_DateTimeStart').val(moment.utc($('#editMaintForm_DateTimeStart').val()).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss'));
			        $('#editMaintForm_DateTimeEnd').val(moment.utc($('#editMaintForm_DateTimeEnd').val()).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss'));
        			function datetimeRange(input) {
			                return {minDatetime: (input.id === 'editMaintForm_DateTimeEnd' ?
			                $('#editMaintForm_DateTimeStart').datetimeEntry('getDatetime') : null),
			                maxDatetime: (input.id === 'editMaintForm_DateTimeStart' ?
			                $('#editMaintForm_DateTimeEnd').datetimeEntry('getDatetime') : null)};
			        }

				$('input.datetimeRange').datetimeEntry({spinnerImage: '',beforeShow: datetimeRange,datetimeFormat: 'O/D/Y H:Ma'});
				$('#editMaintForm_CKT').change(function(){
                                        $('#editMaintForm_CKT_ID').val($('#editMaintForm_CKT').val());
                                });
                                $('#editMaintForm_CKT').val($('#editMaintForm_CKT_ID').val());
                        });
                </script>

		<?PHP addFooter(); ?>
        </body>
</html>

