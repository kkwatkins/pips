<?php
include './plainheader.php';

function confirmUser($username, $password){
   /* Add slashes if necessary (for query) */
   if(!get_magic_quotes_gpc()) {
	$username = addslashes($username);
   }

   /* Verify that user is in database */
   $q = "select password from authenticate where username = '$username'";
   $result = mysql_query($q);
   if(!$result || (mysql_numrows($result) < 1)){
      return 1; //Indicates username failure
   }

   /* Retrieve password from result, strip slashes */
   $dbarray = mysql_fetch_array($result);
   $dbarray['password']  = stripslashes($dbarray['password']);
   $password = stripslashes($password);

   /* Validate that password is correct */
   if($password == $dbarray['password']){
      return 0; //Success! Username and password confirmed
   }
   else{
      return 2; //Indicates password failure
   }
}

function checkLogin(){
   /* Check if user has been remembered */
   if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookpass'])){
      $_SESSION['username'] = $_COOKIE['cookname'];
      $_SESSION['password'] = $_COOKIE['cookpass'];
   }

   /* Username and password have been set */
   if(isset($_SESSION['username']) && isset($_SESSION['password'])){
      /* Confirm that username and password are valid */
      if(confirmUser($_SESSION['username'], $_SESSION['password']) != 0){
         /* Variables are incorrect, user not logged in */
         unset($_SESSION['username']);
         unset($_SESSION['password']);
         return false;
      }
      return true;
   }
   /* User not logged in */
   else{
      return false;
   }
}

function displayLogin(){
   global $logged_in;
   if($logged_in){
   /*
      echo "<h1>Logged In!</h1>";
      echo "Welcome <b>$_SESSION[username]</b>, you are logged in. <a href=\"logout.php\">Logout</a>";
      */
   }
   else{
   		switch($_GET['s']) {
			default:
			case 'li':
				echo '<br><br><br><br><br>';
				echo '<form method="post" action="./index.php">';
				echo '<table width="800" cellpadding="35">';
				echo '<tr>';
				echo '<td bgcolor="#FFFFFF" height="300" width="375" valign="middle">';
				echo '<center>';
				echo '<img width="350" src="./assets/washulogocentered.jpg">';
				echo '</center>';
				echo '<br><br>';
				echo '<br><br>';
				echo '</td>';
				echo '<td bgcolor="#EEEEEE" height=300" width="225" valign="middle">';
				echo '<font color="#0096D8" size=3 face="Verdana">You must have an account to access this part of the site.<br><br></font>';
			//echo '<font color="#0096D8" size=3 face="Verdana">	The Assessment Center is currently closed for updates. Please check back later.<br><br></font>';
			
				echo '<font color="#131413">Username<br>';
				echo '<input type="text" title="Enter your Username" name="user" size="30"><br><br>';
				echo 'Password<br>';
				echo '<input type="password" title="Enter your Password" name="pass" size="30"><br>';
				echo '<input type="checkbox" name="remember"><font size="2">Remember me next time<br><Br>';
				echo '<input type="submit" name="sublogin" value="Login">';
				echo '<br><br>';
				echo '<a href="register.php">Create an account</a> | ';
				echo '<a href="index.php?s=pw">Forgot Password?</a>';			
				echo '</td>';
				echo '</tr>';
				echo '</table>';
				echo '</form>';	
				echo '<br>';
			break;
			case 'rs':
					if(isset($_POST['subnewpass'])) {
						$query = 'UPDATE authenticate SET password = "'.md5($_POST['newpass']).'" WHERE md5(username) = "'.$_GET['u'].'"';				
						mysql_query($query) or die(mysql_error());
						
						echo '<br><br><br><br><br>';
						echo '<table width="800" cellpadding="35">';
						echo '<tr>';
						echo '<td bgcolor="#FFFFFF" height="300" width="375" valign="middle">';
						echo '<center>';
						echo '<img width="350" src="./assets/washulogocentered.jpg">';
						echo '</center>';
						echo '<br><br>';
						echo '<br><br>';
						echo '</td>';
						echo '<td bgcolor="#EEEEEE" height=300" width="225" valign="middle">';
						echo '<font color="#0096D8" size=3 face="Verdana"><br><br></font>';
					//echo '<font color="#0096D8" size=3 face="Verdana">	The Assessment Center is currently closed for updates. Please check back later.<br><br></font>';
					
						echo '<font color="#131413">Success! <a href="index.php">Please return to login.</a>.<br>';
						echo '</td>';
						echo '</tr>';
						echo '</table>';
						echo '<br>';							
					} else {
						echo '<br><br><br><br><br>';
						echo '<form method="POST" action="index.php?s=rs&u='.$_GET['u'].'">';
						echo '<table width="800" cellpadding="35">';
						echo '<tr>';
						echo '<td bgcolor="#FFFFFF" height="300" width="375" valign="middle">';
						echo '<center>';
						echo '<img width="350" src="./assets/washulogocentered.jpg">';
						echo '</center>';
						echo '<br><br>';
						echo '<br><br>';
						echo '</td>';
						echo '<td bgcolor="#EEEEEE" height=300" width="225" valign="middle">';
						echo '<font color="#0096D8" size=3 face="Verdana">Enter a new password for your account<br><br></font>';
					//echo '<font color="#0096D8" size=3 face="Verdana">	The Assessment Center is currently closed for updates. Please check back later.<br><br></font>';
					
						echo '<font color="#131413">New Password<br>';
						echo '<input type="password" title="Enter your Password" name="newpass" size="30"><br><br>';
						echo '<input type="submit" name="subnewpass" value="Submit">';			
						echo '</td>';
						echo '</tr>';
						echo '</table>';
						echo '</form>';	
						echo '<br>';	
				}										
			break;
			case 'pw':
				if(isset($_POST['subreset'])) {
					$query = 'SELECT username, password FROM authenticate WHERE username = "'.strtolower($_POST['user']).'"';
					$result = mysql_query($query) or die(mysql_error());
					
					if(mysql_numrows($result) == 1) {
						$row = mysql_fetch_array($result);
						$to = $row['username'];
						$subject = "Assessment Center - Password Reset";
						$messageline1 = "Click the following link (or paste into browser) to reset your password\r\n\r\n:";
						$messageline2 = "http://pips.washuresearch.org/index.php?s=rs&u=".md5($_POST['user']);
						$body = $messageline1.$messageline2;
						$email_sender = "knightap@wustl.edu";
						$email_return_to = "knightap@wustl.edu";
						$email_client = "PHP/".phpversion();
						$email_header = "From: ".$email_sender."\r\n";
						$email_header.= "Reply-To: ".$email_return_to."\r\n";
						$email_header.= "X-Mailer: ".$email_client."\r\n";
						$changedreturnpath = "-fknightap@wustl.edu";
						mail($to, $subject, $body, $email_header, $changedreturnpath);								
//						echo $body.'<BR>';	
				
						echo '<br><br><br><br><br>';
						echo '<table width="800" cellpadding="35">';
						echo '<tr>';
						echo '<td bgcolor="#FFFFFF" height="300" width="375" valign="middle">';
						echo '<center>';
						echo '<img width="350" src="./assets/washulogocentered.jpg">';
						echo '</center>';
						echo '<br><br>';
						echo '<br><br>';
						echo '</td>';
						echo '<td bgcolor="#EEEEEE" height=300" width="225" valign="middle">';
						echo '<font color="#0096D8" size=3 face="Verdana">Your password has been sent. Please check your email and <a href="index.php">return to the login page</a>.<br><br></font>';
					//echo '<font color="#0096D8" size=3 face="Verdana">	The Assessment Center is currently closed for updates. Please check back later.<br><br></font>';
					
						echo '</td>';
						echo '</tr>';
						echo '</table>';
						echo '<br>';	
					} else {
						echo '<br><br><br><br><br>';
						echo '<table width="800" cellpadding="35">';
						echo '<tr>';
						echo '<td bgcolor="#FFFFFF" height="300" width="375" valign="middle">';
						echo '<center>';
						echo '<img width="350" src="./assets/washulogocentered.jpg">';
						echo '</center>';
						echo '<br><br>';
						echo '<br><br>';
						echo '</td>';
						echo '<td bgcolor="#EEEEEE" height=300" width="225" valign="middle">';
						echo '<font color="#0096D8" size=3 face="Verdana">This username is not valid.<a href="index.php?s=pw">Try again</a>.<br><br></font>';
					//echo '<font color="#0096D8" size=3 face="Verdana">	The Assessment Center is currently closed for updates. Please check back later.<br><br></font>';
					
						echo '</td>';
						echo '</tr>';
						echo '</table>';						
					}
				} else {
				
				
					echo '<br><br><br><br><br>';
					echo '<form method="POST" action="index.php?s=pw">';
					echo '<table width="800" cellpadding="35">';
					echo '<tr>';
					echo '<td bgcolor="#FFFFFF" height="300" width="375" valign="middle">';
					echo '<center>';
					echo '<img width="350" src="./assets/washulogocentered.jpg">';
					echo '</center>';
					echo '<br><br>';
					echo '<br><br>';
					echo '</td>';
					echo '<td bgcolor="#EEEEEE" height=300" width="225" valign="middle">';
					echo '<font color="#0096D8" size=3 face="Verdana">Enter your username (wustl.edu email) and your password will be sent to you.<br><br></font>';
				//echo '<font color="#0096D8" size=3 face="Verdana">	The Assessment Center is currently closed for updates. Please check back later.<br><br></font>';
				
					echo '<font color="#131413">Username<br>';
					echo '<input type="text" title="Enter your Username" name="user" size="30"><br><br>';
					echo '<input type="submit" name="subreset" value="Submit">';			
					echo '</td>';
					echo '</tr>';
					echo '</table>';
					echo '</form>';	
					echo '<br>';
				}					
			
			break;
		}			
	}
}

if(isset($_POST['sublogin'])){
   /* Check that all fields were typed in */
   if(!$_POST['user'] || !$_POST['pass']){
      die('<center><br><br><br><div id="sectioncontent">You didn\'t fill in a required field.<br><br><a href="./index.php">Re-enter your information</a>.</div></center>');
   }
   /* Spruce up username, check length */
   $_POST['user'] = trim($_POST['user']);
   if(strlen($_POST['user']) > 30){
      die('<center><br><br><br><div id="sectioncontent">Sorry, the username must be less than 30 characters.<br><br><a href="./index.php">Please try again</a>.</div></center>');
   }

   /* Checks that username is in database and password is correct */
   $md5pass = md5($_POST['pass']);
   $result = confirmUser($_POST['user'], $md5pass);

   /* Check error codes */
   if($result == 1){
      die('<center><br><br><br><div id="sectioncontent">Credentials invalid.<br><br><a href="./index.php">Please try again</a>.</div></center>');
   }
   else if($result == 2){
      die('<center><br><br><br><div id="sectioncontent">Credentials invalid.<br><br><a href="./index.php">Please try again</a>.</div></center>');
   }

   /* Username and password correct, register session variables */
   $_POST['user'] = stripslashes($_POST['user']);
   $_SESSION['username'] = $_POST['user'];
   $_SESSION['password'] = $md5pass;

   /**
    * This is the cool part: the user has requested that we remember that
    * he's logged in, so we set two cookies. One to hold his username,
    * and one to hold his md5 encrypted password. We set them both to
    * expire in 100 days. Now, next time he comes to our site, we will
    * log him in automatically.
    */
   if(isset($_POST['remember'])){
      setcookie("cookname", $_SESSION['username'], time()+60*60*24*100, "/");
      setcookie("cookpass", $_SESSION['password'], time()+60*60*24*100, "/");
   }

   /* Quick self-redirect to avoid resending data on refresh */
   echo "<meta http-equiv=\"Refresh\" content=\"0;url=$HTTP_SERVER_VARS[PHP_SELF]\">";
   return;
}

/* Sets the value of the logged_in variable, which can be used in your code */
$logged_in = checkLogin();
?>
