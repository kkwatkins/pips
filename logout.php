<?php
include './logoheader.php';
include('./login.php');
/**
 * Delete cookies - the time must be in the past,
 * so just negate what you added when creating the
 * cookie.
 **/
if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookpass'])){
   setcookie("cookname", "", time()-60*60*24*100, "/");
   setcookie("cookpass", "", time()-60*60*24*100, "/");
}
if(!$logged_in){
	echo '<br><br><br><center><div class="sectioncontents">';
		echo 'Logout Failed - You are not currently logged in.<br><br>';
		echo '<a href="index.php" title="Login">Login or Create an Account</a>.</div></center>';
} else{
// Kill session variables
   unset($_SESSION['username']);
   unset($_SESSION['password']);
   $_SESSION = array(); // reset session array
   session_destroy();   // destroy session.
	echo '<br><br><br><center><div class="sectioncontents">You have successfully logged out.<br><br><a href="index.php" title="Login">Return to Login</a>.</div></center>';
}
include './plainfooter.php';
?>