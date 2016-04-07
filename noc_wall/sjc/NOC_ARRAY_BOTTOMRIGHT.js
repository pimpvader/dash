var BR=new Array();

/* 
Array of data for BOTTOM RIGHT screen (SJC cache)

TITLEMAIN,  (PAGE 2)
TITLE1 ,	etc.
TITLE2 ,
TITLE3 ,
TITLE4 ,
URL1
URL2
URL3
URL4
*/

/*
VERSIONS
8/9/2013  - created  -Eric Hetzel
9/25/2013 - renamed to BOTTOM RIGHT -EH
9/25/2013 - --pending-- change to all cache
*/

BR=[
//0
["IAD_HADOOP",
"Load 15",
"CPU",
"BYTES_IN",
"BYTES_OUT",
"http://iad.trend.dc.dotomi.net/graph.php?r=week&z=xlarge&title=IAD+HDP+LOAD+15&vl=&x=&n=&hreg[]=dtiad00hdp.*&mreg[]=load_fifteen&gtype=line&glegend=hide&aggregate=1&embed=1&_=1375983678934",
"http://iad.trend.dc.dotomi.net/graph.php?r=week&z=xlarge&title=IAD+HDP+CPU=&vl=&x=&n=&hreg[]=dtiad00hdp.*&mreg[]=cpu_system&gtype=line&glegend=hide&aggregate=1&embed=1&_=1375983678934",
"http://iad.trend.dc.dotomi.net/graph.php?r=week&z=xlarge&title=IAD+HDP+CPU=&vl=&x=&n=&hreg[]=dtiad00hdp.*&mreg[]=bytes_in&gtype=line&glegend=hide&aggregate=1&embed=1&_=1375983678934",
"http://iad.trend.dc.dotomi.net/graph.php?r=week&z=xlarge&title=IAD+HDP+CPU=&vl=&x=&n=&hreg[]=dtiad00hdp.*&mreg[]=bytes_out&gtype=line&glegend=hide&aggregate=1&embed=1&_=1375983678934"],
//1
["IAD_HADOOP",
"MEMORY",
"REQUESTS / REGION SERVER",
"COMPACTION QUEVE SIZE",
"READ LATENCY",
"http://iad.trend.dc.dotomi.net/graph.php?r=week&z=xlarge&title=IAD+HDP+MEMORY&vl=&x=&n=&hreg[]=dtiad00hdp.*&mreg[]=mem_used&gtype=line&glegend=hide&aggregate=1&embed=1&_=1375983678934",
"http://iad.trend.dc.dotomi.net/graph.php?r=week&z=xlarge&title=&vl=&x=&n=&hreg[]=dtiad00hdp.*&mreg[]=hbase.regionserver.requests&gtype=line&glegend=hide&aggregate=1&embed=1&_=1375983977914",
"http://iad.trend.dc.dotomi.net/graph.php?r=week&z=xlarge&title=IAD+HDP+COMPACTION+QUEVE+SIZE=&vl=&x=&n=&hreg[]=dtiad00hdp.*&mreg[]=hbase.regionserver.compactionQueueSize&gtype=line&glegend=hide&aggregate=1&embed=1&_=1375983678934",
"http://iad.trend.dc.dotomi.net/graph.php?r=week&z=xlarge&title=IAD+HDP+READ+LATENCY=&vl=&x=&n=&hreg[]=dtiad00hdp.*&mreg[]=hbase.regionserver.fsReadLatency_avg_time&gtype=line&glegend=hide&aggregate=1&embed=1&_=1375983678934"],
//2
["SJC_HADOOP",
"Load 15",
"CPU",
"BYTES_IN",
"BYTES_OUT",
"http://sjc.trend.dc.dotomi.net/graph.php?r=week&z=xlarge&title=SJC+HDP+LOAD+15&vl=&x=&n=&hreg[]=dtsjc00hdp.*&mreg[]=load_fifteen&gtype=line&glegend=hide&aggregate=1&embed=1&_=1375983678934",
"http://sjc.trend.dc.dotomi.net/graph.php?r=week&z=xlarge&title=SJC+HDP+CPU=&vl=&x=&n=&hreg[]=dtsjc00hdp.*&mreg[]=cpu_system&gtype=line&glegend=hide&aggregate=1&embed=1&_=1375983678934",
"http://sjc.trend.dc.dotomi.net/graph.php?r=week&z=xlarge&title=SJC+HDP+CPU=&vl=&x=&n=&hreg[]=dtsjc00hdp.*&mreg[]=bytes_in&gtype=line&glegend=hide&aggregate=1&embed=1&_=1375983678934",
"http://sjc.trend.dc.dotomi.net/graph.php?r=week&z=xlarge&title=SJC+HDP+CPU=&vl=&x=&n=&hreg[]=dtsjc00hdp.*&mreg[]=bytes_out&gtype=line&glegend=hide&aggregate=1&embed=1&_=1375983678934"],
//3
["SJC_HADOOP",
"MEMORY",
"REQUESTS / REGION SERVER",
"COMPACTION QUEVE SIZE",
"READ LATENCY",
"http://sjc.trend.dc.dotomi.net/graph.php?r=week&z=xlarge&title=SJC+HDP+MEMORY&vl=&x=&n=&hreg[]=dtsjc00hdp.*&mreg[]=mem_used&gtype=line&glegend=hide&aggregate=1&embed=1&_=1375983678934",
"http://sjc.trend.dc.dotomi.net/graph.php?r=week&z=xlarge&title=&vl=&x=&n=&hreg[]=dtsjc00hdp.*&mreg[]=hbase.regionserver.requests&gtype=line&glegend=hide&aggregate=1&embed=1&_=1375983977914",
"http://sjc.trend.dc.dotomi.net/graph.php?r=week&z=xlarge&title=SJC+HDP+COMPACTION+QUEVE+SIZE=&vl=&x=&n=&hreg[]=dtsjc00hdp.*&mreg[]=hbase.regionserver.compactionQueueSize&gtype=line&glegend=hide&aggregate=1&embed=1&_=1375983678934",
"http://sjc.trend.dc.dotomi.net/graph.php?r=week&z=xlarge&title=SJC+HDP+READ+LATENCY=&vl=&x=&n=&hreg[]=dtsjc00hdp.*&mreg[]=hbase.regionserver.fsReadLatency_avg_time&gtype=line&glegend=hide&aggregate=1&embed=1&_=1375983678934"],

]
