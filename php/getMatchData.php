<?php  
	header ("Content-Type:text/xml");  
	$mid = $_GET['mid'];
	
	//Generating XML file
	$xml_output = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
	$xml_output .= "<matchinfo id='".$mid."' ";
	

	$date="0000-00-00";
	//Connecting to DB
	$db = "localhost";
	$un = "plugmeup";
	$pw = "plugmeup1";
	$dbname = "plugdb_main";
	
	$team1;
	$team1id;
	$team1score;
	$team2;
	$team2id;
	$team2score;

	if($dbc = mysql_connect( $db, $un, $pw ));
	mysql_select_db($dbname, $dbc)
	  or die( "Error! Could not select the database: " . mysql_error());
	$query  = "select * from `matches` where matches.id=$mid"; 

/* 	select * from `teams` where teams.id = (select Team_Home from `matches` where matches.id=1) or teams.id = (select Team_Away from `matches` where matches.id=1) */
	$result = mysql_query($query, $dbc);
	
	
	
	while($row = mysql_fetch_array($result)){
		$team1id=$row['Team_Home'];
		$team2id=$row['Team_Away'];
		$team1score=$row['HomeGoal'];
		$team2score=$row['AwayGoal'];
		$date=$row['Date'];	
	}
	$xml_output.= "date='".$date."' ";
	$xml_output.='>'."\n";
	if($date!="0000-00-00"){
		$query  = "select * from `teams` where teams.id = (select Team_Home from `matches` where matches.id=$mid) or teams.id = (select Team_Away from `matches` where matches.id=$mid)";
		
		$result = mysql_query($query, $dbc);
		while($row = mysql_fetch_array($result)){
			if($row['ID']==$team1id){
				$team1=$row['Name'];
				$xml_output.= fillInfo($team1,$team1id,$team1score,"1");
			}
			if($row['ID']==$team2id){
				$team2=$row['Name'];
				$xml_output.=fillInfo($team2,$team2id,$team2score,"2");
			}
		}
	}
		
	$xml_output .= '</matchinfo>';
	echo $xml_output;
		
	function fillInfo($name,$id,$score,$where){
		$entry.="<team ";
		$entry .= "tid='".$id."' ";
		$entry .= "name='".$name."' ";
		$entry .= "where='".$where."' ";
		$entry .= "score='".$score."' ";
		$entry .= '>'."\n";
		$entry .= getPlayer($id);
		$entry .= "</team>\n";
		return $entry;
	}
	
	function getPlayer($id){
	$db = "localhost";
	$un = "plugmeup";
	$pw = "plugmeup1";
	$dbname = "plugdb_main";
		if($dbc = mysql_connect( $db, $un, $pw ));
	mysql_select_db($dbname, $dbc)
	  or die( "Error! Could not select the database: " . mysql_error());
		$query  = "select * from `players` where Team_ID=$id"; 
		$result = mysql_query($query, $dbc);
		$ent;
		while($row = mysql_fetch_array($result)){
			$ent.="<player ";
			$ent .="pid= '".$row['ID']."' ";
			$ent .="pname= '".$row['Name']."' ";
			$ent .="tid= '".$id."' ";
			$ent .= '/>'."\n";
		}
		return $ent;
	}
	
	