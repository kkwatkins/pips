<?php

function sendCredentials($username) {
	return $username;

}

function standard_deviation($aValues, $bSample = false)
{
    $fMean = array_sum($aValues) / count($aValues);
    $fVariance = 0.0;
    foreach ($aValues as $i)
    {
        $fVariance += pow($i - $fMean, 2);
    }
    $fVariance /= ( $bSample ? count($aValues) - 1 : count($aValues) );
    return (float) sqrt($fVariance);
}

function mmmr($array, $output = 'mean'){ 
    if(!is_array($array)){ 
        return FALSE; 
    }else{ 
        switch($output){ 
            case 'mean': 
                $count = count($array); 
                $sum = array_sum($array); 
                $total = $sum / $count; 
            break; 
            case 'median': 
                rsort($array); 
                $middle = round(count($array) / 2); 
                $total = $array[$middle-1]; 
            break; 
            case 'mode': 
                $v = array_count_values($array); 
                arsort($v); 
                foreach($v as $k => $v){$total = $k; break;} 
            break; 
            case 'range': 
                sort($array); 
                $sml = $array[0]; 
                rsort($array); 
                $lrg = $array[0]; 
                $total = $lrg - $sml; 
            break; 
        } 
        return $total; 
    } 
} 


function checkReportStatus($sid, $classid) {
	$query = "SELECT report_open FROM class_surveys WHERE sid = '".$sid."' AND classid = '".$classid."'";
	$result = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_array($result);
	if($row['report_open'] == NULL || $row['report_open'] == "") {
		$row['report_open'] = 0;
	}
	return $row['report_open'];
}

function displayAdmin() {
	echo '<div class="sectionhead">Administrator Functions</div>';
	echo '<div class="sectioncontent">';
	echo 'You are an administrator. Someday there will be functionality here.';

/*	
	echo '<form name="emulate_user" action="'.$_SERVER['PHP_SELF'].'" method="POST">';
		$query = "SELECT DISTINCT username FROM user_reports ORDER BY username";
		$result = mysql_query($query) or die(mysql_error());
		echo '<select name="emulate_username" onChange="this.form.submit();">';
		echo '<option value="" selected>Choose a user to emulate</option>';		
		while($row = mysql_fetch_array($result)) {
			$selected = "";
			if($row['username'] == $_SESSION['username']) {
				$selected = "selected";
			}
			echo '<option value="'.$row['username'].'" '.$selected.'>'.$row['username'].'</option>';
		}
		echo '</select>';
	echo '</form>';
*/	
	echo '</div><br>';
}

function checkUserInAttributes($username) {
	// This function checks to see if a user already exists in the user attributes table 	
	$exists = FALSE;
	$query = "SELECT username FROM user_attributes WHERE username = '".strtolower($username)."'";
	$result = mysql_query($query) or die(mysql_error());
	if(mysql_numrows($result) > 0) {
		$exists = TRUE;
	}	
	return $exists;	

}

function checkUserInAuthenticate($username) {
	// This function checks to see if a user already exists in the user authentication table
	$exists = FALSE;
	$query = "SELECT username FROM authenticate WHERE username = '".$username."'";
	$result = mysql_query($query) or die(mysql_error());
	if(mysql_numrows($result) > 0) {
		$exists = TRUE;
	}	
	return $exists;	

}

function checkFeedbackAccess($username, $reportid) {
	// This function checks to see if a user has access rights to a give report
	$access = FALSE;
	$query = "SELECT username FROM user_reports WHERE username = '".$username."' AND reportid = '".$reportid."'";
//s	echo $query.'<BR>';
	$result = mysql_query($query) or die(mysql_error());
	if(mysql_numrows($result) > 0 || $_SESSION['role'] == 1) {
		$access = TRUE;
	}	
	return $access;	

}

?>