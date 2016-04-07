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
			<div class="panel-group" id="emailListContainer">
			</div>
		</div>
	
		<script>
                        function pastEmails(jsonPastEmails){
                                var tz = jstz.determine();
                                console.log(jsonPastEmails);
                                console.log(jsonPastEmails.length);

                                if(jsonPastEmails.length==0){
                                        $('#emailListContainer').append("<div class='panel panel-default'><div class='panel-heading'>Previously sent emails</div><div class='panel-body'>No emails found.</div></div>");
                                } else {
                                        //console.log('else');
                                        for(i=0;i<jsonPastEmails.length;i++) {
                                                var mailRecipients = "<div class='event'>To:&nbsp;"+jsonPastEmails[i].sentTo+"</div>";
                                                if(!jsonPastEmails[i].sentCC==""){
                                                        mailRecipients+="<div class='event'>CC:&nbsp;"+jsonPastEmails[i].sentCC+"</div>";
                                                }
                                                if(!jsonPastEmails[i].sentBCC==""){
                                                        mailRecipients+="<div class='event'>BCC:&nbsp;"+jsonPastEmails[i].sentBCC+"</div>";
                                                }
						//emailBody = jsonPastEmails[i].body.replace(new RegExp('\r?\n','g'),'<br>').replace(new RegExp('\t','g'),'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;').replace(new RegExp('FileSystemSizeUsedAvailUse %Mounted on','g'),'<br><br>FileSystem&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Size&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Used&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Avail&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Use %&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mounted on<br>');
						emailBody = jsonPastEmails[i].body;
						var a = moment.utc(jsonPastEmails[i].sentDate).tz(tz.name()).format('MM/DD/YYYY HH:mm:ss');
						var b = moment(a).from();
						jsonPastEmails[i].sentDate = b;
                                        	$('#emailListContainer').append("<div id='pastEmailPanel_"+jsonPastEmails[i].mailID+"' class='panel panel-default'><div class='panel-heading'><a class='panel-title' data-toggle='collapse' data-parent='#emailListContainer' href='#pastEmail_"+jsonPastEmails[i].mailID+"'>"+jsonPastEmails[i].sentDate+" | "+jsonPastEmails[i].subject+"</a></div><div id='pastEmail_"+jsonPastEmails[i].mailID+"' class='panel-collapse collapse'><div class='panel-body'>"+mailRecipients+"<br>"+emailBody+"</div></div></div>");
                                	}
				}
                        }

                        $(document).ready(function () {
                                $(document).attr('title', $(document).attr('title')+' - View Email');
				$('#nav-active-communication').addClass('active');
                                var jsonEmailData = getJSONData('pastEmails');
                                pastEmails(jsonEmailData);

				var activeEmail = window.location.search;
				activeEmail = activeEmail.split("=");
				activeEmailID = activeEmail[1];
				location.href = '#pastEmailPanel_'+activeEmailID;
				$('#pastEmail_'+activeEmailID).collapse('show');

				setInterval(function(){
					$('#emailListContainer .panel').remove();
                                	jsonEmailData = getJSONData('pastEmails');
                                	pastEmails(jsonEmailData);
                                }, 300000); //refresh every 300 sec
                        });
                </script>
	
		<?PHP addFooter(); ?>
	</body>
</html>
