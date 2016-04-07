<?PHP
	$path=$_SERVER['DOCUMENT_ROOT'];
	require_once $path.'/php/dbconn.php';
	include $path.'/php/common.php';
?>

<!DOCTYPE HTML>
<html>
	<head>
		<?PHP getHtmlHead(); ?>

		<script>
                        $(document).ready(function(){
                                //setTimeout('location.reload()',60000); //executes page reload after 60 sec.

                                setInterval(function(){
                                        var tmpImg = $('#metrics_1a').attr('src');
                                        $('#metrics_1a').attr('src',tmpImg);
                                        var tmpImg = $('#metrics_1b').attr('src');
                                        $('#metrics_1b').attr('src',tmpImg);
                                        var tmpImg = $('#metrics_1c').attr('src');
                                        $('#metrics_1c').attr('src',tmpImg);
                                        console.log('row1 updated');

                                        var tmpImg = $('#metrics_2a').attr('src');
                                        $('#metrics_2a').attr('src',tmpImg);
                                        var tmpImg = $('#metrics_2b').attr('src');
                                        $('#metrics_2b').attr('src',tmpImg);
                                        var tmpImg = $('#metrics_2c').attr('src');
                                        $('#metrics_2c').attr('src',tmpImg);
                                        console.log('row2 updated');

                                        var tmpImg = $('#metrics_3a').attr('src');
                                        $('#metrics_3a').attr('src',tmpImg);
                                        var tmpImg = $('#metrics_3b').attr('src');
                                        $('#metrics_3b').attr('src',tmpImg);
                                        var tmpImg = $('#metrics_3c').attr('src');
                                        $('#metrics_3c').attr('src',tmpImg);
                                        console.log('row3 updated');
                                        
                                        var tmpImg = $('#metrics_4a').attr('src');
                                        $('#metrics_4a').attr('src',tmpImg);
                                        var tmpImg = $('#metrics_4b').attr('src');
                                        $('#metrics_4b').attr('src',tmpImg);
                                        var tmpImg = $('#metrics_4c').attr('src');
                                        $('#metrics_4c').attr('src',tmpImg);
                                        console.log('row4 updated');

                                        var tmpImg = $('#metrics_5a').attr('src');
                                        $('#metrics_5a').attr('src',tmpImg);
                                        var tmpImg = $('#metrics_5b').attr('src');
                                        $('#metrics_5b').attr('src',tmpImg);
                                        var tmpImg = $('#metrics_5c').attr('src');
                                        $('#metrics_5c').attr('src',tmpImg);
                                        console.log('row5 updated');

                                        var tmpImg = $('#metrics_6a').attr('src');
                                        $('#metrics_6a').attr('src',tmpImg);
                                        var tmpImg = $('#metrics_6b').attr('src');
                                        $('#metrics_6b').attr('src',tmpImg);
                                        var tmpImg = $('#metrics_6c').attr('src');
                                        $('#metrics_6c').attr('src',tmpImg);
                                        console.log('row6 updated');

                                        var tmpImg = $('#metrics_7a').attr('src');
                                        $('#metrics_7a').attr('src',tmpImg);
                                        var tmpImg = $('#metrics_7b').attr('src');
                                        $('#metrics_7b').attr('src',tmpImg);
                                        var tmpImg = $('#metrics_7c').attr('src');
                                        $('#metrics_7c').attr('src',tmpImg);
                                        console.log('row7 updated');

                                        var tmpImg = $('#metrics_8a').attr('src');
                                        $('#metrics_8a').attr('src',tmpImg);
                                        var tmpImg = $('#metrics_8b').attr('src');
                                        $('#metrics_8b').attr('src',tmpImg);
                                        var tmpImg = $('#metrics_8c').attr('src');
                                        $('#metrics_8c').attr('src',tmpImg);
                                        console.log('row8 updated');

                                        var tmpImg = $('#metrics_9a').attr('src');
                                        $('#metrics_9a').attr('src',tmpImg);
                                        var tmpImg = $('#metrics_9b').attr('src');
                                        $('#metrics_9b').attr('src',tmpImg);
                                        var tmpImg = $('#metrics_9c').attr('src');
                                        $('#metrics_9c').attr('src',tmpImg);
                                        console.log('row9 updated');

                                        var tmpImg = $('#metrics_10a').attr('src');
                                        $('#metrics_10a').attr('src',tmpImg);
                                        var tmpImg = $('#metrics_10b').attr('src');
                                        $('#metrics_10b').attr('src',tmpImg);
                                        var tmpImg = $('#metrics_10c').attr('src');
                                        $('#metrics_10c').attr('src',tmpImg);
                                        console.log('row10 updated');

                                        var tmpImg = $('#metrics_11a').attr('src');
                                        $('#metrics_11a').attr('src',tmpImg);
                                        var tmpImg = $('#metrics_11b').attr('src');
                                        $('#metrics_11b').attr('src',tmpImg);
                                        var tmpImg = $('#metrics_11c').attr('src');
                                        $('#metrics_11c').attr('src',tmpImg);
                                        console.log('row11 updated');

					console.log('--------------------------------------------');
                                },60000);
                        });
                </script>


		<style>
                        #main{
                                margin: 0 auto;
                                /*text-align: right;*/
                        }
                        .metricsRow{
                                width:100%;
                                text-align: center;
                        }
                        .metricGraph{
                                margin:0px;
                                display:inline-block;
				background-color:white;
                                border: 1px #ddd solid;
				box-shadow: inset 0 1px 0 rgba(255,255,255,0.15),0 1px 5px rgba(0,0,0,0.075);
                        }
                        .metricGraph img{
                                max-width: 595px;
                                /*min-width: 350px;*/
                                height: auto;
                                width: 595px;
                                margin: 0px;
                        }
                        .metricGraphHeading{
                                max-width: 595px;
                                height: auto;
                                width: 595px;
                                margin: 0px;
                        }
			.rotate-ccw-90 {
				filter:  progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);  /* IE6,IE7 */
				-ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)"; /* IE8 */
				-moz-transform: rotate(-90.0deg);  /* FF3.5+ */
				-ms-transform: rotate(-90.0deg);  /* IE9+ */
				-o-transform: rotate(-90.0deg);  /* Opera 10.5 */
				-webkit-transform: rotate(-90.0deg);  /* Safari 3.1+, Chrome */
				transform: rotate(-90.0deg);  /* Standard */
			}
                </style>


	</head>

	<body>
		<?PHP getHeader(); ?>
		<div id="pageContainer">
			<div id="main">
				<div id="metricsTop" class="metricsRow">
					<!-- <div class='metricGraph'><a href="http://sjc.trend.dc.dotomi.net/graph_all_periods.php?title=SJC+BIDS&vl=&x=&n=&hreg%5B%5D=rtb&mreg%5B%5D=Bid_Summary&gtype=stack&glegend=show&aggregate=1" target="_blank"><img id="metrics_1a" src="http://sjc.trend.dc.dotomi.net/graph.php?r=4hr&z=xlarge&title=SJC+BIDS&vl=&x=&n=&hreg[]=rtb&mreg[]=Bid_Summary&gtype=stack&glegend=hide&aggregate=1" /></a></div> -->
					<!-- <div class='metricGraph'><a href="http://iad.trend.dc.dotomi.net/graph_all_periods.php?title=IAD+BIDS&vl=&x=&n=&hreg%5B%5D=rtb&mreg%5B%5D=Bid_Summary&gtype=stack&glegend=show&aggregate=1" target="_blank"><img id="metrics_1b" src="http://iad.trend.dc.dotomi.net/graph.php?r=4hr&z=xlarge&title=IAD+BIDS&vl=&x=&n=&hreg[]=rtb&mreg[]=Bid_Summary&gtype=stack&glegend=hide&aggregate=1" /></a></div> -->
					<div class='metricGraph'>SJC DMA Success/Timeout Last Day<br><a href="http://tsdb2.dc.dotomi.net/#start=24h-ago&m=sum:rate:dma.requests%7Bresponse_code=TIMEOUT,dc=S%7D&o=&m=sum:rate:dma.requests%7Bresponse_code=SUCCESS,dc=S%7D&o=&yrange=%5B0:%5D&key=out%20center%20top%20box&wxh=1825x775&autoreload=30" target="_blank"><img id="metrics_1a" src="http://tsdb2.dc.dotomi.net/q?start=24h-ago&m=sum:rate:dma.requests%7Bresponse_code=TIMEOUT,dc=S%7D&o=&m=sum:rate:dma.requests%7Bresponse_code=SUCCESS,dc=S%7D&o=&yrange=%5B0:%5D&key=out%20center%20top%20box&wxh=595x282&png" /></a></div>
					<div class='metricGraph'>IAD DMA Success/Timeout Last Day<br><a href="http://tsdb2.dc.dotomi.net/#start=24h-ago&m=sum:rate:dma.requests%7Bresponse_code=TIMEOUT,dc=I%7D&o=&m=sum:rate:dma.requests%7Bresponse_code=SUCCESS,dc=I%7D&o=&yrange=%5B0:%5D&key=out%20center%20top%20box&wxh=1825x775&autoreload=30" target="_blank"><img id="metrics_1b" src="http://tsdb2.dc.dotomi.net/q?start=24h-ago&m=sum:rate:dma.requests%7Bresponse_code=TIMEOUT,dc=I%7D&o=&m=sum:rate:dma.requests%7Bresponse_code=SUCCESS,dc=I%7D&o=&yrange=%5B0:%5D&key=out%20center%20top%20box&wxh=595x282&png" /></a></div>
					<div class='metricGraph'>AMS DMA Success/Timeout Last Day<br><a href="http://tsdb2.dc.dotomi.net/#start=24h-ago&m=sum:rate:dma.requests%7Bresponse_code=TIMEOUT,dc=A%7D&o=&m=sum:rate:dma.requests%7Bresponse_code=SUCCESS,dc=A%7D&o=&yrange=%5B0:%5D&key=out%20center%20top%20box&wxh=1825x775&autoreload=30" target="_blank"><img id="metrics_1c" src="http://tsdb2.dc.dotomi.net/q?start=24h-ago&m=sum:rate:dma.requests%7Bresponse_code=TIMEOUT,dc=A%7D&o=&m=sum:rate:dma.requests%7Bresponse_code=SUCCESS,dc=A%7D&o=&yrange=%5B0:%5D&key=out%20center%20top%20box&wxh=595x282&png" /></a></div>

					<!-- <div class='metricGraph'><a href="http://sjc.trend.dc.dotomi.net/graph_all_periods.php?title=SJC+DCS+Heap+Mem+Usage&vl=&x=&n=&hreg%5B%5D=dcs&mreg%5B%5D=HeapMemoryUsage.used&gtype=line&glegend=show&aggregate=1" target="_blank"><img id="metrics_6a" src="http://sjc.trend.dc.dotomi.net/graph.php?r=4hr&z=xlarge&title=SJC+DCS+Heap+Mem+Usage&vl=&x=&n=&hreg[]=dcs&mreg[]=HeapMemoryUsage.used&gtype=line&glegend=hide&aggregate=1&embed=1&_=1375815166907" /></a></div>
					<div class='metricGraph'><a href="http://iad.trend.dc.dotomi.net/graph_all_periods.php?title=IAD+DCS+Heap+Mem+Usage&vl=&x=&n=&hreg%5B%5D=dcs&mreg%5B%5D=HeapMemoryUsage.used&gtype=line&glegend=show&aggregate=1" target="_blank"><img id="metrics_6b" src="http://iad.trend.dc.dotomi.net/graph.php?r=4hr&z=xlarge&title=IAD+DCS+Heap+Mem+Usage&vl=&x=&n=&hreg[]=dcs&mreg[]=HeapMemoryUsage.used&gtype=line&glegend=hide&aggregate=1&embed=1&_=1375815166907" /></a></div>
					<div class='metricGraph'><a href="http://ams.trend.dc.dotomi.net/graph_all_periods.php?title=AMS+DCS+Heap+Mem+Usage&vl=&x=&n=&hreg%5B%5D=dcs&mreg%5B%5D=HeapMemoryUsage.used&gtype=line&glegend=show&aggregate=1" target="_blank"><img id="metrics_6c" src="http://ams.trend.dc.dotomi.net/graph.php?r=4hr&z=xlarge&title=AMS+DCS+Heap+Mem+Usage&vl=&x=&n=&hreg[]=dcs&mreg[]=HeapMemoryUsage.used&gtype=line&glegend=hide&aggregate=1&embed=1&_=1375815166907" /></a></div> -->
				
					<!-- <div class='metricGraph'><a href="http://sjc.trend.dc.dotomi.net/graph_all_periods.php?title=SJC+RTB+Heap+Mem+Usage&vl=&x=&n=&hreg%5B%5D=rtb&mreg%5B%5D=HeapMemoryUsage.used&gtype=line&glegend=show&aggregate=1" target="_blank"><img id="metrics_2a" src="http://sjc.trend.dc.dotomi.net/graph.php?r=4hr&z=xlarge&title=SJC+RTB+Heap+Mem+Usage&vl=&x=&n=&hreg[]=rtb&mreg[]=HeapMemoryUsage.used&gtype=line&glegend=hide&aggregate=1&embed=1&_=1375815166907" /></a></div>
					<div class='metricGraph'><a href="http://iad.trend.dc.dotomi.net/graph_all_periods.php?title=IAD+RTB+Heap+Mem+Usage&vl=&x=&n=&hreg%5B%5D=rtb&mreg%5B%5D=HeapMemoryUsage.used&gtype=line&glegend=show&aggregate=1" target="_blank"><img id="metrics_2b" src="http://iad.trend.dc.dotomi.net/graph.php?r=4hr&z=xlarge&title=IAD+RTB+Heap+Mem+Usage&vl=&x=&n=&hreg[]=rtb&mreg[]=HeapMemoryUsage.used&gtype=line&glegend=hide&aggregate=1&embed=1&_=1375815166907" /></a></div>
					<div class='metricGraph'><a href="http://ams.trend.dc.dotomi.net/graph_all_periods.php?title=AMS+RTB+Heap+Mem+Usage&vl=&x=&n=&hreg%5B%5D=rtb&mreg%5B%5D=HeapMemoryUsage.used&gtype=line&glegend=show&aggregate=1" target="_blank"><img id="metrics_2c" src="http://ams.trend.dc.dotomi.net/graph.php?r=4hr&z=xlarge&title=AMS+RTB+Heap+Mem+Usage&vl=&x=&n=&hreg[]=rtb&mreg[]=HeapMemoryUsage.used&gtype=line&glegend=hide&aggregate=1&embed=1&_=1375815166907" /></a></div> -->
				
					<div class='metricGraph'><a href="http://sjc.trend.dc.dotomi.net/graph_all_periods.php?title=SJC+NSY+Heap+Mem+Usage&vl=&x=&n=&hreg%5B%5D=nsy&mreg%5B%5D=HeapMemoryUsage.used&gtype=line&glegend=show&aggregate=1" target="_blank"><img id="metrics_3a" src="http://sjc.trend.dc.dotomi.net/graph.php?r=4hr&z=xlarge&title=SJC+NSY+Heap+Mem+Usage&vl=&x=&n=&hreg[]=nsy&mreg[]=HeapMemoryUsage.used&gtype=line&glegend=hide&aggregate=1&embed=1&_=1375815166907" /></a></div>
					<div class='metricGraph'><a href="http://iad.trend.dc.dotomi.net/graph_all_periods.php?title=IAD+NSY+Heap+Mem+Usage&vl=&x=&n=&hreg%5B%5D=nsy&mreg%5B%5D=HeapMemoryUsage.used&gtype=line&glegend=show&aggregate=1" target="_blank"><img id="metrics_3b" src="http://iad.trend.dc.dotomi.net/graph.php?r=4hr&z=xlarge&title=IAD+NSY+Heap+Mem+Usage&vl=&x=&n=&hreg[]=nsy&mreg[]=HeapMemoryUsage.used&gtype=line&glegend=hide&aggregate=1&embed=1&_=1375815166907" /></a></div>
					<div class='metricGraph'><a href="http://ams.trend.dc.dotomi.net/graph_all_periods.php?title=AMS+NSY+Heap+Mem+Usage&vl=&x=&n=&hreg%5B%5D=nsy&mreg%5B%5D=HeapMemoryUsage.used&gtype=line&glegend=show&aggregate=1" target="_blank"><img id="metrics_3c" src="http://ams.trend.dc.dotomi.net/graph.php?r=4hr&z=xlarge&title=AMS+NSY+Heap+Mem+Usage&vl=&x=&n=&hreg[]=nsy&mreg[]=HeapMemoryUsage.used&gtype=line&glegend=hide&aggregate=1&embed=1&_=1375815166907" /></a></div>

					<!-- <div class='metricGraph'><a href="http://sjc.trend.dc.dotomi.net/graph_all_periods.php?title=SJC+RTB+Bid+Summary&vl=&x=&n=&hreg%5B%5D=rtb&mreg%5B%5D=Bid_Summary&gtype=stack&glegend=show&aggregate=1" target="_blank"><img id="metrics_8a" src="http://sjc.trend.dc.dotomi.net/graph.php?r=day&z=xlarge&title=SJC+RTB+Bid+Summary&vl=&x=&n=&hreg[]=rtb&mreg[]=Bid_Summary&gtype=stack&glegend=hide&aggregate=1&embed=1&_=1375815166907" /></a></div>
					<div class='metricGraph'><a href="http://iad.trend.dc.dotomi.net/graph_all_periods.php?title=IAD+RTB+Bid+Summary&vl=&x=&n=&hreg%5B%5D=rtb&mreg%5B%5D=Bid_Summary&gtype=stack&glegend=show&aggregate=1" target="_blank"><img id="metrics_8b" src="http://iad.trend.dc.dotomi.net/graph.php?r=day&z=xlarge&title=IAD+RTB+Bid+Summary&vl=&x=&n=&hreg[]=rtb&mreg[]=Bid_Summary&gtype=stack&glegend=hide&aggregate=1&embed=1&_=1375815166907" /></a></div>
					<div class='metricGraph'><a href="http://ams.trend.dc.dotomi.net/graph_all_periods.php?title=AMS+RTB+Bid+Summary&vl=&x=&n=&hreg%5B%5D=rtb&mreg%5B%5D=Bid_Summary&gtype=stack&glegend=show&aggregate=1" target="_blank"><img id="metrics_8c" src="http://ams.trend.dc.dotomi.net/graph.php?r=day&z=xlarge&title=AMS+RTB+Bid+Summary&vl=&x=&n=&hreg[]=rtb&mreg[]=Bid_Summary&gtype=stack&glegend=hide&aggregate=1&embed=1&_=1375815166907" /></a></div> -->

					<div class='metricGraph'>SJC RTB Bid Summary Last Day<br><a href="http://tsdb2.dc.dotomi.net/#start=24h-ago&m=sum:rate:rtb.requests%7Bdc=S%7D&o=&yrange=%5B0:%5D&key=out%20center%20top%20horiz%20box&wxh=1785x650&autoreload=30" target="_blank"><img id="metrics_8a" src="http://tsdb2.dc.dotomi.net/q?start=24h-ago&m=sum:rate:rtb.requests%7Bdc=S,response_type=BID%7D&o=&yrange=%5B0:%5D&nokey&wxh=595x282&png" /></a></div>
					<div class='metricGraph'>IAD RTB Bid Summary Last Day<br><a href="http://tsdb2.dc.dotomi.net/#start=24h-ago&m=sum:rate:rtb.requests%7Bdc=I%7D&o=&yrange=%5B0:%5D&key=out%20center%20top%20horiz%20box&wxh=1785x650&autoreload=30" target="_blank"><img id="metrics_8b" src="http://tsdb2.dc.dotomi.net/q?start=24h-ago&m=sum:rate:rtb.requests%7Bdc=I,response_type=BID%7D&o=&yrange=%5B0:%5D&nokey&wxh=595x282&png" /></a></div>
					<div class='metricGraph'>AMS RTB Bid Summary Last Day<br><a href="http://tsdb2.dc.dotomi.net/#start=24h-ago&m=sum:rate:rtb.requests%7Bdc=A%7D&o=&yrange=%5B0:%5D&key=out%20center%20top%20horiz%20box&wxh=1785x650&autoreload=30" target="_blank"><img id="metrics_8c" src="http://tsdb2.dc.dotomi.net/q?start=24h-ago&m=sum:rate:rtb.requests%7Bdc=A,response_type=BID%7D&o=&yrange=%5B0:%5D&nokey&wxh=595x282&png" /></a></div>

					<div class='metricGraph'>SJC RTB Response by Publisher 99_PCT Last 4hr<br><a href="http://tsdb2.dc.dotomi.net/#start=4h-ago&m=sum:rate:rtb.requests.process_time%7Bdc=S,response_type=BID,request_type=SITE_BANNER,summary_type=99_PCT,network=*%7D&o=&yrange=%5B0:%5D&key=out%20center%20top%20horiz%20box&wxh=1785x650&autoreload=30" target="_blank"><img id="metrics_4a" src="http://tsdb2.dc.dotomi.net/q?start=4h-ago&m=sum:rate:rtb.requests.process_time%7Bdc=S,response_type=BID,request_type=SITE_BANNER,summary_type=99_PCT,network=*%7D&o=&yrange=%5B0:%5D&nokey&wxh=595x282&png" /></a></div>
					<div class='metricGraph'>IAD RTB Response by Publisher 99_PCT Last 4hr<br><a href="http://tsdb2.dc.dotomi.net/#start=4h-ago&m=sum:rate:rtb.requests.process_time%7Bdc=I,response_type=BID,request_type=SITE_BANNER,summary_type=99_PCT,network=*%7D&o=&yrange=%5B0:%5D&key=out%20center%20top%20horiz%20box&wxh=1785x650&autoreload=30" target="_blank"><img id="metrics_4b" src="http://tsdb2.dc.dotomi.net/q?start=4h-ago&m=sum:rate:rtb.requests.process_time%7Bdc=I,response_type=BID,request_type=SITE_BANNER,summary_type=99_PCT,network=*%7D&o=&yrange=%5B0:%5D&nokey&wxh=595x282&png" /></a></div>
					<div class='metricGraph'>AMS RTB Response by Publisher 99_PCT Last 4hr<br><a href="http://tsdb2.dc.dotomi.net/#start=4h-ago&m=sum:rate:rtb.requests.process_time%7Bdc=A,response_type=BID,request_type=SITE_BANNER,summary_type=99_PCT,network=*%7D&o=&yrange=%5B0:%5D&key=out%20center%20top%20horiz%20box&wxh=1785x650&autoreload=30" target="_blank"><img id="metrics_4c" src="http://tsdb2.dc.dotomi.net/q?start=4h-ago&m=sum:rate:rtb.requests.process_time%7Bdc=A,response_type=BID,request_type=SITE_BANNER,summary_type=99_PCT,network=*%7D&o=&yrange=%5B0:%5D&nokey&wxh=595x282&png" /></a></div>
				
					<div class='metricGraph'>DMA GC Time Last Day<br><a href="http://tsdb2.dc.dotomi.net/#start=24h-ago&m=sum:rate:dma.gc.time%7Bdc=*%7D&o=&yrange=%5B0:%5D&key=top%20center%20horiz%20box&wxh=1851x776&autoreload=30" target="_blank"><img id="metrics_5a" src="http://tsdb2.dc.dotomi.net/q?start=24h-ago&ignore=479&m=sum:rate:dma.gc.time%7Bdc=*%7D&o=&yrange=%5B0:%5D&key=top%20center%20horiz%20box&wxh=595x282&png" /></a></div>
					<div class='metricGraph'>DCS GC Time Last Day<br><a href="http://tsdb2.dc.dotomi.net/#start=24h-ago&m=sum:rate:sasquatch.gc.time%7Bdc=*%7D&o=&yrange=%5B0:%5D&key=top%20center%20horiz%20box&wxh=1851x776&autoreload=30" target="_blank"><img id="metrics_5b" src="http://tsdb2.dc.dotomi.net/q?start=24h-ago&ignore=479&m=sum:rate:sasquatch.gc.time%7Bdc=*%7D&o=&yrange=%5B0:%5D&key=top%20center%20horiz%20box&wxh=595x282&png" /></a></div>
					<div class='metricGraph'>NSY GC Time Last Day<br><a href="http://tsdb2.dc.dotomi.net/#start=24h-ago&m=sum:rate:nsy.gc.time%7Bdc=*%7D&o=&yrange=%5B0:%5D&key=top%20center%20horiz%20box&wxh=1851x776&autoreload=30" target="_blank"><img id="metrics_5c" src="http://tsdb2.dc.dotomi.net/q?start=24h-ago&ignore=479&m=sum:rate:nsy.gc.time%7Bdc=*%7D&o=&yrange=%5B0:%5D&key=top%20center%20horiz%20box&wxh=595x282&png" /></a></div>

					<div class='metricGraph'>SJC DMA Unassociated Impressions Last 24hr<br><a href="http://tsdb2.dc.dotomi.net/#start=24h-ago&m=sum:rate:dma.impressions.unassociated%7Bdc=S%7D&o=&yrange=%5B0:%5D&key=top%20center%20horiz%20box&wxh=1851x1776&autoreload=30" target="_blank"><img id="metrics_9a" src="http://tsdb2.dc.dotomi.net/q?start=24h-ago&m=sum:rate:dma.impressions.unassociated%7Bdc=S%7D&o=&yrange=%5B0:%5D&key=top%20center%20horiz%20box&wxh=595x282&png" /></a></div>
					<div class='metricGraph'>IAD DMA Unassociated Impressions Last 24hr<br><a href="http://tsdb2.dc.dotomi.net/#start=24h-ago&m=sum:rate:dma.impressions.unassociated%7Bdc=I%7D&o=&yrange=%5B0:%5D&key=top%20center%20horiz%20box&wxh=1851x1776&autoreload=30" target="_blank"><img id="metrics_9b" src="http://tsdb2.dc.dotomi.net/q?start=24h-ago&m=sum:rate:dma.impressions.unassociated%7Bdc=I%7D&o=&yrange=%5B0:%5D&key=top%20center%20horiz%20box&wxh=595x282&png" /></a></div>
					<div class='metricGraph'>AMS DMA Unassociated Impressions Last 24hr<br><a href="http://tsdb2.dc.dotomi.net/#start=24h-ago&m=sum:rate:dma.impressions.unassociated%7Bdc=A%7D&o=&yrange=%5B0:%5D&key=top%20center%20horiz%20box&wxh=1851x1776&autoreload=30" target="_blank"><img id="metrics_9c" src="http://tsdb2.dc.dotomi.net/q?start=24h-ago&m=sum:rate:dma.impressions.unassociated%7Bdc=A%7D&o=&yrange=%5B0:%5D&key=top%20center%20horiz%20box&wxh=595x282&png" /></a></div>

					<div class='metricGraph'>SJC Flume Channel Fill Percentage Last 24hr<br><a href="http://tsdb2.dc.dotomi.net/#start=24h-ago&m=sum:flume.channel.ChannelFillPercentage%7Bdc=S,node=*%7D&o=&yrange=%5B0:%5D&key=out%20center%20top%20horiz%20box&wxh=1851x776&autoreload=30" target="_blank"><img id="metrics_7a" src="http://tsdb2.dc.dotomi.net/q?start=24h-ago&ignore=125&m=sum:flume.channel.ChannelFillPercentage%7Bdc=S,node=*%7D&o=&yrange=%5B0:%5D&nokey&wxh=595x282&png" /></a></div>
					<div class='metricGraph'>ORD Flume Channel Fill Percentage Last 24hr<br><a href="http://tsdb2.dc.dotomi.net/#start=24h-ago&m=sum:flume.channel.ChannelFillPercentage%7Bdc=O,node=*%7D&o=&yrange=%5B0:%5D&key=out%20center%20top%20horiz%20box&wxh=1851x776&autoreload=30" target="_blank"><img id="metrics_7b" src="http://tsdb2.dc.dotomi.net/q?start=24h-ago&ignore=125&m=sum:flume.channel.ChannelFillPercentage%7Bdc=O,node=*%7D&o=&yrange=%5B0:%5D&nokey&wxh=595x282&png" /></a></div>
					<div class='metricGraph'>IAD Flume Channel Fill Percentage Last 24hr<br><a href="http://tsdb2.dc.dotomi.net/#start=24h-ago&m=sum:flume.channel.ChannelFillPercentage%7Bdc=I,node=*%7D&o=&yrange=%5B0:%5D&key=out%20center%20top%20horiz%20box&wxh=1851x776&autoreload=30" target="_blank"><img id="metrics_7c" src="http://tsdb2.dc.dotomi.net/q?start=24h-ago&ignore=125&m=sum:flume.channel.ChannelFillPercentage%7Bdc=I,node=*%7D&o=&yrange=%5B0:%5D&nokey&wxh=595x282&png" /></a></div>
					<div class='metricGraph'>AMS Flume Channel Fill Percentage Last 24hr<br><a href="http://tsdb2.dc.dotomi.net/#start=24h-ago&m=sum:flume.channel.ChannelFillPercentage%7Bdc=A,node=*%7D&o=&yrange=%5B0:%5D&key=out%20center%20top%20horiz%20box&wxh=1851x776&autoreload=30" target="_blank"><img id="metrics_10a" src="http://tsdb2.dc.dotomi.net/q?start=24h-ago&ignore=125&m=sum:flume.channel.ChannelFillPercentage%7Bdc=A,node=*%7D&o=&yrange=%5B0:%5D&nokey&wxh=595x282&png" /></a></div>

					<div class='metricGraph'><a href="http://sjc.trend.dc.dotomi.net/graph_all_periods.php?title=RES+HTTP+Requests&vl=&x=&n=&hreg%5B%5D=res&mreg%5B%5D=xx&gtype=stack&glegend=show&aggregate=1" target="_blank"><img id="metrics_11a" src="http://sjc.trend.dc.dotomi.net/graph.php?r=day&z=xlarge&title=RES+HTTP+Requests&vl=&x=&n=&hreg[]=res&mreg[]=xx&gtype=stack&glegend=hide&aggregate=1&embed=1&_=1375815166907" /></a></div>
					<!-- <div class='metricGraph'><a href="http://iad.trend.dc.dotomi.net/graph_all_periods.php?title=IAD+NSY+Heap+Mem+Usage&vl=&x=&n=&hreg%5B%5D=rtb&mreg%5B%5D=Bid_Summary&gtype=stack&glegend=show&aggregate=1" target="_blank"><img id="metrics_9b" src="http://iad.trend.dc.dotomi.net/graph.php?r=day&z=xlarge&title=IAD+RTB+Bid+Summary&vl=&x=&n=&hreg[]=rtb&mreg[]=Bid_Summary&gtype=stack&glegend=hide&aggregate=1&embed=1&_=1375815166907" /></a></div>
					<div class='metricGraph'><a href="http://ams.trend.dc.dotomi.net/graph_all_periods.php?title=AMS+NSY+Heap+Mem+Usage&vl=&x=&n=&hreg%5B%5D=rtb&mreg%5B%5D=Bid_Summary&gtype=stack&glegend=show&aggregate=1" target="_blank"><img id="metrics_9c" src="http://ams.trend.dc.dotomi.net/graph.php?r=day&z=xlarge&title=AMS+RTB+Bid+Summary&vl=&x=&n=&hreg[]=rtb&mreg[]=Bid_Summary&gtype=stack&glegend=hide&aggregate=1&embed=1&_=1375815166907" /></a></div> -->


					<!-- <div class='metricGraph'><a href="http://iad.trend.dc.dotomi.net/graph_all_periods.php?title=IAD+Biddy+Response&vl=&x=&n=&hreg[]=rtb&mreg[]=_DISPLAY_BID_99_PCT&gtype=line&glegend=hide&aggregate=1" target="_blank"><img id="metrics_4a" src="http://iad.trend.dc.dotomi.net/graph.php?r=4hr&z=xlarge&title=IAD+Biddy+Response+-+Alert+If+Above+150&vl=&x=&n=&hreg[]=rtb&mreg[]=_DISPLAY_BID_99_PCT&gtype=line&glegend=hide&aggregate=1" /></a></div> -->
					<!-- <div class='metricGraph'><a href="http://tsdb2.dc.dotomi.net/#start=24h-ago&m=sum:rate:dma.requests%7Bresponse_code=TIMEOUT,dc=*%7D&o=&m=sum:rate:dma.requests%7Bresponse_code=SUCCESS,dc=*%7D&o=&yrange=%5B0:%5D&key=top%20left%20box&wxh=1825x775&autoreload=30" target="_blank"><img id="metrics_4b" src="http://tsdb2.dc.dotomi.net/q?start=24h-ago&m=sum:rate:dma.requests%7Bresponse_code=TIMEOUT,dc=*%7D&o=&m=sum:rate:dma.requests%7Bresponse_code=SUCCESS,dc=*%7D&o=&yrange=%5B0:%5D&key=top%20left%20box&wxh=1800x776&png" /></a></div> -->
					<!-- <div class='metricGraph'><a href="#" target="_blank"><img id="metrics_4c" src="http://tsdb2.dc.dotomi.net/q?start=6h-ago&ignore=122&m=sum:rate:dma.gc.time%7Bdc=*%7D&o=&yrange=%5B0:%5D&key=top%20left%20box&wxh=595x282&png" /></a></div> -->
					<!-- <a id="tsdbURL" href="http://tsdb2.dc.dotomi.net/#start=24h-ago&m=sum:rate:dma.requests%7Bresponse_code=TIMEOUT,dc=*%7D&o=&m=sum:rate:dma.requests%7Bresponse_code=SUCCESS,dc=*%7D&o=&yrange=%5B0:%5D&key=out%20center%20top%20box&wxh=1851x776&autoreload=30" target="_blank">OpenTSDB DMA Requests - Success vs. Timeouts</a> -->
				</div>
			</div>
		</div>
		
		<!--
		<div class="dropdown dropup" style="width:200px;margin:0 auto;text-align:center;">
			<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">OpenTSDB Graphs<span class="caret"></span></button>
			<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
				<li role="presentation" class="dropdown-header">DMA</li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Garbage Collection Time</a></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Another action</a></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Something else here</a></li>
				<li role="presentation" class="divider"></li>
				<li role="presentation" class="dropdown-header">DCS</li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Garbage Collection Time</a></li>
				<li role="presentation" class="divider"></li>
			</ul>
		</div>
		-->

		<script>
			$(document).ready(function(){
				$(document).attr('title', $(document).attr('title')+' - High Priority Metrics');
				$('p').css('font-size','100%');
				$('p').css('word-wrap','break-word');
				$(' #loading-modal ').modal('show');
				$(window).load(function(){
					$(' #loading-modal ').modal('hide');
				});

				setTimeout('location.reload()',300000);
			});
                </script>
			
		<?PHP addFooter(); ?>
	</body>
</html>
