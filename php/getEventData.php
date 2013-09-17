<?php  
	header ("Content-Type:text/xml");  
	$pid=$_GET['pid'];
	$etype=$_GET['etype'];
	$l=$_GET['l'];
	$r=$_GET['r'];
	$mid=$_GET['mid'];
	$good=$_GET['good'];
	$g="";
	if($good<3){
		$g=" AND Success=".$good;
	}
	if($pid>99){
		$pl="Team_ID =".$pid%100;
	}
	else
		$pl="Player1_ID = $pid";
	//Generating XML file
	$xml_output = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
	$xml_output .= "<events type='passes'>\n";
	
	//Connecting to DB
	$db = "localhost";
	$un = "plugmeup";
	$pw = "plugmeup1";
	$dbname = "plugdb_main";
	
	$start;
	$end;
	$explodeStart;
	$explodeEnd;
	$X1per;
	$Y1per;
	$X2per;
	$Y2per;
	$X1;
	$Y1;
	$X2;
	$Y2;

	if($dbc = mysql_connect( $db, $un, $pw ));
	mysql_select_db($dbname, $dbc)
	  or die( "Error! Could not select the database: " . mysql_error());
	$query  = "select * from `passes` where ".$pl." AND type = $etype AND Minute>=$l AND Minute<=$r AND Match_ID=$mid".$g; 
	// $chan_type AND event_id = $evID";
	$result = mysql_query($query, $dbc);
	
	
	$explodeStart = null;
	while($row = mysql_fetch_array($result)){
		$start = $row['X1Y1'];
		$end = $row['X2Y2'];
		$success = $row['Success'];
		$explodeStart = explode("#", $start);
		$explodeEnd = explode("#", $end);
		
		$X1per = "$explodeStart[1]";
		$Y1per = "$explodeStart[2]";
		$X2per = "$explodeEnd[1]";
		$Y2per = "$explodeEnd[2]";
		
		$X1 = rotateTransform($Y1per,x);
		$Y1 = rotateTransform($X1per,y);
		$X2 = rotateTransform($Y2per,x);
		$Y2 = rotateTransform($X2per,y);
			
		$entry.="<pass ";
		$entry .= "X1='".$X1."' ";
		$entry .= "X2='".$X2."' ";		
		$entry .= "Y1='".$Y1."' ";
		$entry .= "Y2='".$Y2."' ";				
		$entry .= "success='".$success."' ";
		$entry .= '>'."\n";
		$entry .= "</pass>\n";
		
		$xml_output .= $entry;
	}
	
	
	
	

	function rotateTransform($val,$axis){
		
		if($axis == x) 
			return (($val/100)*554)+25; 
		else
			return ((((-1*$val)+100)/100)*402)+25;
	}
	
	

			
	
	$xml_output .= '</events>';
	echo $xml_output;
	
	