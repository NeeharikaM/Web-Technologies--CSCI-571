<html>
	<head>
	    <style type='text/css'>
	        form {
	            text-align: center;
	        }
	        table {
	         border: 1px solid grey;
	         border-collapse: collapse;
	         background-color:#F3F3F3;
	        }
	        th {
	            text-align: left;
	        }
	        td {
	            text-align: center;
	        }
	        .table1 {
	            text-align: left;
	        }
	        .error {
	            text-align:center;
	        }
	        div#dynamic {
	        text-align:center;
	        }
	        a{
    			text-decoration: none;
			}
			body{
			 text-align:center; 
			 vertical-align:middle;
			}
			div{
				text-align: center;
				margin:0 auto;
			}
	           
	    </style>
	    <script type='text/javascript'>
	    document.getElementById("cmtx_comment").value = localStorage.getItem("comment");
	        function reset_form(form){
	            document.getElementById("dynamic").innerHTML=" "; 
	            document.forms["myform"]["company"].value = " ";
	        }
	    function news(){
          var newsvar =  document.getElementById("mynews");
          //newsvar.src = "http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Up.png";
          if (newsvar.src == "http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Up.png"){
            newsvar.src = "http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Down.png";
            var newstable = document.getElementById("newstable");
             newstable.style.display = 'none';
          }
          else{
            newsvar.src = "http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Up.png";
             var newstable = document.getElementById("newstable");
             newstable.style.display = 'table';
          }
        }
	    </script>
	    <script src="http://code.highcharts.com/adapters/standalone-framework.js"></script>
		<script src="http://code.highcharts.com/highcharts.js"></script>
		<script src="http://code.highcharts.com/modules/exporting.js"></script>
	</head>
	    <body onLoad='myPRICEChart()'>
	        <table align=center border=1 cellpadding=10>
	            <tr>
	            	<th class = "heading"> 
	                	Stock Search
	            	</th>
	            </tr>
	            <tr>
	            	<td>
	            	<form name="myform" method="post" action=" ">
	                	Enter Stock Ticker Symbol:* <input type="text" name="company"><br>
	                	<div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	                    	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	                	<input type= "submit" value="search" name="submit" onClick="check_form(this.form)" >
	                	<input type= "button" value="clear" name="reset" onclick="reset_form(this.form)">
	                	</div>
	                	* - Mandatory Fields.
	            	</form>
	            	</td>
	            </tr> 
	            
	        </table>
	        <br>
	        <div id="dynamic">
	        <?php
	        if(isset($_POST['submit'])){
	         $var = trim($_POST["company"]);
	         if($var==""){
	         	$_POST["company"]="";
	         	echo '<script type="text/javascript">alert("Please enter a value");</script>';
	         	exit;
	         }
	        $url = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=".$var."&apikey=8WJ1BA0XCFY36KXJ";
	        $json_object = file_get_contents($url);
	        //echo $json_object;
			$json_content = json_decode($json_object,true);
			 if (array_key_exists("Error Message",$json_content))
                {
                    die("<br><br><table text-align=center border=1 cellpadding=10 width=60%><tr><td class='error'> No records has been found </td></tr></table>");
                }
			$key = array_keys($json_content)[1];
			$last_day_session_key = array_keys($json_content[$key]);
			$close = $json_content['Time Series (Daily)'][$last_day_session_key[0]]['4. close'];
			$open  = $json_content['Time Series (Daily)'][$last_day_session_key[0]]['1. open'];
			$previous_close = $json_content['Time Series (Daily)'][$last_day_session_key[1]]['4. close'];
			$change = $close-$previous_close;
			$change_percent = round($change/$previous_close*100,2);
			 echo "<table align=center border=1 cellpadding=10 width=80%><tr>";
	            echo "<th>Stock Ticker Symbol</th><td>";
	            echo $json_content['Meta Data']['2. Symbol']."</td></tr>";
	            echo "<th>Close</th><td>";
	            echo $close."</td></tr>";
	            echo "<th>Open</th><td>";
	            echo $open."</td></tr>";
	            echo "<th>Previous Close</th><td>";
	            echo $previous_close."</td></tr>";
	            echo "<th>Change</th><td>";
	            if ($change < 0){
	            echo $change."%<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png ' height='15' width='15'></td></tr>";
	            }
	            else if ($change > 0){
	            echo $change."%<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png' height='15' width='15'></td></tr>";
	            }
	            else{
	            echo $change."</td></tr>";
	            }
	            echo "<th>Change Percent</th><td>";
	            if ($change_percent < 0){
	            echo $change_percent."%<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png' height='15' width='15'></td></tr>";
	            }
	            else if ($change_percent > 0){
	            echo $change_percent."%<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png' height='15' width='15'></td></tr>";
	            }
	            else{
	            echo $change_percent."</td></tr>";
	            }
	            echo "<th>Day's Range</th><td>";
	            echo $json_content['Time Series (Daily)'][$last_day_session_key[0]]['3. low']."-".$json_content['Time Series (Daily)'][$last_day_session_key[0]]['2. high']."</td></tr>";
	            echo "<th>Volume</th><td>";
	            echo $json_content['Time Series (Daily)'][$last_day_session_key[1]]['5. volume']."</td></tr>";
	            echo "<th>Timestamp</th><td>";
	            echo $last_day_session_key[0]."</td></tr>";
	            echo "<th>Indicators</th><td>";
	            echo "<a href='#/' onClick='myPRICEChart()' value='PRICE'>Price&nbsp&nbsp&nbsp&nbsp&nbsp</a>"."<a href='#/' onClick='mySMAChart()' value='SMA'>SMA&nbsp&nbsp&nbsp&nbsp&nbsp</a>"."<a href='#/' onClick='myEMAChart()' value='EMA'>EMA&nbsp&nbsp&nbsp&nbsp&nbsp</a>"."<a href='#/' onClick='mySTOCHChart()' value='STOCH'>STOCH&nbsp&nbsp&nbsp&nbsp&nbsp</a>"."<a href='#/' onClick='myRSIChart()' value='RSI'>RSI&nbsp&nbsp&nbsp&nbsp&nbsp</a>"."<a href='#/' onClick='myADXChart()' value='ADX'>ADX&nbsp&nbsp&nbsp&nbsp&nbsp</a>"."<a href='#/' onClick='myCCIChart()' value='CCI'>CCI&nbsp&nbsp&nbsp&nbsp&nbsp</a>"."<a href='#/' onClick='myBBANDSChart()' value='BBANDS'>BBANDS&nbsp&nbsp&nbsp&nbsp&nbsp</a>"."<a href='#/' onClick='myMACDChart()' value='MACD'>MACD&nbsp&nbsp&nbsp&nbsp&nbsp</a>"."</td></tr>";
	            echo "</table>";
	            echo "<br>";
	        } 
			?>
			<div id="container" style="width:80%; height:400px; align:center"></div>
			<script language = "JavaScript">
				function myPRICEChart(){
				//console.log("hi");
				var myurl = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol="+"<?php echo $_POST['company'] ?>"+"&apikey=8WJ1BA0XCFY36KXJ";
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.open("GET",myurl,true);
				xmlhttp.send();
				xmlhttp.onload = function() {
  					var myResponse = xmlhttp.response;
  					var myObj = JSON.parse(myResponse);
  					//console.log(myObj["Meta Data"]["2: Indicator"]);	
  					var keys_sub_array = Object.keys(myObj["Time Series (Daily)"]);
  					var date_sort_desc = function (date1, date2) {
  											if (date1 > date2) return -1;
  											if (date1 < date2) return 1;
  											return 0;
										};
					keys_sub_array.sort(date_sort_desc);
					var d=new Date(keys_sub_array[0]);  //converts the string into date object
  					var m=d.getMonth()+1; //get the value of month
  					var current_year=d.getFullYear();
     				last_month = m-6;
     				var temp_keys_array=[];
     				for (i=0;i<keys_sub_array.length;i++){
     					var r=new Date(keys_sub_array[i]);
     					var mn=r.getMonth()+1;
     					var yr=r.getFullYear();
     				 	if(mn > last_month && yr==current_year){
     				 		temp_keys_array.push(keys_sub_array[i]);
     					}
     				}
     				//console.log(keys_array);
     				var keys_array=[];
     				for (var j=0; j<temp_keys_array.length; j++){
     					keys_array.push(temp_keys_array[j].slice(5,7)+'/'+temp_keys_array[j].slice(8,10));
     				}
  					var sub_obj = myObj["Time Series (Daily)"];
  					result1 = [];
  					for (var x =0; x<keys_array.length; x++){
  						result1.push(sub_obj[temp_keys_array[x]]["4. close"]);
  					}
  					var y_axis_price_values=[];
					for (var i=0,l=result1.length;i<l;i++) 
					y_axis_price_values.push(parseFloat(result1[i])); // or parseInt(arr[i]) or Number(arr[i])
					console.log(y_axis_price_values);
					result2 = [];
  					for (var x =0; x<keys_array.length; x++){
  						result2.push(sub_obj[temp_keys_array[x]]["5. volume"]);
  					}
  					var y_axis_volume_values=[];
					for (var i=0,l=result2.length;i<l;i++) 
					y_axis_volume_values.push(parseFloat(result2[i])); // or parseInt(arr[i]) or Number(arr[i])
					console.log(y_axis_volume_values);
					var today = new Date();
					var date = (today.getMonth()+1)+'/'+today.getDate()+'/'+today.getFullYear();
  					var chart = new Highcharts.Chart({
    				chart: {
    					borderColor : 'grey',
    					borderWidth : 1,
        				renderTo: 'container',
        				align : 'center',
        				zoomType: 'x',
                        panning: true,
                        panKey: 'shift',
                        type : 'area'
    				},
    				title: {
    					text: "Stock Price ("+date+")"
					},
					subtitle: {
    					text : '<a href="https://www.alphavantage.co/">Source: Alpha Vantage </a>'
					},
					legend: {
						enabled: true,
        				align: 'right',
        				verticalAlign: 'top',
        				layout: 'vertical',
        				x: 0,
        				y: 100,
    				},
					xAxis: {
						endOnTick: true,
                        showLastLabel: true,
						type : 'datetime',
						tickInterval: 2,
        				categories: keys_array,
        				labels : {
        					rotation : -45
        				}
    				},
    				yAxis: [
    					{ // Primary yAxis
        					labels: {
            					format: '{value}'
        					},
        					title: {
            					text: 'Stock Price'
        					},
        					lineWidth: 1,
        					minTickInterval : 5
    					}, 
    					{ // Secondary yAxis
        					title: {
            					text: 'Volume'
        					},
        					minTickInterval: 80000000,
                        	max:80000000,
        					lineWidth: 1,
        					opposite: true
    					}
    				],		
    				series: [{
                        data: y_axis_price_values,
                        lineColor: '#f75e59',
                        lineWidth: 1,
                        color: ' #f78888',
                        fillOpacity: 0.5,
                        name: myObj["Meta Data"]["2. Symbol"],
                        marker: {
                            enabled: false
                        },
                        threshold: null
                    },{
                        type: 'column',
                        name: myObj["Meta Data"]["2. Symbol"]+' Volume',
                        color: 'white',
                        borderWidth: 0.3,
                        data: y_axis_volume_values,
                        yAxis: 1
                    }]
					});
				}
				}
				function mySMAChart(){
				//console.log("hi");
				//console.log(document.forms["myform"]["company"].value);
				var myurl = "https://www.alphavantage.co/query?function=SMA&symbol="+"<?php echo $_POST['company'] ?>"+"&interval=weekly&time_period=10&series_type=close&apikey=8WJ1BA0XCFY36KXJ";
				//console.log(myurl);
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.open("GET",myurl,true);
				xmlhttp.send();
				xmlhttp.onload = function() {
  					var myResponse = xmlhttp.response;
  					var myObj = JSON.parse(myResponse);
  					//console.log(myObj["Meta Data"]["2: Indicator"]);	
  					var keys_sub_array = Object.keys(myObj["Technical Analysis: SMA"]);
  					var date_sort_desc = function (date1, date2) {
  											if (date1 > date2) return -1;
  											if (date1 < date2) return 1;
  											return 0;
										};
					keys_sub_array.sort(date_sort_desc);
					var d=new Date(keys_sub_array[0]);  //converts the string into date object
  					var m=d.getMonth()+1; //get the value of month
  					var current_year=d.getFullYear();
     				last_month = m-6;
     				var temp_keys_array=[];
     				for (i=0;i<keys_sub_array.length;i++){
     					var r=new Date(keys_sub_array[i]);
     					var mn=r.getMonth()+1;
     					var yr=r.getFullYear();
     				 	if(mn > last_month && yr==current_year){
     				 		temp_keys_array.push(keys_sub_array[i]);
     					}
     				}
     				var keys_array=[];
     				for (var j=0; j<temp_keys_array.length; j++){
     					keys_array.push(temp_keys_array[j].slice(5,7)+'/'+temp_keys_array[j].slice(8,10));
     				}
  					var sub_obj = myObj["Technical Analysis: SMA"];
  					result = [];
  					for (var x =0; x<temp_keys_array.length; x++){
  						result.push(sub_obj[temp_keys_array[x]]["SMA"]);
  					}
  					var y_axis_values=[];
					for (var i=0,l=result.length;i<l;i++) 
					y_axis_values.push(parseFloat(result[i])); // or parseInt(arr[i]) or Number(arr[i])
  					var chart = new Highcharts.Chart({
    				chart: {
    				type : 'line',
        			renderTo: 'container',
        			align : 'center'
    				},
    				title: {
    				text: myObj["Meta Data"]["2: Indicator"]
					},
					subtitle: {
    				text : '<a href="https://www.alphavantage.co/">Source: Alpha Vantage </a>'
					},
					legend: {
        				align: 'right',
        				verticalAlign: 'top',
        				layout: 'vertical',
        				x: 0,
        				y: 100,
        				labelFormatter: function () {
            				return this.name = myObj["Meta Data"]["1: Symbol"];
       					 }
    				},
					xAxis: {
					type : 'datetime',
					tickInterval: 1,
					dateTimeLabelFormats: {
            			day: '%e of %b'
        			},
        			categories: keys_array,
        			labels : {
        				rotation : -45
        			}
    				},
    				yAxis: {
        				title: {
            			text: 'SMA'
        				}
    				},
    				series: [{
        			data: y_axis_values
    				}]

					});
					/*
					function myFunction() {
           				var win = window.open("https://www.alphavantage.co/",'_blank');
  						win.focus();
       				} 
       				*/
				}
				}
				function myEMAChart(){
				//console.log("hi");
				var myurl = "https://www.alphavantage.co/query?function=EMA&symbol="+"<?php echo $_POST['company'] ?>"+"&interval=weekly&time_period=10&series_type=close&apikey=8WJ1BA0XCFY36KXJ";
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.open("GET",myurl,true);
				xmlhttp.send();
				xmlhttp.onload = function() {
  					var myResponse = xmlhttp.response;
  					var myObj = JSON.parse(myResponse);
  					//console.log(myObj["Meta Data"]["2: Indicator"]);	
  					
  					
  					var keys_sub_array = Object.keys(myObj["Technical Analysis: EMA"]);
  					var date_sort_desc = function (date1, date2) {
  											if (date1 > date2) return -1;
  											if (date1 < date2) return 1;
  											return 0;
										};
					keys_sub_array.sort(date_sort_desc);
					var d=new Date(keys_sub_array[0]);  //converts the string into date object
  					var m=d.getMonth()+1; //get the value of month
  					var current_year=d.getFullYear();
     				last_month = m-6;
     				var temp_keys_array=[];
     				for (i=0;i<keys_sub_array.length;i++){
     					var r=new Date(keys_sub_array[i]);
     					var mn=r.getMonth()+1;
     					var yr=r.getFullYear();
     				 	if(mn > last_month && yr==current_year){
     				 		temp_keys_array.push(keys_sub_array[i]);
     					}
     				}
     				var keys_array=[];
     				for (var j=0; j<temp_keys_array.length; j++){
     					keys_array.push(temp_keys_array[j].slice(5,7)+'/'+temp_keys_array[j].slice(8,10));
     				}
  					var sub_obj = myObj["Technical Analysis: EMA"];
  					result = [];
  					for (var x =0; x<temp_keys_array.length; x++){
  						result.push(sub_obj[temp_keys_array[x]]["EMA"]);
  					}
  					var y_axis_values=[];
					for (var i=0,l=result.length;i<l;i++) 
					y_axis_values.push(parseFloat(result[i])); // or parseInt(arr[i]) or Number(arr[i])
  					var chart = new Highcharts.Chart({
    				chart: {
    				type : 'line',
        			renderTo: 'container',
        			align : 'center'
    				},
    				title: {
    				text: myObj["Meta Data"]["2: Indicator"]
					},
					subtitle: {
    				text : '<a href="https://www.alphavantage.co/">Source: Alpha Vantage </a>'
					},
					legend: {
        				align: 'right',
        				verticalAlign: 'top',
        				layout: 'vertical',
        				x: 0,
        				y: 100,
        				labelFormatter: function () {
            				return this.name = myObj["Meta Data"]["1: Symbol"];
       					 }
    				},
					xAxis: {
					type : 'datetime',
					tickInterval: 1,
					dateTimeLabelFormats: {
            			day: '%e of %b'
        			},
        			categories: keys_array,
        			labels : {
        				rotation : -45
        			}
    				},
    				yAxis: {
        				title: {
            			text: 'EMA'
        				}
    				},
    				series: [{
        			data: y_axis_values
    				}]

					});
					/*
					function myFunction() {
           				var win = window.open("https://www.alphavantage.co/",'_blank');
  						win.focus();
       				} 
       				*/
				}
				}
				function mySTOCHChart(){
				//console.log("hi");
				var myurl = "https://www.alphavantage.co/query?function=STOCH&symbol="+"<?php echo $_POST['company'] ?>"+"&interval=weekly&time_period=10&series_type=close&apikey=8WJ1BA0XCFY36KXJ";
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.open("GET",myurl,true);
				xmlhttp.send();
				xmlhttp.onload = function() {
  					var myResponse = xmlhttp.response;
  					var myObj = JSON.parse(myResponse);
  					//console.log(myObj["Meta Data"]["2: Indicator"]);	
  					
  					
  					var keys_sub_array = Object.keys(myObj["Technical Analysis: STOCH"]);
  					var date_sort_desc = function (date1, date2) {
  											if (date1 > date2) return -1;
  											if (date1 < date2) return 1;
  											return 0;
										};
					keys_sub_array.sort(date_sort_desc);
					var d=new Date(keys_sub_array[0]);  //converts the string into date object
  					var m=d.getMonth()+1; //get the value of month
  					var current_year=d.getFullYear();
     				last_month = m-6;
     				var temp_keys_array=[];
     				for (i=0;i<keys_sub_array.length;i++){
     					var r=new Date(keys_sub_array[i]);
     					var mn=r.getMonth()+1;
     					var yr=r.getFullYear();
     				 	if(mn > last_month && yr==current_year){
     				 		temp_keys_array.push(keys_sub_array[i]);
     					}
     				}
     				var keys_array=[];
     				for (var j=0; j<temp_keys_array.length; j++){
     					keys_array.push(temp_keys_array[j].slice(5,7)+'/'+temp_keys_array[j].slice(8,10));
     				}
  					var sub_obj = myObj["Technical Analysis: STOCH"];
  					result1 = [];
  					for (var x =0; x<temp_keys_array.length; x++){
  						result1.push(sub_obj[temp_keys_array[x]]["SlowK"]);
  					}
  					var y_axis_SlowK_values=[];
					for (var i=0,l=result1.length;i<l;i++) 
					y_axis_SlowK_values.push(parseFloat(result1[i])); // or parseInt(arr[i]) or Number(arr[i])
					result2 = [];
  					for (var x =0; x<temp_keys_array.length; x++){
  						result2.push(sub_obj[temp_keys_array[x]]["SlowD"]);
  					}
  					var y_axis_SlowD_values=[];
					for (var i=0,l=result2.length;i<l;i++) 
					y_axis_SlowD_values.push(parseFloat(result2[i])); // or parseInt(arr[i]) or Number(arr[i])
  					var chart = new Highcharts.Chart({
    				chart: {
    				type : 'line',
        			renderTo: 'container',
        			align : 'center'
    				},
    				title: {
    				text: myObj["Meta Data"]["2: Indicator"]
					},
					subtitle: {
    				text : '<a href="https://www.alphavantage.co/">Source: Alpha Vantage </a>'
					},
					legend: {
        				align: 'right',
        				verticalAlign: 'top',
        				layout: 'vertical',
        				x: 0,
        				y: 100
    				},
					xAxis: {
					type : 'datetime',
					tickInterval: 1,
					dateTimeLabelFormats: {
            			day: '%e of %b'
        			},
        			categories: keys_array,
        			labels : {
        				rotation : -45
        			}
    				},
    				yAxis: {
        				title: {
            			text: 'STOCH'
        				}
    				},
    				series: [{
    					type: 'line',
    					name: myObj["Meta Data"]["1: Symbol"]+"SlowK",
   				 		data: y_axis_SlowK_values
						}, {
    					type: 'line',
    					name: myObj["Meta Data"]["1: Symbol"]+"SlowD",
    					data: y_axis_SlowD_values
					}]

					});
					/*
					function myFunction() {
           				var win = window.open("https://www.alphavantage.co/",'_blank');
  						win.focus();
       				} 
       				*/
				}
				}
				function myRSIChart(){
				//console.log("hi");
				var myurl = "https://www.alphavantage.co/query?function=RSI&symbol="+"<?php echo $_POST['company'] ?>"+"&interval=weekly&time_period=10&series_type=close&apikey=8WJ1BA0XCFY36KXJ";
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.open("GET",myurl,true);
				xmlhttp.send();
				xmlhttp.onload = function() {
  					var myResponse = xmlhttp.response;
  					var myObj = JSON.parse(myResponse);
  					//console.log(myObj["Meta Data"]["2: Indicator"]);	
  					
  					
  					var keys_sub_array = Object.keys(myObj["Technical Analysis: RSI"]);
  					var date_sort_desc = function (date1, date2) {
  											if (date1 > date2) return -1;
  											if (date1 < date2) return 1;
  											return 0;
										};
					keys_sub_array.sort(date_sort_desc);
					var d=new Date(keys_sub_array[0]);  //converts the string into date object
  					var m=d.getMonth()+1; //get the value of month
  					var current_year=d.getFullYear();
     				last_month = m-6;
     				var temp_keys_array=[];
     				for (i=0;i<keys_sub_array.length;i++){
     					var r=new Date(keys_sub_array[i]);
     					var mn=r.getMonth()+1;
     					var yr=r.getFullYear();
     				 	if(mn > last_month && yr==current_year){
     				 		temp_keys_array.push(keys_sub_array[i]);
     					}
     				}
     				var keys_array=[];
     				for (var j=0; j<temp_keys_array.length; j++){
     					keys_array.push(temp_keys_array[j].slice(5,7)+'/'+temp_keys_array[j].slice(8,10));
     				}
  					var sub_obj = myObj["Technical Analysis: RSI"];
  					result = [];
  					for (var x =0; x<temp_keys_array.length; x++){
  						result.push(sub_obj[temp_keys_array[x]]["RSI"]);
  					}
  					var y_axis_values=[];
					for (var i=0,l=result.length;i<l;i++) 
					y_axis_values.push(parseFloat(result[i])); // or parseInt(arr[i]) or Number(arr[i])
  					var chart = new Highcharts.Chart({
    				chart: {
    				type : 'line',
        			renderTo: 'container',
        			align : 'center'
    				},
    				title: {
    				text: myObj["Meta Data"]["2: Indicator"]
					},
					subtitle: {
    				text : '<a href="https://www.alphavantage.co/">Source: Alpha Vantage </a>'
					},
					legend: {
        				align: 'right',
        				verticalAlign: 'top',
        				layout: 'vertical',
        				x: 0,
        				y: 100,
        				labelFormatter: function () {
            				return this.name = myObj["Meta Data"]["1: Symbol"];
       					 }
    				},
					xAxis: {
					type : 'datetime',
					tickInterval: 1,
					dateTimeLabelFormats: {
            			day: '%e of %b'
        			},
        			categories: keys_array,
        			labels : {
        				rotation : -45
        			}
    				},
    				yAxis: {
        				title: {
            			text: 'RSI'
        				}
    				},
    				series: [{
        			data: y_axis_values
    				}]

					});
					/*
					function myFunction() {
           				var win = window.open("https://www.alphavantage.co/",'_blank');
  						win.focus();
       				} 
       				*/
				}
				}
				function myADXChart(){
				//console.log("hi");
				var myurl = "https://www.alphavantage.co/query?function=ADX&symbol="+"<?php echo $_POST['company'] ?>"+"&interval=weekly&time_period=10&series_type=close&apikey=8WJ1BA0XCFY36KXJ";
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.open("GET",myurl,true);
				xmlhttp.send();
				xmlhttp.onload = function() {
  					var myResponse = xmlhttp.response;
  					var myObj = JSON.parse(myResponse);
  					//console.log(myObj["Meta Data"]["2: Indicator"]);	
  					
  					
  					var keys_sub_array = Object.keys(myObj["Technical Analysis: ADX"]);
  					var date_sort_desc = function (date1, date2) {
  											if (date1 > date2) return -1;
  											if (date1 < date2) return 1;
  											return 0;
										};
					keys_sub_array.sort(date_sort_desc);
					var d=new Date(keys_sub_array[0]);  //converts the string into date object
  					var m=d.getMonth()+1; //get the value of month
  					var current_year=d.getFullYear();
     				last_month = m-6;
     				var temp_keys_array=[];
     				for (i=0;i<keys_sub_array.length;i++){
     					var r=new Date(keys_sub_array[i]);
     					var yr=r.getFullYear();
     					var mn=r.getMonth()+1;
     				 	if(mn > last_month && yr==current_year){
     				 		temp_keys_array.push(keys_sub_array[i]);
     					}
     				}
     				var keys_array=[];
     				for (var j=0; j<temp_keys_array.length; j++){
     					keys_array.push(temp_keys_array[j].slice(5,7)+'/'+temp_keys_array[j].slice(8,10));
     				}
  					var sub_obj = myObj["Technical Analysis: ADX"];
  					result = [];
  					for (var x =0; x<temp_keys_array.length; x++){
  						result.push(sub_obj[temp_keys_array[x]]["ADX"]);
  					}
  					var y_axis_values=[];
					for (var i=0,l=result.length;i<l;i++) 
					y_axis_values.push(parseFloat(result[i])); // or parseInt(arr[i]) or Number(arr[i])
  					var chart = new Highcharts.Chart({
    				chart: {
    				type : 'line',
        			renderTo: 'container',
        			align : 'center'
    				},
    				title: {
    				text: myObj["Meta Data"]["2: Indicator"]
					},
					subtitle: {
    				text : '<a href="https://www.alphavantage.co/">Source: Alpha Vantage </a>'
					},
					legend: {
        				align: 'right',
        				verticalAlign: 'top',
        				layout: 'vertical',
        				x: 0,
        				y: 100,
        				labelFormatter: function () {
            				return this.name = myObj["Meta Data"]["1: Symbol"];
       					 }
    				},
					xAxis: {
					type : 'datetime',
					tickInterval: 1,
					dateTimeLabelFormats: {
            			day: '%e of %b'
        			},
        			categories: keys_array,
        			labels : {
        				rotation : -45
        			}
    				},
    				yAxis: {
        				title: {
            			text: 'ADX'
        				}
    				},
    				series: [{
        			data: y_axis_values
    				}]

					});
					/*
					function myFunction() {
           				var win = window.open("https://www.alphavantage.co/",'_blank');
  						win.focus();
       				} 
       				*/
				}
				}
				function myCCIChart(){
				//console.log("hi");
				var myurl = "https://www.alphavantage.co/query?function=CCI&symbol="+"<?php echo $_POST['company'] ?>"+"&interval=weekly&time_period=10&series_type=close&apikey=8WJ1BA0XCFY36KXJ";
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.open("GET",myurl,true);
				xmlhttp.send();
				xmlhttp.onload = function() {
  					var myResponse = xmlhttp.response;
  					var myObj = JSON.parse(myResponse);
  					//console.log(myObj["Meta Data"]["2: Indicator"]);	
  					
  					
  					var keys_sub_array = Object.keys(myObj["Technical Analysis: CCI"]);
  					var date_sort_desc = function (date1, date2) {
  											if (date1 > date2) return -1;
  											if (date1 < date2) return 1;
  											return 0;
										};
					keys_sub_array.sort(date_sort_desc);
					var d=new Date(keys_sub_array[0]);  //converts the string into date object
  					var m=d.getMonth()+1; //get the value of month
  					var current_year=d.getFullYear();
     				last_month = m-6;
     				var temp_keys_array=[];
     				for (i=0;i<keys_sub_array.length;i++){
     					var r=new Date(keys_sub_array[i]);
     					var mn=r.getMonth()+1;
     					var yr=r.getFullYear();
     				 	if(mn > last_month && yr==current_year){
     				 		temp_keys_array.push(keys_sub_array[i]);
     					}
     				}
     				var keys_array=[];
     				for (var j=0; j<temp_keys_array.length; j++){
     					keys_array.push(temp_keys_array[j].slice(5,7)+'/'+temp_keys_array[j].slice(8,10));
     				}
  					var sub_obj = myObj["Technical Analysis: CCI"];
  					result = [];
  					for (var x =0; x<temp_keys_array.length; x++){
  						result.push(sub_obj[temp_keys_array[x]]["CCI"]);
  					}
  					var y_axis_values=[];
					for (var i=0,l=result.length;i<l;i++) 
					y_axis_values.push(parseFloat(result[i])); // or parseInt(arr[i]) or Number(arr[i])
  					var chart = new Highcharts.Chart({
    				chart: {
    				type : 'line',
        			renderTo: 'container',
        			align : 'center'
    				},
    				title: {
    				text: myObj["Meta Data"]["2: Indicator"]
					},
					subtitle: {
    				text : '<a href="https://www.alphavantage.co/">Source: Alpha Vantage </a>'
					},
					legend: {
        				align: 'right',
        				verticalAlign: 'top',
        				layout: 'vertical',
        				x: 0,
        				y: 100,
        				labelFormatter: function () {
            				return this.name = myObj["Meta Data"]["1: Symbol"];
       					 }
    				},
					xAxis: {
					type : 'datetime',
					tickInterval: 1,
					dateTimeLabelFormats: {
            			day: '%e of %b'
        			},
        			categories: keys_array,
        			labels : {
        				rotation : -45
        			}
    				},
    				yAxis: {
        				title: {
            			text: 'CCI'
        				}
    				},
    				series: [{
        			data: y_axis_values
    				}]

					});
					/*
					function myFunction() {
           				var win = window.open("https://www.alphavantage.co/",'_blank');
  						win.focus();
       				} 
       				*/
				}
				}
				function myBBANDSChart(){
				//console.log("hi");
				var myurl = "https://www.alphavantage.co/query?function=BBANDS&symbol="+"<?php echo $_POST['company'] ?>"+"&interval=weekly&time_period=10&series_type=close&apikey=8WJ1BA0XCFY36KXJ";
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.open("GET",myurl,true);
				xmlhttp.send();
				xmlhttp.onload = function() {
  					var myResponse = xmlhttp.response;
  					var myObj = JSON.parse(myResponse);
  					var keys_sub_array = Object.keys(myObj["Technical Analysis: BBANDS"]);
  					var date_sort_desc = function (date1, date2) {
  											if (date1 > date2) return -1;
  											if (date1 < date2) return 1;
  											return 0;
										};
					keys_sub_array.sort(date_sort_desc);
					console.log(keys_sub_array);
					var d=new Date(keys_sub_array[0]);  //converts the string into date object
  					var m=d.getMonth()+1; //get the value of month
  					var current_year=d.getFullYear();
     				last_month = m-6;
     				var temp_keys_array=[];
     				for (i=0;i<keys_sub_array.length;i++){
     					var r=new Date(keys_sub_array[i]);
     					var mn=r.getMonth()+1;
     					var yr=r.getFullYear();
     				 	if(mn > last_month && yr==current_year){
     				 		temp_keys_array.push(keys_sub_array[i]);
     					}
     				}
     				//console.log(keys_array);
     				var keys_array=[];
     				for (var j=0; j<temp_keys_array.length; j++){
     					keys_array.push(temp_keys_array[j].slice(5,7)+'/'+temp_keys_array[j].slice(8,10));
     				}
  					var sub_obj = myObj["Technical Analysis: BBANDS"];
  					result1 = [];
  					for (var x =0; x<temp_keys_array.length; x++){
  						result1.push(sub_obj[temp_keys_array[x]]["Real Upper Band"]);
  					}
  					var y_axis_RUB_values=[];
					for (var i=0,l=result1.length;i<l;i++) 
					y_axis_RUB_values.push(parseFloat(result1[i])); // or parseInt(arr[i]) or Number(arr[i])
					result2 = [];
  					for (var x =0; x<temp_keys_array.length; x++){
  						result2.push(sub_obj[temp_keys_array[x]]["Real Lower Band"]);
  					}
  					var y_axis_RLB_values=[];
					for (var i=0,l=result2.length;i<l;i++) 
					y_axis_RLB_values.push(parseFloat(result2[i])); // or parseInt(arr[i]) or Number(arr[i])
					result3 = [];
  					for (var x =0; x<temp_keys_array.length; x++){
  						result3.push(sub_obj[temp_keys_array[x]]["Real Middle Band"]);
  					}
  					var y_axis_RMB_values=[];
					for (var i=0,l=result3.length;i<l;i++) 
					y_axis_RMB_values.push(parseFloat(result3[i])); // or parseInt(arr[i]) or Number(arr[i])
  					var chart = new Highcharts.Chart({
    				chart: {
    				type : 'line',
        			renderTo: 'container',
        			align : 'center'
    				},
    				title: {
    				text: myObj["Meta Data"]["2: Indicator"]
					},
					subtitle: {
    				text : '<a href="https://www.alphavantage.co/">Source: Alpha Vantage </a>'
					},
					legend: {
        				align: 'right',
        				verticalAlign: 'top',
        				layout: 'vertical',
        				x: 0,
        				y: 100
    				},
					xAxis: {
					type : 'datetime',
					tickInterval: 1,
					dateTimeLabelFormats: {
            			day: '%e of %b'
        			},
        			categories: keys_array,
        			labels : {
        				rotation : -45
        			}
    				},
    				yAxis: {
        				title: {
            			text: 'BBANDS'
        				}
    				},
    				series: [{
    					type: 'line',
    					name: myObj["Meta Data"]["1: Symbol"]+"Real Middle Band",
   				 		data: y_axis_RMB_values
						}, {
						type: 'line',
    					name: myObj["Meta Data"]["1: Symbol"]+"Real Upper Band",
   				 		data: y_axis_RUB_values
						}, {
    					type: 'line',
    					name: myObj["Meta Data"]["1: Symbol"]+"Real Lower Band",
    					data: y_axis_RLB_values
					}]

					});
					/*
					function myFunction() {
           				var win = window.open("https://www.alphavantage.co/",'_blank');
  						win.focus();
       				} 
       				*/
				}
				}
				function myMACDChart(){
				//console.log("hi");
				var myurl = "https://www.alphavantage.co/query?function=MACD&symbol="+"<?php echo $_POST['company'] ?>"+"&interval=weekly&time_period=10&series_type=close&apikey=8WJ1BA0XCFY36KXJ";
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.open("GET",myurl,true);
				xmlhttp.send();
				xmlhttp.onload = function() {
  					var myResponse = xmlhttp.response;
  					var myObj = JSON.parse(myResponse);
  					//console.log(myObj["Meta Data"]["2: Indicator"]);	
  					
  					
  					var keys_sub_array = Object.keys(myObj["Technical Analysis: MACD"]);
  					var date_sort_desc = function (date1, date2) {
  											if (date1 > date2) return -1;
  											if (date1 < date2) return 1;
  											return 0;
										};
					keys_sub_array.sort(date_sort_desc);
					var d=new Date(keys_sub_array[0]);  //converts the string into date object
  					var m=d.getMonth()+1; //get the value of month
  					var current_year=d.getFullYear();
     				last_month = m-6;
     				var temp_keys_array=[];
     				for (i=0;i<keys_sub_array.length;i++){
     					var r=new Date(keys_sub_array[i]);
     					var mn=r.getMonth()+1;
     					var yr=r.getFullYear();
     				 	if(mn > last_month && yr==current_year){
     				 		temp_keys_array.push(keys_sub_array[i]);
     					}
     				}
     				var keys_array=[];
     				for (var j=0; j<temp_keys_array.length; j++){
     					keys_array.push(temp_keys_array[j].slice(5,7)+'/'+temp_keys_array[j].slice(8,10));
     				}
  					var sub_obj = myObj["Technical Analysis: MACD"];
  					result1 = [];
  					for (var x =0; x<temp_keys_array.length; x++){
  						result1.push(sub_obj[temp_keys_array[x]]["MACD"]);
  					}
  					var y_axis_MACD_values=[];
					for (var i=0,l=result1.length;i<l;i++) 
					y_axis_MACD_values.push(parseFloat(result1[i])); // or parseInt(arr[i]) or Number(arr[i])
					result2 = [];
  					for (var x =0; x<temp_keys_array.length; x++){
  						result2.push(sub_obj[temp_keys_array[x]]["MACD_Hist"]);
  					}
  					var y_axis_MACD_Hist_values=[];
					for (var i=0,l=result2.length;i<l;i++) 
					y_axis_MACD_Hist_values.push(parseFloat(result2[i])); // or parseInt(arr[i]) or Number(arr[i])
					result3 = [];
  					for (var x =0; x<temp_keys_array.length; x++){
  						result3.push(sub_obj[temp_keys_array[x]]["MACD_Signal"]);
  					}
  					var y_axis_MACD_Signal_values=[];
					for (var i=0,l=result3.length;i<l;i++) 
					y_axis_MACD_Signal_values.push(parseFloat(result3[i])); // or parseInt(arr[i]) or Number(arr[i])
  					var chart = new Highcharts.Chart({
    				chart: {
    				type : 'line',
        			renderTo: 'container',
        			align : 'center'
    				},
    				title: {
    				text: myObj["Meta Data"]["2: Indicator"]
					},
					subtitle: {
    				text : '<a href="https://www.alphavantage.co/">Source: Alpha Vantage </a>'
					},
					legend: {
        				align: 'right',
        				verticalAlign: 'top',
        				layout: 'vertical',
        				x: 0,
        				y: 100
    				},
					xAxis: {
					type : 'datetime',
					tickInterval: 1,
					dateTimeLabelFormats: {
            			day: '%e of %b'
        			},
        			categories: keys_array,
        			labels : {
        				rotation : -45
        			}
    				},
    				yAxis: {
        				title: {
            			text: 'MACD'
        				}
    				},
    				series: [{
    					type: 'line',
    					name: myObj["Meta Data"]["1: Symbol"]+"MACD",
   				 		data: y_axis_MACD_values
						}, {
						type: 'line',
    					name: myObj["Meta Data"]["1: Symbol"]+"MACD_Hist",
   				 		data: y_axis_MACD_Hist_values
						}, {
    					type: 'line',
    					name: myObj["Meta Data"]["1: Symbol"]+"MACD_Signal",
    					data: y_axis_MACD_Signal_values
					}]

					});
					/*
					function myFunction() {
           				var win = window.open("https://www.alphavantage.co/",'_blank');
  						win.focus();
       				} 
       				*/
				}
				}
      		</script>
      		
			<?php
        	if(isset($_POST['submit'])){
        	echo "<p>Click here to show stock news</p>";
 			echo "<img id='mynews' src='http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Down.png' height='16' width='22' onclick='news()'>";
            $var = trim($_POST["company"]);
                $url = "https://seekingalpha.com/api/sa/combined/".$var.".xml";
                //echo $url;
                $xml_object = file_get_contents($url);
                //echo gettype($xml_object);
				//echo $xml_object;
                $xml = simplexml_load_string($xml_object);
                $json_string = json_encode($xml);
                $json_array = json_decode($json_string,true);
                //echo $json_array["channel"]["item"][0]["title"];
                 echo "<table align=center border=1 cellpadding=10 width=80% id='newstable' style = 'display:none'>";
                 for ($i = 0; $i < 5; $i++ ){
                 	echo "<tr><td class='table1'>";
                 	$temp = $json_array["channel"]["item"][$i]["title"];
                 	$templink = $json_array["channel"]["item"][$i]["link"];
                 	$pubdate = $json_array["channel"]["item"][$i]["pubDate"];
                 	//echo typeof($pubdate);
                 	$tempdate = substr($pubdate,0,-5);
                 	//$formatteddate = date_format($tempdate,"D, d M Y H:i:s");
                	echo "<a href='$templink'>$temp&nbsp&nbsp&nbsp&nbsp</a>";
                	echo "<span style="."margin-left:5px".">Publicated Time: $tempdate </span>";
                    echo "</td>";
                	
                 }
                 echo "</table>";
                 
        }       
        ?>
        </div>
		</body>
</html>
