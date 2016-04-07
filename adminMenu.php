<?PHP
	$path=$_SERVER['DOCUMENT_ROOT'];
	require_once $path.'/php/dbconn.php';
	include $path.'/php/common.php';

        if( $_SESSION['usergroup'] != 'admin' ){ //if user is not an admin, send them to the home page
                header("Location: index.php");
        }

	$validLogin = (bool) True;

	function getDN($ad, $samaccountname, $basedn) {
                $attributes = array('dn');
                $result = ldap_search($ad, $basedn,"(samaccountname={$samaccountname})", $attributes);
                if ($result === FALSE) { return ''; }
                $entries = ldap_get_entries($ad, $result);
                if ($entries['count']>0) { return $entries[0]['dn']; }
                else { return ''; };
        }

        /*
        * This function retrieves and returns CN from given DN
        */
        function getCN($dn) {
                preg_match('/[^,]*/', $dn, $matchs, PREG_OFFSET_CAPTURE, 3);
                return $matchs[0][0];
        }

        /*
        * This function checks group membership of the user, searching only
        * in specified group (not recursively).
        */
        function checkGroup($ad, $userdn, $groupdn) {
                $attributes = array('members');
                $result = ldap_read($ad, $userdn, "(memberof={$groupdn})", $attributes);
                if ($result === FALSE) { return FALSE; };
                $entries = ldap_get_entries($ad, $result);
                return ($entries['count'] > 0);
        }

        /*
        * This function checks group membership of the user, searching
        * in specified group and groups which is its members (recursively).
        */
        function checkGroupEx($ad, $userdn, $groupdn) {
                $attributes = array('memberof');
                $result = ldap_read($ad, $userdn, '(objectclass=*)', $attributes);
                if ($result === FALSE) { return FALSE; };
                $entries = ldap_get_entries($ad, $result);
                if ($entries['count'] <= 0) { return FALSE; };
                if (empty($entries[0]['memberof'])) { return FALSE; } else {
                        for ($i = 0; $i < $entries[0]['memberof']['count']; $i++) {
                                if ($entries[0]['memberof'][$i] == $groupdn) { return TRUE; }
                                elseif (checkGroupEx($ad, $entries[0]['memberof'][$i], $groupdn)) { return TRUE; };
                        };
                };
                return FALSE;
        }

	function lookupUser($userQuery) {
		$user = $_SESSION['username'];
                $password = $_SESSION['password'];
                $host = 'ord-ldap';
                $domain = 'corp.valueclick.com';
                //$basedn = 'OU=Chicago,OU=UnitedStates,OU=NorthAmerica,OU=ValueClick,DC=corp,DC=valueclick,DC=com';
                $basedn = 'OU=ValueClick,DC=corp,DC=valueclick,DC=com';
		$search_filter = "(&(objectClass=user)(objectcategory=person)(|(sAMAccountName=*$userQuery*)(givenname=*".$userQuery."*)(sn=*".$userQuery."*)))";
		
		//var_dump($search_filter);exit();

                $ad = ldap_connect("ldap://{$host}.{$domain}") or die('Could not connect to LDAP server.');
                ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);
                ldap_set_option($ad, LDAP_OPT_REFERRALS, 0);
                $ldapbind = ldap_bind($ad, "{$user}@{$domain}", $password);// or die('Could not bind to AD.');
                //$userdn = getDN($ad, $user, $basedn);

                if ($ldapbind){ //able to query AD with user login creds
			//echo "LDAP bind successful...<br /><br />";
        		//$result = ldap_search($ad,$basedn, "(cn=*)") or die ("Error in search query: ".ldap_error($ad));
        		$result = ldap_search($ad,$basedn,$search_filter) or die ("Error in search query: ".ldap_error($ad));
		        $data = ldap_get_entries($ad, $result);
        
		        // SHOW ALL DATA
        		//echo '<h1>Dump all data</h1><pre>';
		        //print_r($data);    
        		//echo '</pre>';
        
		        // iterate over array and print data for each entry
        		//echo '<h1>Show me the users</h1>';
			echo '<h4>Search Results</h4>';
			echo '<form name="ADUsersSearchResult" role="form" action="adminMenu.php" method="post">';
	        	echo "Users Found: " . ldap_count_entries($ad, $result)."<br>";

			$db_connection_nocdash = new db_nocdash();
		        for ($i=0; $i<$data["count"]; $i++) {
        			//echo "dn is: ". $data[$i]["dn"] ."<br />";
				echo "<input type='radio' id='ADUserResult' name='ADUserResult' value='".$data[$i]["samaccountname"][0]."' required>&nbsp;&nbsp;".$data[$i]["cn"][0]." (".$data[$i]["samaccountname"][0].") - ";
				$result = $db_connection_nocdash->query("SELECT usergroup FROM Noc.NocDashUsers WHERE username=".$db_connection_nocdash->quote($data[$i]["samaccountname"][0]));
				if($result->num_rows > 0){
					$row = $result->fetch_assoc();
                                	echo "Access level: ".$row['usergroup'];
                        	}else{
                                	echo "Access level: user";
                        	}
				echo "<br>";
	        	}
        		// print number of entries found
				echo "<br>Set permission level:<br><input type='radio' id='ADUserPermission' name='ADUserPermission' value='admin' required>&nbsp;&nbsp;Admin<br>";
				echo "<input type='radio' id='ADUserPermission' name='ADUserPermission' value='powuser' required>&nbsp;&nbsp;Power User<br>";
				echo "<input type='radio' id='ADUserPermission' name='ADUserPermission' value='dev' required>&nbsp;&nbsp;Developer<br>";
				echo "<input type='radio' id='ADUserPermission' name='ADUserPermission' value='exec' required>&nbsp;&nbsp;Executive<br>";
				echo "<input type='radio' id='ADUserPermission' name='ADUserPermission' value='user' required>&nbsp;&nbsp;User (default)<br><br>";
	        	echo '<input class="btn btn-sm btn-primary" type="submit" value="Set Permissions">';
			echo '</form>';
	    	} else {
        		echo "LDAP bind failed...";
	    	}
		
                ldap_unbind($ad);
        }

	function loadAdminBody(){
		$db_connection_nocdash = new db_nocdash();
		echo '<div id="pageContainer" class="container">
        	                <div id="lookupUserForm" class="panel panel-default">
                	                <div class="panel-body">
	                                        <form class="col-lg-6 col-sm-6 col-xs-12" name="searchADUsers" role="form" action="adminMenu.php" method="post">
	                                 	       <h4>NocDash User Permissions</h4>
	        	                                <label for="lookupUserForm-1" class="control-label">User Search</label>
        	        	                        <input id="lookupUserForm-1" name="lookupUserForm-1" class="form-control" placeholder="John Smith" autofocus required />
	        	                                <input class="btn btn-sm btn-primary" type="submit" value="Search">
        	                                </form>
						<div class="col-lg-6 col-sm-6 col-xs-12">';
		if( isset($_POST['lookupUserForm-1']) ){
			//echo 'search conducted:'.$_POST['lookupUserForm-1'].'<br>';
			lookupUser($_POST['lookupUserForm-1']);
		}
	
		if( isset($_POST['ADUserResult']) ){
			$result = $db_connection_nocdash->query("SELECT * FROM Noc.NocDashUsers WHERE username=".$db_connection_nocdash->quote($_POST['ADUserResult']));
			if($result->num_rows > 0){
				//echo $_POST['ADUserResult'].' already exists in the database';
				$result = $db_connection_nocdash->query("UPDATE Noc.NocDashUsers SET usergroup=".$db_connection_nocdash->quote($_POST['ADUserPermission'])." WHERE username=".$db_connection_nocdash->quote($_POST['ADUserResult']));
			}else{
				//echo $_POST['ADUserResult'].' is NOT in the database';
				$result = $db_connection_nocdash->query("INSERT INTO Noc.NocDashUsers (username,usergroup) VALUES (".$db_connection_nocdash->quote($_POST['ADUserResult']).",".$db_connection_nocdash->quote($_POST['ADUserPermission']).")");
			}
			if($result === false){
				echo 'There was an error updating the user permission database...';
			}else{
				echo 'Permissions sucessfully updated in the database.<br>User: <b>'.$_POST['ADUserResult'].'</b><br>Access Level: <b>'.$_POST['ADUserPermission'].'</b>';
			}
		}

		echo '
						</div>
                	                </div>
	                        </div>
        	                <div id="roleAccessForm" class="panel panel-default">
                	                <div class="panel-body">
	                                        <form name="setRoleFeatures" role="form" action="adminMenu.php" method="post">
							<h4>Enable/Disable Role Features</h4>
							<div class="panel panel-default col-lg-3 col-sm-6" style="padding-left:0px;padding-right:0px;">
								<div class="panel-heading">Power Users</div>
								<div class="panel-body" style="padding-top:0px;padding-bottom:0px;">
									<div class="checkbox">
									<input id="setRoleFeatures_powuser-1" name="setRoleFeatures_powuser-1" type="checkbox">
	        			                                <label for="setRoleFeatures_powuser-1" class="control-label">Jira Queues</label></div>
									<div class="checkbox"><input id="setRoleFeatures_powuser-2" name="setRoleFeatures_powuser-2" type="checkbox">
	        	                        		        <label for="setRoleFeatures_powuser-2" class="control-label">Turnover Board</label></div>
									<div class="checkbox"><input id="setRoleFeatures_powuser-3" name="setRoleFeatures_powuser-3" type="checkbox">
	        	                        		        <label for="setRoleFeatures_powuser-3" class="control-label">View Ganglia Alerts</label></div>
									<div class="checkbox"><input id="setRoleFeatures_powuser-4" name="setRoleFeatures_powuser-4" type="checkbox">
	        	                        		        <label for="setRoleFeatures_powuser-4" class="control-label">View Past Alerts</label></div>
									<div class="checkbox"><input id="setRoleFeatures_powuser-5" name="setRoleFeatures_powuser-5" type="checkbox">
	        	                        		        <label for="setRoleFeatures_powuser-5" class="control-label">View FiresDash</label></div>
									<div class="checkbox"><input id="setRoleFeatures_powuser-6" name="setRoleFeatures_powuser-6" type="checkbox">
	        	                        		        <label for="setRoleFeatures_powuser-6" class="control-label">Create/Edit FireBoard Events</label></div>
									<div class="checkbox"><input id="setRoleFeatures_powuser-7" name="setRoleFeatures_powuser-7" type="checkbox">
	        	                        		        <label for="setRoleFeatures_powuser-7" class="control-label">View Exec Dash</label></div>
									<div class="checkbox"><input id="setRoleFeatures_powuser-8" name="setRoleFeatures_powuser-8" type="checkbox">
	        	                        		        <label for="setRoleFeatures_powuser-8" class="control-label">Manage Users</label></div>
								</div>
							</div>
							<div class="panel panel-default col-lg-3 col-sm-6" style="padding-left:0px;padding-right:0px;">
								<div class="panel-heading">Developers</div>
								<div class="panel-body" style="padding-top:0px;padding-bottom:0px;">
									<div class="checkbox">
									<input id="setRoleFeatures_dev-1" name="setRoleFeatures_dev-1" type="checkbox">
	        			                                <label for="setRoleFeatures_dev-1" class="control-label">Jira Queues</label></div>
									<div class="checkbox"><input id="setRoleFeatures_dev-2" name="setRoleFeatures_dev-2" type="checkbox">
	        	                        		        <label for="setRoleFeatures_dev-2" class="control-label">Turnover Board</label></div>
									<div class="checkbox"><input id="setRoleFeatures_dev-3" name="setRoleFeatures_dev-3" type="checkbox">
	        	                        		        <label for="setRoleFeatures_dev-3" class="control-label">View Ganglia Alerts</label></div>
									<div class="checkbox"><input id="setRoleFeatures_dev-4" name="setRoleFeatures_dev-4" type="checkbox">
	        	                        		        <label for="setRoleFeatures_dev-4" class="control-label">View Past Alerts</label></div>
									<div class="checkbox"><input id="setRoleFeatures_dev-5" name="setRoleFeatures_dev-5" type="checkbox">
	        	                        		        <label for="setRoleFeatures_dev-5" class="control-label">View FiresDash</label></div>
									<div class="checkbox"><input id="setRoleFeatures_dev-6" name="setRoleFeatures_dev-6" type="checkbox">
	        	                        		        <label for="setRoleFeatures_dev-6" class="control-label">Create/Edit FireBoard Events</label></div>
									<div class="checkbox"><input id="setRoleFeatures_dev-7" name="setRoleFeatures_dev-7" type="checkbox">
	        	                        		        <label for="setRoleFeatures_dev-7" class="control-label">View Exec Dash</label></div>
									<div class="checkbox"><input id="setRoleFeatures_dev-8" name="setRoleFeatures_dev-8" type="checkbox">
	        	                        		        <label for="setRoleFeatures_dev-8" class="control-label">Manage Users</label></div>
								</div>
							</div>
							<div class="panel panel-default col-lg-3 col-sm-6" style="padding-left:0px;padding-right:0px;">
								<div class="panel-heading">Executive</div>
								<div class="panel-body" style="padding-top:0px;padding-bottom:0px;">
									<div class="checkbox">
									<input id="setRoleFeatures_exec-1" name="setRoleFeatures_exec-1" type="checkbox">
	        			                                <label for="setRoleFeatures_exec-1" class="control-label">Jira Queues</label></div>
									<div class="checkbox"><input id="setRoleFeatures_exec-2" name="setRoleFeatures_exec-2" type="checkbox">
	        	                        		        <label for="setRoleFeatures_exec-2" class="control-label">Turnover Board</label></div>
									<div class="checkbox"><input id="setRoleFeatures_exec-3" name="setRoleFeatures_exec-3" type="checkbox">
	        	                        		        <label for="setRoleFeatures_exec-3" class="control-label">View Ganglia Alerts</label></div>
									<div class="checkbox"><input id="setRoleFeatures_exec-4" name="setRoleFeatures_exec-4" type="checkbox">
	        	                        		        <label for="setRoleFeatures_exec-4" class="control-label">View Past Alerts</label></div>
									<div class="checkbox"><input id="setRoleFeatures_exec-5" name="setRoleFeatures_exec-5" type="checkbox">
	        	                        		        <label for="setRoleFeatures_exec-5" class="control-label">View FiresDash</label></div>
									<div class="checkbox"><input id="setRoleFeatures_exec-6" name="setRoleFeatures_exec-6" type="checkbox">
	        	                        		        <label for="setRoleFeatures_exec-6" class="control-label">Create/Edit FireBoard Events</label></div>
									<div class="checkbox"><input id="setRoleFeatures_exec-7" name="setRoleFeatures_exec-7" type="checkbox">
	        	                        		        <label for="setRoleFeatures_exec-7" class="control-label">View Exec Dash</label></div>
									<div class="checkbox"><input id="setRoleFeatures_exec-8" name="setRoleFeatures_exec-8" type="checkbox">
	        	                        		        <label for="setRoleFeatures_exec-8" class="control-label">Manage Users</label></div>
								</div>
							</div>
							<div class="panel panel-default col-lg-3 col-sm-6" style="padding-left:0px;padding-right:0px;">
								<div class="panel-heading">Users</div>
								<div class="panel-body" style="padding-top:0px;padding-bottom:0px;">
									<div class="checkbox">
									<input id="setRoleFeatures_user-1" name="setRoleFeatures_user-1" type="checkbox">
	        			                                <label for="setRoleFeatures_user-1" class="control-label">Jira Queues</label></div>
									<div class="checkbox"><input id="setRoleFeatures_user-2" name="setRoleFeatures_user-2" type="checkbox">
	        	                        		        <label for="setRoleFeatures_user-2" class="control-label">Turnover Board</label></div>
									<div class="checkbox"><input id="setRoleFeatures_user-3" name="setRoleFeatures_user-3" type="checkbox">
	        	                        		        <label for="setRoleFeatures_user-3" class="control-label">View Ganglia Alerts</label></div>
									<div class="checkbox"><input id="setRoleFeatures_user-4" name="setRoleFeatures_user-4" type="checkbox">
	        	                        		        <label for="setRoleFeatures_user-4" class="control-label">View Past Alerts</label></div>
									<div class="checkbox"><input id="setRoleFeatures_user-5" name="setRoleFeatures_user-5" type="checkbox">
	        	                        		        <label for="setRoleFeatures_user-5" class="control-label">View FiresDash</label></div>
									<div class="checkbox"><input id="setRoleFeatures_user-6" name="setRoleFeatures_user-6" type="checkbox">
	        	                        		        <label for="setRoleFeatures_user-6" class="control-label">Create/Edit FireBoard Events</label></div>
									<div class="checkbox"><input id="setRoleFeatures_user-7" name="setRoleFeatures_user-7" type="checkbox">
	        	                        		        <label for="setRoleFeatures_user-7" class="control-label">View Exec Dash</label></div>
									<div class="checkbox"><input id="setRoleFeatures_user-8" name="setRoleFeatures_user-8" type="checkbox">
	        	                        		        <label for="setRoleFeatures_user-8" class="control-label">Manage Users</label></div>
								</div>
							</div>
							<div id="submitDiv" style="text-align:right;margin-right:25px;margin-bottom:10px;"><input class="btn btn-sm btn-primary" style="margin-top:10px;margin-right:-10px;" type="submit" value="Save"></div>
						</form>
					</div>
				</div>
	                </div>
		';
	}
?>

<!DOCTYPE HTML>
<html>
	<head>
		<?PHP getHtmlHead(); ?>
		<script>
			$(document).ready(function () {
				$(document).attr('title', $(document).attr('title')+' - Admin Menu');
				setInterval(function(){
				
				}, 60000);
			});
		</script>
	</head>

	<body>
		<?PHP getHeader(); ?>
		
		<?PHP loadAdminBody(); ?>

		<?PHP addFooter(); ?>
	</body>
</html>
