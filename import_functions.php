<?php
include '../database_pips.php';
echo "start";

// First, add the new report
$query = "DELETE FROM sessions";
mysql_query($query) or die(mysql_error());
$query = "DELETE FROM reports";
mysql_query($query) or die(mysql_error());


$sessiondesc = 'Full-time MBA Everest Simulation';
$query = "INSERT INTO sessions SET sessiondesc='".$sessiondesc."', sessiondate='2013-08-27 08:15'";
mysql_query($query) or die(mysql_error());

// Get the id of what was just added.
$sessionid = mysql_insert_id();
echo "sessionid = ".$sessionid.'<BR>';

// Second, add the reports to the report section
$filename = "./uploads/everest_report_info.txt";
$fcontents = file($filename); 
$cols = explode("\t", trim($fcontents[0]));
foreach($cols as $pos => $name) {
	$colname[$pos] = $name;
	$colpos[$name] = $pos;		
}
for($i = 1; $i < sizeof($fcontents); $i++) {
	echo $i."<BR>";
	$vals = explode("\t", trim($fcontents[$i]));
	$query = 'INSERT INTO reports SET sessionid = '.$sessionid.', reportdesc="'.$vals[$colpos['reportdesc']].'"';
	mysql_query($query) or die(mysql_error());
	echo $query.'<BR>';
}

// Third, add the indiv_static data
$filename = "./uploads/everest_indiv_static.txt";
$fcontents = file($filename); 
$cols = explode("\t", trim($fcontents[0]));
foreach($cols as $pos => $name) {
	$colname[$pos] = $name;
	$colpos[$name] = $pos;		
}
for($i = 1; $i < sizeof($fcontents); $i++) {
	$vals = explode("\t", trim($fcontents[$i]));
	
	// Get the reportid for this person
	$query = 'SELECT reportid FROM reports WHERE sessionid = "'.$sessionid.'" AND reportdesc = "'.$vals[$colpos['reportdesc']].'"';
	echo $query.'<BR><BR>';
	$result = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_array($result);
	$reportid = $row['reportid'];
	$query = 'INSERT INTO indiv_static SET reportid = "'.$reportid.'", username = "'.$vals[$colpos['username']].'", metric="'.$vals[$colpos['metric']].'", value="'.$vals[$colpos['value']].'"';
	echo $query.'<BR>';	
	mysql_query($query) or die(mysql_error());
}

// Fourth, add the indiv_dynamic data
$filename = "./uploads/everest_indiv_dynamic.txt";
$fcontents = file($filename); 
$cols = explode("\t", trim($fcontents[0]));
foreach($cols as $pos => $name) {
	$colname[$pos] = $name;
	$colpos[$name] = $pos;		
}
for($i = 1; $i < sizeof($fcontents); $i++) {
	$vals = explode("\t", trim($fcontents[$i]));
	
	// Get the reportid for this person
	$query = 'SELECT reportid FROM reports WHERE sessionid = "'.$sessionid.'" AND reportdesc = "'.$vals[$colpos['reportdesc']].'"';
	echo $query.'<BR><BR>';
	$result = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_array($result);
	$reportid = $row['reportid'];
	$query = 'INSERT INTO indiv_dynamic SET reportid = "'.$reportid.'", username = "'.$vals[$colpos['username']].'", metric="'.$vals[$colpos['metric']].'", time_stamp = "'.$vals[$colpos['time_stamp']].'", value="'.$vals[$colpos['value']].'"';
	echo $query.'<BR>';	
	mysql_query($query) or die(mysql_error());
}

// Fifth, add the dyad_static data
$filename = "./uploads/everest_dyad_static.txt";
$fcontents = file($filename); 
$cols = explode("\t", trim($fcontents[0]));
foreach($cols as $pos => $name) {
	$colname[$pos] = $name;
	$colpos[$name] = $pos;		
}
for($i = 1; $i < sizeof($fcontents); $i++) {
	$vals = explode("\t", trim($fcontents[$i]));
	
	// Get the reportid for this person
	$query = 'SELECT reportid FROM reports WHERE sessionid = "'.$sessionid.'" AND reportdesc = "'.$vals[$colpos['reportdesc']].'"';
	echo $query.'<BR><BR>';
	$result = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_array($result);
	$reportid = $row['reportid'];
	$query = 'INSERT INTO dyad_static SET reportid = "'.$reportid.'", ego_username = "'.$vals[$colpos['ego_username']].'", alter_username = "'.$vals[$colpos['alter_username']].'", metric="'.$vals[$colpos['metric']].'", value="'.$vals[$colpos['value']].'"';
	echo $query.'<BR>';	
	mysql_query($query) or die(mysql_error());
}

// Sixth, add the roster data

$filename = "./uploads/everest_user_reports.txt";
$fcontents = file($filename); 
$cols = explode("\t", trim($fcontents[0]));
foreach($cols as $pos => $name) {
	$colname[$pos] = $name;
	$colpos[$name] = $pos;		
}
for($i = 1; $i < sizeof($fcontents); $i++) {
	$vals = explode("\t", trim($fcontents[$i]));
	
	// Get the reportid for this person
	$query = 'SELECT reportid FROM reports WHERE sessionid = "'.$sessionid.'" AND reportdesc = "'.$vals[$colpos['reportdesc']].'"';
	echo $query.'<BR><BR>';
	$result = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_array($result);
	$reportid = $row['reportid'];
	$query = 'INSERT INTO user_reports SET reportid = "'.$reportid.'", username = "'.$vals[$colpos['username']].'", accesslevel = "'.$vals[$colpos['accesslevel']].'"';
	echo $query.'<BR>';	
	mysql_query($query) or die(mysql_error());
}


?>