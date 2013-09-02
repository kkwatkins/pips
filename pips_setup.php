<?php
include '../database_pips.php';
// Create the login table
echo "start<BR><BR>";
/*
$query = 'DROP TABLE IF EXISTS authenticate';
mysql_query($query) or die(mysql_error());
$query = "CREATE TABLE authenticate(username VARCHAR(30), password VARCHAR(32))";
mysql_query($query) or die(mysql_error());
echo $query."<BR>";
*/
$query = "DROP TABLE IF EXISTS sessions";
echo $query."<BR>";
mysql_query($query) or die(mysql_error());
$query = "CREATE TABLE sessions(sessionid INT NOT NULL AUTO_INCREMENT, PRIMARY KEY (sessionid), sessiondesc VARCHAR(256), sessiondate DATETIME)";
echo $query."<BR>";

mysql_query($query) or die(mysql_error());


$query = "DROP TABLE IF EXISTS reports";
mysql_query($query) or die(mysql_error());
$query = "CREATE TABLE reports(reportid INT NOT NULL AUTO_INCREMENT, PRIMARY KEY (reportid), sessionid INT, reportdesc VARCHAR(256))";
mysql_query($query) or die(mysql_error());
echo $query."<BR>";

$query = "DROP TABLE IF EXISTS user_reports";
mysql_query($query) or die(mysql_error());
$query = "CREATE TABLE user_reports(id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),username VARCHAR(30), reportid INT, accesslevel INT)";
mysql_query($query) or die(mysql_error());
echo $query."<BR>";
$query = "DROP TABLE IF EXISTS user_attributes";
mysql_query($query) or die(mysql_error());
$query = "CREATE TABLE user_attributes(id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY (id), username VARCHAR(30), role INT, firstname VARCHAR(75), middlename VARCHAR(75), lastname VARCHAR(75), emailaddress VARCHAR(75), primedivision VARCHAR(5), stdtid int(11))";
mysql_query($query) or die(mysql_error());
echo $query."<BR>";
$query = "INSERT INTO user_attributes SET username = 'knightap@wustl.edu', role = '1', firstname='Andrew', lastname='Knight', emailaddress='knightap@wustl.edu'";
echo $query."<BR>";
mysql_query($query) or die(mysql_error());

$query = "INSERT INTO user_attributes SET username = 'karren.watkins@wustl.edu', role = '1', firstname='Karren', lastname='Watkins', emailaddress='karren.watkins@wustl.edu'";
echo $query."<BR>";
mysql_query($query) or die(mysql_error());
/*
$query = "INSERT INTO reports SET reportdesc='Everest Simulation - Team 1'";
echo $query."<BR>";
mysql_query($query) or die(mysql_error());

$query = "INSERT INTO user_reports SET username='knightap@wustl.edu', reportid=1, accesslevel=1";
echo $query."<BR>";
mysql_query($query) or die(mysql_error());
*/
### Create the dataset tables ###
### These are temporary, will change later ###
$query = "DROP TABLE IF EXISTS indiv_static";
mysql_query($query) or die(mysql_error());
$query = "CREATE TABLE indiv_static(id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY (id), reportid INT,  username VARCHAR(256), metric VARCHAR(256), value DECIMAL(11,2))";
echo $query.'<BR>';
mysql_query($query) or die(mysql_error());

$query = "DROP TABLE IF EXISTS indiv_dynamic";
mysql_query($query) or die(mysql_error());
$query = "CREATE TABLE indiv_dynamic(id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY (id), reportid INT,  username VARCHAR(256), time_stamp DATETIME, metric VARCHAR(256), value DECIMAL(11,2))";
echo $query.'<BR>';
mysql_query($query) or die(mysql_error());

$query = "DROP TABLE IF EXISTS dyad_static";
mysql_query($query) or die(mysql_error());
$query = "CREATE TABLE dyad_static(id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY (id), reportid INT,  ego_username VARCHAR(256), alter_username VARCHAR(256), metric VARCHAR(256), value DECIMAL(11,2))";
echo $query.'<BR>';
mysql_query($query) or die(mysql_error());

?>