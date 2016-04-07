<?php
	$path=$_SERVER['DOCUMENT_ROOT'];
	require_once $path.'/php/dbconn.php';
	include $path.'/php/common.php';

	$eventCreated = (bool) False;
	
	if ($_REQUEST['message'] == 'fail') {
		$eventCreated = False;
	} else if ($_REQUEST['message'] == 'success') {
		$eventCreated = True;
	}

	if (!empty($_POST)) {
		$db_connection_nocdash = new db_nocdash();
		if($_REQUEST['type']=='maint'){
			$siteList = implode(",",$_POST['createMaintForm_Sites']);
			$result = $db_connection_nocdash->query("INSERT INTO Noc.maintenance (Provider,CKT_ID,Work_Start,Work_End,Duration,Affected_Sites,Provider_Ticket_Num,Work_Type,Work_Description,createdBy,lastModified,lastModifiedBy) VALUES (".$db_connection_nocdash->quote($_POST[createMaintForm_Provider]).",".$db_connection_nocdash->quote($_POST[createMaintForm_CKT]).",CONVERT_TZ(\"".date('Y-m-d H:i:s',strtotime($_POST[createMaintForm_DateTimeStart]))."\",\"".$_POST[form_timezone]."\",\"UTC\"),CONVERT_TZ(\"".date('Y-m-d H:i:s',strtotime($_POST[createMaintForm_DateTimeEnd]))."\",\"".$_POST[form_timezone]."\",\"UTC\"),".$db_connection_nocdash->quote($_POST[createMaintForm_Duration]).",".$db_connection_nocdash->quote($siteList).",".$db_connection_nocdash->quote($_POST[createMaintForm_Provider_Ticket]).",".$db_connection_nocdash->quote($_POST[createMaintForm_Work_Type]).",".$db_connection_nocdash->quote($_POST[createMaintForm_Work_Description]).",".$db_connection_nocdash->quote($_SESSION['displayName']).",NOW(),".$db_connection_nocdash->quote($_SESSION['displayName']).")");

			if($result === false){
				echo '[Error]: Unable to create maintenance event<br>';
				$db_connection_nocdash->error();
			}else{
				header("location:maintenance.php");
			}
		}

		if($_REQUEST['type']=='firesDash'){
			$result = $db_connection_nocdash->query("INSERT INTO Noc.fireBoard (eventType,eventDesc,eventStart,est_duration,eventActive,createdBy,eventLastModified,lastModifiedBy) VALUES (".$db_connection_nocdash->quote($_POST[createEventForm_Type]).",".$db_connection_nocdash->quote($_POST[createEventForm_Desc]).",NOW(),".$db_connection_nocdash->quote($_POST[createEventForm_Est_Duration]).",1,".$db_connection_nocdash->quote($_SESSION['displayName']).",NOW(),".$db_connection_nocdash->quote($_SESSION['displayName']).")");

			if($result === false){
				echo '[Error]: Unable to create fireBoard event<br>';
				$db_connection_nocdash->error();
			}else{
                                header("location:firesDash.php");
			}
		}
	}

	function createForm($formType){
		if ($_REQUEST['message'] == 'fail') {
			$eventCreated = False;
		} else if ($_REQUEST['message'] == 'success') {
			$eventCreated = True;
		}

		if($formType=='maint'){
			echo '
				<form class="col-lg-12 col-md-12 col-sm-12 col-xs-12" name="createMaintForm" role="form" action="create.php?type=maint" method="post">
					<h4>Upcoming Vendor Maintenance</h4>
					<label for="createMaintForm_Provider" class="control-label">Vendor</label>
					<select name="createMaintForm_Provider" id="createMaintForm_Provider" class="form-control" style="width:70%;min-width:200px;max-width:250px;" autofocus>
						<option value="Zayo">Zayo</option>
						<option value="XO">XO</option>
						<option value="Latisys">Latisys</option>
						<option value="Internap">Internap</option>
						<option value="Akamai">Akamai</option>
						<option value="Keynote">Keynote</option>
						<option value="Hibernia">Hibernia</option>
						<option value="Savvis">Savvis</option>
						<option value="Equinix">Equinix</option>
                                                <option value="Other">Other</option>

					</select>
					<label for="createMaintForm_DateTimeStart" class="control-label">Start Time (Local)</label>
					<input name="createMaintForm_DateTimeStart" id="createMaintForm_DateTimeStart" class="form-control datetimeRange" type="text" style="width:70%;min-width:200px;max-width:500px;" placeholder="Maintenance window start in local time" required>
					<label for="createMaintForm_DateTimeEnd" class="control-label">End Time (Local)</label>
					<input name="createMaintForm_DateTimeEnd" id="createMaintForm_DateTimeEnd" class="form-control datetimeRange" type="text" style="width:70%;min-width:200px;max-width:500px;" placeholder="Maintenance window end in local time" required>
					<label for="createMaintForm_Duration" class="control-label">Duration</label>
					<input name="createMaintForm_Duration" id="createMaintForm_Duration" class="form-control" type="text" style="width:70%;min-width:200px;max-width:500px;" placeholder="Days, hours, minutes, etc." required>
					<label for="createMaintForm_Work_Type" class="control-label">Type of Work</label>
					<input name="createMaintForm_Work_Type" id="createMaintForm_Work_Type" class="form-control" type="text" style="width:70%;min-width:200px;max-width:500px;" required>
					<!-- <label for="createMaintForm_CKT-check" class="control-label"><input id="createMaintForm_CKT-check" name="createMaintForm_CKT-check" type="checkbox"> Service Impacting?</label><br> -->
					<label for="createMaintForm_CKT" class="control-label">Affected Circuit</label>
					<select name="createMaintForm_CKT" id="createMaintForm_CKT" class="form-control" style="width:70%;min-width:265px;max-width:275px;">
						<option value="n/a" selected>n/a</option>
						<option value="OGYX/066617//ZYO">ORD&lt; &gt;SJC (OGYX/066617//ZYO)</option>
                                                <option value="OGYX/066625//ZYO">ORD&lt; &gt;IAD (OGYX/066625//ZYO)</option>
                                                <option value="OGYX/066598//ZYO">SJC&lt; &gt;IAD (OGYX/066598//ZYO)</option>
                                                <option value="OBK_AMS_10GE_51">AMS&lt; &gt;ORD (OBK_AMS_10GE_51)</option>
                                                <option value="ASH_AMS_10GE_61">AMS&lt; &gt;IAD (ASH_AMS_10GE_61)</option>
                                                <option value="OGYX/068097//ZYO">ORD&lt; &gt;CHI 1 (OGYX/068097//ZYO)</option>
                                                <option value="OGYX/063471//ZYO">ORD&lt; &gt;CHI 2 (OGYX/063471//ZYO)</option>
                                                <option value="SF/LUYX/819682/TQW">SJ2&lt; &gt;ORD (SF/LUYX/819682/TQW)</option>
                                                <option value="SF/OGYX/993721/TQW">SJ2&lt; &gt;DC6 1 (SF/OGYX/993721/TQW )</option>
                                                <option value="SF/LZYX/868266/TQW">SJ2&lt; &gt;DC6 2 (SF/LZYX/868266/TQW)</option>
                                                <option value="SF/OGXX/922509/TQW">SJ2&lt; &gt;SJC 1 (SF/OGXX/922509/TQW)</option>
                                                <option value="SF/OGYX/922563/TQW">SJ2&lt; &gt;SJC 2 (SF/OGYX/922563/TQW)</option>
                                                <option value="WA/OGGS/923909/TQW">DC6&lt; &gt;IAD 1 (WA/OGGS/923909/TQW)</option>
                                                <option value="WA/OGGS/924165/TQW">DC6&lt; &gt;IAD 2 (WA/OGGS/924165/TQW)</option>
					</select>
					<label for="createMaintForm_CKT_ID" class="control-label">CKT ID</label>
					<input name="createMaintForm_CKT_ID" id="createMaintForm_CKT_ID" class="form-control" type="text" style="width:70%;min-width:175px;max-width:185px;" disabled>
					<label class="control-label">Affected Sites</label><br>
					<label for="createMaintForm_Sites_IAD" class="control-label"><input type="checkbox" name="createMaintForm_Sites[]" id="createMaintForm_Sites_IAD" value="IAD"> IAD</label>
					<label for="createMaintForm_Sites_SJC" class="control-label"><input type="checkbox" name="createMaintForm_Sites[]" id="createMaintForm_Sites_SJC" value="SJC"> SJC</label>
					<label for="createMaintForm_Sites_ORD" class="control-label"><input type="checkbox" name="createMaintForm_Sites[]" id="createMaintForm_Sites_ORD" value="ORD"> ORD</label>
					<label for="createMaintForm_Sites_AMS" class="control-label"><input type="checkbox" name="createMaintForm_Sites[]" id="createMaintForm_Sites_AMS" value="AMS"> AMS</label>
					<label for="createMaintForm_Sites_CHI" class="control-label"><input type="checkbox" name="createMaintForm_Sites[]" id="createMaintForm_Sites_CHI" value="CHI"> CHI</label>
					<label for="createMaintForm_Sites_SJ2" class="control-label"><input type="checkbox" name="createMaintForm_Sites[]" id="createMaintForm_Sites_SJ2" value="SJ2"> SJ2</label>
					<label for="createMaintForm_Sites_DC6" class="control-label"><input type="checkbox" name="createMaintForm_Sites[]" id="createMaintForm_Sites_DC6" value="DC6"> DC6</label><br>
					<label for="createMaintForm_Provider_Ticket" class="control-label">Maintenance Ticket #</label>
					<input name="createMaintForm_Provider_Ticket" id="createMaintForm_Provider_Ticket" class="form-control" type="text" style="width:70%;min-width:200px;max-width:500px;" required>
					<label for="createMaintForm_Work_Description" class="control-label">Description of Work</label>
					<textarea id="createMaintForm_Work_Description" class="form-control" rows="8" name="createMaintForm_Work_Description" required></textarea>
			';
		}

		if($formType=='firesDash'){
			echo '
				<form class="col-lg-12 col-md-12 col-sm-12 col-xs-12" name="createEventForm" role="form" action="create.php?type=firesDash" method="post">
					<h4>Add Event to FireBoard</h4>
					<label for="createEventForm_Type" class="control-label">Event Type</label>
					<select name="createEventForm_Type" id="createEventForm_Type" class="form-control" style="width:50%;min-width:200px;max-width:250px;">
						<option value="maint">Maintenance</option>
						<option value="deploy">Deployment</option>
						<option value="canary">Canary</option>
						<option value="fire">Fire</option>
					</select>
					<label for="createEventForm_Desc" class="control-label">Event Description</label>
					<input name="createEventForm_Desc" id="createEventForm_Desc" class="form-control" type="text" style="width:85%;min-width:250px;max-width:450px;" placeholder="Description" required>
					<label for="createEventForm_Est_Duration" class="control-label">Estimated Duration</label>
					<input name="createEventForm_Est_Duration" id="createEventForm_Est_Duration" class="form-control" type="text" style="width:50%;min-width:200px;max-width:300px;" required placeholder="30 minutes, 1-2 hours, etc.">
			';
		}
		
		if ($eventCreated){
			echo '<div id="createEntrySuccess">New record created.</div>';
		} else {
			//echo '<div>&nbsp;</div>';
		}
		echo '<input type="button" class="btn btn-sm btn-danger form-button" id="formBackButton_'.$_REQUEST['type'].'" value="Back"><input name="form_timezone" id="form_timezone" type="hidden" value=""><input class="btn btn-sm btn-success form-button" style="margin-left:10px;" type="submit" value="Create Event" /><br><br></form>';
	}
?>

<html>
        <head>
		<?PHP getHtmlHead(); ?>

		<script src="js/jquery.timeentry.js"></script>
        </head>

        <body>
		<?PHP getHeader(); ?>

                <div id="pageContainer" class="container" style="max-width:600px;">
			<div id="createFormContainer" class="panel panel-default container" style="max-width:600px;">
				<div class="panel-body">
					<?PHP createForm($_REQUEST['type']); ?>
				</div>
			</div>
		</div>

		<script>
                        $(document).ready(function() {
				$(document).attr('title', $(document).attr('title')+' - New Entry');
                                var t = new Date();
                                var x = t.getTimezoneOffset()/60;

				var tz = jstz.determine();
				$('#form_timezone').val(tz.name());

				/*$('#createMaintForm_CKT-check').on('click', function(){
                                        if($(this).prop("checked")==true){
                                                $("#createMaintForm_CKT").prop('disabled',false);
                                                $("#createMaintForm_Sites_IAD").prop('disabled',false);
                                                $("#createMaintForm_Sites_SJC").prop('disabled',false);
                                                $("#createMaintForm_Sites_ORD").prop('disabled',false);
                                                $("#createMaintForm_Sites_CHI").prop('disabled',false);
                                                $("#createMaintForm_Sites_SJ2").prop('disabled',false);
                                                $("#createMaintForm_Sites_DC6").prop('disabled',false);
                                        }else{
                                                $("#createMaintForm_CKT").prop('disabled',true);
                                                $("#createMaintForm_Sites_IAD").prop('disabled',true);
                                                $("#createMaintForm_Sites_SJC").prop('disabled',true);
                                                $("#createMaintForm_Sites_ORD").prop('disabled',true);
                                                $("#createMaintForm_Sites_CHI").prop('disabled',true);
                                                $("#createMaintForm_Sites_SJ2").prop('disabled',true);
                                                $("#createMaintForm_Sites_DC6").prop('disabled',true);
                                        }
                                });*/


        			function datetimeRange(input) {
			                return {minDatetime: (input.id === 'createMaintForm_DateTimeEnd' ?
			                $('#createMaintForm_DateTimeStart').datetimeEntry('getDatetime') : null),
			                maxDatetime: (input.id === 'createMaintForm_DateTimeStart' ?
			                $('#createMaintForm_DateTimeEnd').datetimeEntry('getDatetime') : null)};
			        }

				$('input.datetimeRange').datetimeEntry({spinnerImage: '',beforeShow: datetimeRange,datetimeFormat: 'O/D/Y H:Ma'});
 
                                $('#createEventForm_TimeETA_Alt').timeEntry();
                                $('#createEventForm_TimeETA').timeEntry({spinnerImage: '', showSeconds: false, show24Hours: true, useMouseWheel: true, defaultTime: '+'+x+'h'}).change(function(){
                                        var d = new Date($('#createEventForm_TimeETA').timeEntry('getTime'));
                                        var e = d.getHours();
                                        d.setHours(e-x);

                                        var altTime = d.getHours()+':'+d.getMinutes()+':'+d.getSeconds();
                                        $('#createEventForm_TimeETA_Alt').timeEntry('setTime', altTime);
                                });
                                $('#createEventForm_DateETA').datepicker();

                                $('#formBackButton_maint').click(function(){
                                        $(location).attr('href','maintenance.php');
                                });

                                $('#formBackButton_firesDash').click(function(){
                                        $(location).attr('href','firesDash.php');
                                });

                                $('#createMaintForm_CKT').change(function(){
                                        $('#createMaintForm_CKT_ID').val($('#createMaintForm_CKT').val());
                                });
                                $('#createMaintForm_CKT_ID').val($('#createMaintForm_CKT').val());
                        });
                </script>

		<?PHP addFooter(); ?>
        </body>
</html>

