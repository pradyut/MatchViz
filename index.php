<!DOCTYPE html >
<html>

	<head>
		<meta charset="utf-8" name="apple-mobile-web-app-capable" content="yes"/>
		<title>Match Visualizer</title>
		<link rel="stylesheet" href="style.css" type="text/css" media="screen">	
		<script src= "https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
		<link rel="stylesheet" href="./stylesheets/jslider.plastic.css" type="text/css">
		<script type="text/javascript" src="./javascripts/jquery.dependClass.js"></script>
		<script type="text/javascript" src="./javascripts/jquery.slider-min.js"></script>
		<link rel="stylesheet" href="./stylesheets/jslider.css" type="text/css">
	

		<script type="text/javascript">
			var d1a = "2.5.11";
			var t1;
			var t1id;
			var t2;
			var t2id;
			var t1s;
			var t2s;
			var p0=['All'];//new Array();
			var p0id=['100'];//new Array();
			var p1=['All'];//new Array();
			var p1id=['101'];
			var p2=['All'];//new Array();
			var p2id=['102'];
			var d1b = "Feb 02, 2011";
			var d2a = "4.2.11";
			var left=0;
			var right =90;
			var good=3;
			
			var d2b = "Apr 02, 2011";
			var matchID
			$(document).ready(function () {
				

				$("#sm").change(function () {
					$("#sumg1").hide();
					$("#sumg2").hide();
          			matchID=(this.options[this.selectedIndex].value);
					$.ajax({
						type:"GET",
						url: "php/getMatchData.php",
						data:({mid:matchID}),
						dataType:"xml",
						success: function(xml) {
							$(xml).find("matchinfo").each(function(){
								if($(this).attr("date")=="0000-00-00")
									alert("Match not found in database!")
								else
									fillMatchInfo(xml);
							});
						}					              	
    				});
    			});
    			
    			$("#st").change(function () {
    				
    				teamID=(this.options[this.selectedIndex].value);	
    				sp = document.getElementById("sp");
    				i =sp.options.length;
    				while(i>=0){
    					sp.options.remove(i);
    					i--
    				}
    				for(i=0;i<window['p'+teamID].length;i++){
    					var newOption = document.createElement("option");
						newOption.text = window['p'+teamID][i];
						newOption.value = window['p'+teamID+'id'][i];
						sp.options.add(newOption);

    				}

    			});
    			
    			$("#sp").change(function () {
    				getFilters();
    				
    				
    			});
    			
    			$("#se").change(function () {
    				getFilters();
    				

				});
				
    			$("#ses").change(function () {
    				getFilters();
    				

				});
				
    			
        	});
        	
        	function getFilters(){
        		se =document.getElementById("se");
    			etype=se.options[se.options.selectedIndex].value;
    			sp =document.getElementById("sp");
    			
    			pid=sp.options[sp.options.selectedIndex].value;
    			ses =document.getElementById("ses");
    			
    			good=ses.options[ses.options.selectedIndex].value;
    		
    			reload(pid,etype,left,right,good)
        	}

        	function fillMatchInfo(xml,date){
		  		
		  		$(xml).find("team").each(function(){
		  			i=$(this).attr("where");
		  			window['t'+i]=$(this).attr("name")
		  			window['t'+i+"id"]=$(this).attr("tid")
		  			window['t'+i+"s"]=$(this).attr("score")
		  			createPlayer(xml,$(this).attr("tid"))
		  		});
		  		$("#summary #sum1").html(t1+" "+t1s);
		  		$("#summary #sum2").html(t2+" "+t2s);
		  		var d=d2a;
		  		if(matchID==1){
		  			$("#sumg1").show();
					$("#sumg2").show();
          			
		  			d=d1a
		  		}	
		  		$("#header span").html(d+" "+t1+" ("+t1s+") VS. "+t2+" ("+t2s+")");
		  		$("select").each(function(){
		  			$(this).removeAttr('disabled')
		  		});
		  		se = document.getElementById("se");
		  		fillTeam()
		  		//fillEvents()
		  }	
		  
		  function fillTeam(){
				$("#st").html('<option>Select</option><option value='+t1id+'>'+t1+'</option><option value='+t2id+'>'+t2+'</option>')
			}
			function createPlayer(xml,i){
				
				$(xml).find("player").each(function(){
				
				
					if($(this).attr("tid")==i){
						window['p'+i].push($(this).attr("pname"));
						window['p'+i+"id"].push($(this).attr("pid"))																	
					}
				});
			}
	      function drawArrow(X1,Y1,X2,Y2,success){
	        var canvas = document.getElementById('canvas');
	        if (canvas.getContext){
	        	var ctx = canvas.getContext('2d');
				var dist = null;
				var X3 = null;
				var Y3 = null;
				ctx.lineWidth = 1;
				ctx.lineJoin = 'round';
				if(success == 1){
					ctx.strokeStyle = "#87BF3A";
					ctx.fillStyle = "#87BF3A";
				}else{
					ctx.strokeStyle = "#29ABE2";
					ctx.fillStyle = "#29ABE2";
				}

				//draw line
		    	ctx.beginPath();
				ctx.moveTo(X1,Y1);
			 	ctx.lineTo(X2,Y2);
				ctx.stroke();

				//draw circle
				ctx.moveTo(X1,Y1);
				ctx.arc(X1,Y1,4,0,2*Math.PI, 0);
				ctx.fill();
				
				//draw arrow
				ctx.moveTo(X2,Y2);
				ctx.arc(X2,Y2,2,0,2*Math.PI, 1);
				ctx.fill();
				

				
				//draw arrow
				//slope = (Y2-Y1)/(X2-X1);
				
				
				//X3 = X2 - (-5/(Math.sqrt(1+(Math.pow(slope,2)))));
				//Y3 = Y2 - (slope*(X2-X3));
				
				//ctx.moveTo(X2,Y2);
				//ctx.lineTo(X3-5,Y3-6);
				//ctx.lineTo(X3-5,Y3+6);
				//ctx.lineTo(X2,Y2);
				//ctx.arc(X3,Y3,1,0,2*Math.PI, 0);
				//ctx.fill();
				
				
				
			 	/*ctx.beginPath();
			 	ctx.moveTo(X1,Y1);
			 	ctx.lineTo(X2,Y2);
			 	ctx.stroke();*/
			 	
				ctx.save();
	        }
	      }
	    function changeTime(l,r){
	    	left=l;
	    	right=r;
	    	getFilters()
	    }
	      
		function clearCanvas(){
    		var canvas = document.getElementById("canvas");
    		var context = canvas.getContext("2d");
    		context.clearRect(0, 0, canvas.width, canvas.height);
		}

		function reload(pid,etype,l,r,good){
			clearCanvas();
			$.ajax({
				type: "GET",
				url: "php/getEventData.php",
				dataType: "xml",
				data:"pid="+pid+"&etype="+etype+"&l="+l+"&r="+r+"&mid="+matchID+"&good="+good,
				success: function(xml) {
					$(xml).find('pass').each(function(){
						var X1 = $(this).attr('X1');
						var Y1 = $(this).attr('Y1');
						var X2 = $(this).attr('X2');
						var Y2 = $(this).attr('Y2');
						var success = $(this).attr('success');
						drawArrow(X1,Y1,X2,Y2,success);
						});
					}
			});
		}

    	</script>
	</head>
	
	<body>
		<div id="header"><span></span></div>
		
		<div id="wrapper">
		    <canvas id="canvas" width="600" height="450">
			</canvas>
			
			<div id="filters">
				<p  style="padding-top:7px;" class = "select">SELECT A MATCH</p>
				<select id="sm">
					<option>Select</option>
					<option value="1">Newcastle vs Arsenal (Feb 5, 2011)</option>
					<option value="2">Arsenal vs Blackburn Rovers (Apr 2, 2011)</option>
				</select>
				
				<p class ="select">SELECT A TEAM</p>
				<select id="st" disabled>
						
				</select>
				
				<p class="select">SELECT A PLAYER</p>
				<select id="sp" size="11" >		
					<option selected value="55">All</option>			
				</select>
				
				<p name="event" class="select">SELECT AN EVENT</p>
				<select id="se" disabled>
					
					<option value="0">Passes</option>
					<option value="1">Shots</option>
					<option value="2">Goals</option>						
				</select>
				<br/>
				<br/>
				
				<select id="ses" size="6" disabled>
					<option selected value="3">All</option>
					<option value="1">Successfull/On-target</option>
					<option value="0">Unsuccessful/Off-target</option>
				</select>
			</div>
		<!-- end #wrapper --></div>
		
		<div id="foot">
<!-- 			<canvas id="timeline"> -->
			<div class="layout-slider">
		      <input id="Slider2" type="slider" name="time" value="0;90" />
		    </div>
		    <script type="text/javascript" charset="utf-8">		
		      
 				jQuery("#Slider2").slider({ from: 0, to: 90, step: 1, dimension: '', scale: ['0', '45', '90'], limits: false, 
 				calculate: function( value ){
 					changeTime($(".jslider-value span").first().html(),$(".jslider-value-to span").html())
       				//if($(".jslider-value").hasClass("jslider-value-to"))
	       				//console.log($(".jslider-value-to span").html())
       				return value
      			}})
		    </script>

<!-- 			</canvas> -->
			
			<div id="summary">
				<p>SUMMARY</p>
				<p id="sum1"></p>
				<span style="display:none;" id="sumg1">Barton (pen) 68, (pen) 83, Best 75, Tiote 87</span>
			
				<p id="sum2"></p>
				<span style="display:none;" id="sumg2">Walcott 1, Djourou 3, van Persie 10, 26</span>
			<!-- end #summary --></div>
		<!-- end #foot --></div>
		
	</body>
</html>