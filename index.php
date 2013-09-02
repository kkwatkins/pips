<?php
include './login.php';
if(!$logged_in) {
	displayLogin();
	echo '<BR>';
	include './plainfooter.php';	
	die;
}
include './logoheader.php';
include './functionset.php';	

// Get user attributes to see what type of user we have 
$query = "SELECT role FROM user_attributes WHERE username = '".$_SESSION['username']."'";
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($result);

if($row['role'] == 1) {
	$_SESSION['role'] = 1;
	displayAdmin();
}

// List the reports for this user
if($row['role'] == 1) {
	$query = 'SELECT DISTINCT user_reports.reportid, reports.reportdesc, reports.sessionid FROM user_reports JOIN reports ON user_reports.reportid = reports.reportid';	
} else {
	$query = 'SELECT user_reports.reportid, user_reports.accesslevel, reports.reportdesc, reports.sessionid FROM user_reports JOIN reports ON user_reports.reportid = reports.reportid WHERE user_reports.username = "'.$_SESSION['username'].'"';
}
$result = mysql_query($query) or die(mysql_error());

if(mysql_numrows($result) > 0) {
	echo '<div class="sectionhead">Your interpersonal skills reports</div>';
	echo '<div class="sectioncontent">';
	echo '<table class="bodylist">';
	echo '<tr>';
	echo '<th>Session Description</th>';
	echo '<th>Session Date</th>';	
	echo '<th>Report Description</th>';
	echo '</tr>';	
	while($row = mysql_fetch_array($result)) {
		// Get the session description
		$query = "SELECT sessiondesc, UNIX_TIMESTAMP(sessiondate) AS sessiondate FROM sessions WHERE sessionid = '".$row['sessionid']."'";
		$result2 = mysql_query($query) or die(mysql_error());
		$row2 = mysql_fetch_array($result2);
		echo '<tr>';
		echo '<td>'.$row2['sessiondesc'].'</td>';
		echo '<td>'.date('M d, Y',$row2['sessiondate']).'</td>';
		echo '<td><a href="feedback.php?rid='.$row['reportid'].'">'.$row['reportdesc'].'</a></td>';				
		echo '</tr>';
	}
	echo '</table>';
	echo '</div><br>';
}


include './plainfooter.php';
?>
