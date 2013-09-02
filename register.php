<?php
include './plainheader.php';
function usernameTaken($username){
   if(!get_magic_quotes_gpc()){
      $username = addslashes($username);
   }
   $q = "SELECT username FROM authenticate WHERE username = '$username'";
   $result = mysql_query($q);
   return (mysql_numrows($result) > 0);
}

function addNewUser($username, $password){
   global $conn;
   $q = "INSERT INTO authenticate VALUES ('$username', '$password')";
   return mysql_query($q);
}


function displayStatus(){
   $uname = $_SESSION['reguname'];
   if($_SESSION['regresult']){
	   	$regmsg = '<br><br><br><center><div class="sectioncontents">Registration Success<br><br>You may now <a href="index.php" title="Login">log in</a>.</div></center>';
   }
   else{
	   	$regmsg = '<br><br><br><center><div class="sectioncontents">Registration Failure<br><br>Something went wrong - <a href="register.php">please try again</a>.</div></center>';
   }
   unset($_SESSION['reguname']);
   unset($_SESSION['registered']);
   unset($_SESSION['regresult']);
	return $regmsg;
}

if(isset($_SESSION['registered'])){
	echo '<html><title>Registration Page</title><body>';
	$regmsg = displayStatus();
	echo $regmsg;
	echo '</body>';
	echo '</html>';
   return;
}


/**
 * Determines whether or not to show to sign-up form
 * based on whether the form has been submitted, if it
 * has, check the database for consistency and create
 * the new account.
 */
if(isset($_POST['subjoin'])){
   /* Make sure all fields were entered */
   if(!$_POST['user'] || !$_POST['pass']){
      die('<center><br><br><br><div id="sectioncontent">You didn\'t fill in a required field.<br><br><a href="./register.php">Re-enter your information</a>.</div></center>');
   }

   /* Spruce up username, check length */
   $_POST['user'] = trim($_POST['user']);
   if(strlen($_POST['user']) > 30){
      die('<center><br><br><br><div id="sectioncontent">Sorry, the username must be less than 30 characters.<br><br><a href="./register.php">Please try again</a>.</div></center>');
   }

   /* Check if username is already in use */
   if(usernameTaken($_POST['user'])){
      $use = $_POST['user'];
      die('<center><br><br><br><div id="sectioncontent">Sorry, this username is already taken.<br><br><a href="./register.php">Please try again</a>.</div></center>');
   }
  
   // Check if username is a wustl.edu email address

   if(strtolower(substr($_POST['user'], -9,9)) != "wustl.edu"){
      $use = $_POST['user'];
      die('<center><br><br><br><div id="sectioncontent">Sorry, your username must be your wustl.edu email-address.<br><br><a href="./register.php">Please try again</a>.</div></center>');
   }   

   /* Add the new account to the database */
   $md5pass = md5($_POST['pass']);
   $_SESSION['reguname'] = $_POST['user'];
   $_SESSION['regresult'] = addNewUser($_POST['user'], $md5pass);
   $_SESSION['registered'] = true;
   echo "<meta http-equiv=\"Refresh\" content=\"0;url=$HTTP_SERVER_VARS[PHP_SELF]\">";
   return;
}
else{
/**
 * This is the page with the sign-up form, the names
 * of the input fields are important and should not
 * be changed.
 */
	echo '<br><br><br><br><br>';
	echo '<form action="'.$HTTP_SERVER_VARS['PHP_SELF'].'" method="post">';
	echo '<table width="800" cellpadding="35">';
	echo '<tr>';
	echo '<td bgcolor="#FFFFFF" height="300" width="400" valign="middle">';
	echo '<center>';
	echo '<img width="350" src="./assets/washulogocentered.jpg">';
	echo '</center>';
	echo '<br><br>';
	echo '<br><br>';
	echo '</td>';
	echo '<td bgcolor="#EEEEEE" height=300" width="200" valign="middle">';
	echo '<font color="#0096D8" size=3 face="Verdana">Enter your wustl.edu email address as your username (e.g., janedoe@wustl.edu) and your desired password to register for this site.<br><br></font>';	
	echo '<font color="#131413">Username (wustl.edu email)<br>';
	echo '<input type="text" title="Enter a Username" name="user" size="30" maxlength="30"><br><br>';
	echo 'Password<br>';
	echo '<input type="password" title="Enter your Password" name="pass" size="30" maxlength="30"><br>';
	echo '<input type="submit" name="subjoin" value="Register">';
	echo '</td>';
	echo '</tr>';
	echo '</table>';
	echo '</form>';	
	echo '<br>';	
 }
 include './plainfooter.php';
?>