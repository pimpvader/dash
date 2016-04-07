<?PHP
	$path=$_SERVER['DOCUMENT_ROOT'];
	require_once $path.'/php/dbconn.php';
	include $path.'/php/common.php';

	$validLogin = (bool) True;
	session_start();
	//if (isset($_GET['redir'])){$lastRequest = $_GET['redir'];}
	
	if (!empty($_POST)) {
		/*
		* This function searchs in LDAP tree ($ldapconn -LDAP link identifier)
		* entry specified by samaccountname and returns its DN or epmty
		* string on failure.
		*/
		function getDN($ldapconn, $samaccountname, $basedn) {
	    		$attributes = array('dn');
    			$result = ldap_search($ldapconn, $basedn,"(samaccountname={$samaccountname})", $attributes);
			if ($result === FALSE) { return ''; }
			$entries = ldap_get_entries($ldapconn, $result);
			if ($entries['count']>0) { return $entries[0]['dn']; }
			else { return ''; };
		}

		/*
		* This function retrieves and returns CN from given DN
		*/
		function getCN($dn) {
			preg_match('/[^,]*/', $dn, $match, PREG_OFFSET_CAPTURE, 3);
			return $match[0][0];
		}

		/*
		* This function checks group membership of the user, searching only
		* in specified group (not recursively).
		*/
		function checkGroup($ldapconn, $userdn, $groupdn) {
			$attributes = array('members');
			$result = ldap_read($ldapconn, $userdn, "(memberof={$groupdn})", $attributes);
			if ($result === FALSE) { return FALSE; };
			$entries = ldap_get_entries($ldapconn, $result);
			return ($entries['count'] > 0);
		}

		/*
		* This function checks group membership of the user, searching
		* in specified group and groups which is its members (recursively).
		*/
		function checkGroupEx($ldapconn, $userdn, $groupdn) {
			$attributes = array('memberof');
			$result = ldap_read($ldapconn, $userdn, '(objectclass=*)', $attributes);
			if ($result === FALSE) { return FALSE; };
			$entries = ldap_get_entries($ldapconn, $result);
			if ($entries['count'] <= 0) { return FALSE; };
			if (empty($entries[0]['memberof'])) { return FALSE; } else {
				for ($i = 0; $i < $entries[0]['memberof']['count']; $i++) {
					if ($entries[0]['memberof'][$i] == $groupdn) { return TRUE; }
					elseif (checkGroupEx($ldapconn, $entries[0]['memberof'][$i], $groupdn)) { return TRUE; };
				};
			};
			return FALSE;
		}
	
		function verifyLogin($loginUsername, $loginPassword) {
			$user = trim($loginUsername);
			$password = trim($loginPassword);
			if( empty($user) || empty($password) ){return False;}

			$host = 'ord-ldap';
			$domain = 'corp.valueclick.com';
			$basedn = 'dc=corp,dc=valueclick,dc=com';
			$group = 'S_APP_OpenDCIM'; //Active Directory security group that controls access

			$ldapconn = ldap_connect("ldap://{$host}.{$domain}") or die('Could not connect to LDAP server.');
			ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

			if ($ldapconn){
				//echo 'LDAP connection success<br><br>';
				$ldapbind = ldap_bind($ldapconn, "{$user}@{$domain}", $password);// or die('Could not bind to AD.');
				$userdn = getDN($ldapconn, $user, $basedn);
				
				if ($ldapbind){ //able to query AD with user login creds
					//echo 'LDAP bind success<br><br>';
					//if (checkGroupEx($ldapconn, $userdn, getDN($ldapconn, $group, $basedn))) {
					if ( checkGroup($ldapconn, $userdn, getDN($ldapconn, $group, $basedn)) ) { //check if user is a member of $group
						echo "You're authorized as ".getCN($userdn);
						$_SESSION['username'] = $user;
						$_SESSION['password'] = $password;
						$_SESSION['displayName'] = getCN($userdn);

						if(!empty($_GET['redir'])){
							header("Location: http://nocdash.dc.dotomi.net".$_GET['redir']);
						}else{
							header("Location: http://nocdash.dc.dotomi.net/jira.php");
						}
						
						ldap_unbind($ldapconn);
						return True;
					} else {
						echo 'Authorization failed.  Not a member of AD security group: '.$group.'.';
						ldap_unbind($ldapconn);
					}
				} else { //user login failed
					//echo 'LDAP bind fail<br><br>';
					ldap_unbind($ldapconn);
					return False;
				}
			}
		}
		
		$db_connection_nocdash = new db_nocdash();
		$loginUsername = $db_connection_nocdash->sanitize($_POST['login-un']);
		$loginPassword = $db_connection_nocdash->sanitize($_POST['login-pw']);
		
		$validLogin = verifyLogin($loginUsername, $loginPassword);
	}
	session_write_close();
?>

<!DOCTYPE HTML>
<html>
	<head>
		<?PHP getHtmlHead(); ?>
		<link href="css/login.css" rel="stylesheet">
	</head>

	<body>
		<?PHP getHeader(); ?>
		<div id="pageContainer" class="container">
			<form name="login-form" action="<?PHP echo $_SERVER['REQUEST_URI']; ?>" method="post" class="form-signin" role="form">
				<h2 class="form-signin-heading">NOC Dash Login</h2>
				<input name="login-un" type="text" class="form-control" placeholder="Username" required autofocus>
				<input name="login-pw" type="password" class="form-control" placeholder="Password" required>
				<!--
				<label class="checkbox">
					<input type="checkbox" value="remember-me"> Remember me
				</label>
				-->

				<?PHP
					if (!$validLogin) {
						echo '<div class="alert alert-danger">Invalid username or password.</div>';
					}
				?>
				<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
			</form>
		</div>
	</body>
</html>
