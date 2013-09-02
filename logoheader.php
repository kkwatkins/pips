<?php
session_start();
include '../database_pips.php';

echo '<html>';
echo '<head>';
echo '<title>pips sandbox</title>';
echo '<link rel="stylesheet" href="./pips.css" type="text/css" title="original">';
//echo '<script type="text/javascript" src="./fsp.js"></script>';
//echo '<script type="text/javascript" src="./reveal_hide.js"></script>';

echo '</head>';

echo '<body>';
echo '<center>';
echo '<div id="container">';
echo '<br><br>';
echo '<table width="800">';
echo '<tr>';
echo '<td>';
echo '<a href="index.php"><img src="./assets/washulogo.jpg" width="400"></a>';
echo '</td>';
echo '<td STYLE="text-align: right; font-size: 14pt;">';
echo '<a href="index.php"><font STYLE="color: #800000;">pips sandbox</font></a>';
if($logged_in) {
	echo "<div STYLE='text-align: right; font-size:10pt;'><br>Logged in as ".$_SESSION['username']." | <a href='logout.php'>Log out</a></div>";
}
else {
	"<div STYLE='text-align: right; font-size:10pt;'><br><a href='index.php'>Log In</a></div>";
}	

echo '</td>';
echo '</tr>';
echo '</table>';
echo '<br><br>';
?>