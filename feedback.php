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

$ind = $_POST['ind'];

if(!checkFeedbackAccess($_SESSION['username'], $_GET['rid'])) {
	echo 'You do not have access to this report';
	die();
}
// get the focal individual
if($_SESSION['role'] == 1) {
	$query = 'SELECT DISTINCT username FROM user_reports WHERE reportid = "'.$_GET['rid'].'"';
	$result = mysql_query($query) or die(mysql_error());
	echo '<form name="focal_indiv" action="'.$_SERVER['PHP_SELF'].'?rid='.$_GET['rid'].'" method="POST">';
	echo '<select name="ind" onChange="this.form.submit();">';
	echo '<option value="" selected>Choose a focal user</option>';		
	while($row = mysql_fetch_array($result)) {
		$selected = "";
		if($row['username'] == $ind) {
			$selected = "selected";
		}
		echo '<option value="'.$row['username'].'" '.$selected.'>'.$row['username'].'</option>';
	}
	echo '</select>';
	echo '</form>';	
} else {
	$ind = $_SESSION['username'];
}

// Get the summary information for this report
$query = "SELECT sessions.sessiondesc, UNIX_TIMESTAMP(sessions.sessiondate) AS sessiondate, reports.reportdesc FROM sessions JOIN reports ON sessions.sessionid = reports.sessionid WHERE reports.reportid='".$_GET['rid']."'";
//echo $query.'<BR>';
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($result);

echo '<div class="sectionhead">';
	echo $row['sessiondesc'].' | '.$row['reportdesc'].' ('.date('M d, Y',$row['sessiondate']).')';
echo '</div>';
echo '<div class="sectioncontent">';
echo '<div class="quadl">';
echo 'Balance of Speaking and Listening<br>';
// get the sum for this report and the number of people
$query = "SELECT COUNT(username) AS num_people, SUM(value) AS total_speaking FROM indiv_static WHERE reportid = ".$_GET['rid']." AND metric = 'speech_profile_speaking' GROUP BY reportid";
$agg_result = mysql_query($query) or die(mysql_error());
$agg_row = mysql_fetch_array($agg_result);
// GET THE FOCAL INDIVIDUAL'S SPEAKING
$query = "SELECT value AS ind_speaking FROM indiv_static WHERE reportid = ".$_GET['rid']." AND metric = 'speech_profile_speaking' AND username = '".$ind."'";
$ind_result = mysql_query($query) or die(mysql_error());
$ind_row = mysql_fetch_array($ind_result);

$balance_pct = 100/$agg_row['num_people'];
$ind_pct = 100*$ind_row['ind_speaking']/$agg_row['total_speaking'];

// Figure out the individual's category
$inc = 0.20;
$r1 = $balance_pct + $balance_pct*$inc;
$r2 = $balance_pct + $balance_pct*$inc*2;
$l1 = $balance_pct - $balance_pct*$inc;
$l2 = $balance_pct - $balance_pct*$inc*2;

echo $agg_row['total_speaking'].", ".$agg_row['num_people']."<BR>";
echo $ind_row['ind_speaking']."<BR>";

$cat = 0;
if($ind_pct <= $l2) {
	$cat = -2; 
} else
if($ind_pct <= $l1) {
	$cat = -1;
} else 
if($ind_pct >= $r2) {
	$cat = 2;
}
else
if($ind_pct >= $r1) {
	$cat = 1;
}
// Draw the table
$cols[-2] = "#F59759";
$cols[-1] = "#F5CA59";
$cols[0] = "#93BC69";
$cols[1] = "#F5CA59";
$cols[2] = "#F59759";

echo '<table>';

echo '</table>';

echo '</div>';

echo '<div class="quadr">';
echo 'Average Speaking Segment Length';
echo '</div>';

echo '<div class="quadl">';
echo 'Pacing of Speaking over Time';
echo '</div>';

echo '<div class="quadr">';
echo 'Engagement with Group Members';
echo '</div>';

echo '<br>';
echo '</div>';

include './plainfooter.php';
?>