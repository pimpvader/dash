var TR=new Array();

/* 
Array of data for Top Right (IAD Cache)


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
8/9/2013  - created  - Eric Hetzel
9/25/2013 - changed title to Top Right  -EH
9/25/2013 - --pending-- moved all registration/delivery/conversion rate
*/


TR=[
//0
["IAD Money Makers",
"IAD Conversions",
"IAD Registrations",
"IAD Message Delivered",
"IAD Frontdoor and CID Proxy",
"http://iad.trend.int/graph.php?r=week&z=xlarge&title=IAD+CONVERSIONS&vl=&x=&n=&hreg[]=dtiad.*&mreg[]=user_operations.REGISTRATIONaction.CONVERSION&gtype=stack&glegend=hide&aggregate=1&embed=1&_=1380127596005",
"http://iad.trend.int/graph.php?r=week&z=xlarge&title=IAD+REGISTRATIONS&vl=&x=&n=&hreg[]=dma&mreg[]=DMA_REGISTRATION_SUCCESS&gtype=stack&glegend=hide&aggregate=1&embed=1&_=1380127340457",
"http://iad.trend.int/graph.php?r=week&z=xlarge&title=IAD+DELIVERIES&vl=&x=&n=&hreg[]=dtiad.*&mreg[]=MsgRecvByBrowser_3&gtype=stack&glegend=hide&aggregate=1&embed=1&_=1380127504278",
"http://iad.trend.int/graph.php?r=2hr&z=xlarge&title=IAD+Frontdoor+and+CID+Proxy+Traffic&vl=&x=&n=&hreg[]=lvs&mreg[]=HTTP_RPS%2520%7CHTTPS_RPS&gtype=stack&glegend=hide&aggregate=1"],
//1
["SJC Money Makers",
"SJC Conversions",
"SJC Registrations",
"SJC Messages Delivered",
"SJC Frontdoor and CID Proxy",
"http://sjc.trend.int/graph.php?r=week&z=xlarge&title=SJC+CONVERSIONS&vl=&x=&n=&hreg[]=dtsjc.*&mreg[]=user_operations.REGISTRATIONaction.CONVERSION&gtype=stack&glegend=hide&aggregate=1&embed=1&_=1380127596005",
"http://sjc.trend.int/graph.php?r=week&z=xlarge&title=SJC+REGISTRATIONS&vl=&x=&n=&hreg[]=dma&mreg[]=DMA_REGISTRATION_SUCCESS&gtype=stack&glegend=hide&aggregate=1&embed=1&_=1380127340457",
"http://sjc.trend.int/graph.php?r=week&z=xlarge&title=SJC+DELIVERIES&vl=&x=&n=&hreg[]=dtsjc.*&mreg[]=MsgRecvByBrowser_3&gtype=stack&glegend=hide&aggregate=1&embed=1&_=1380127504278",
"http://sjc.trend.int/graph.php?r=2hr&z=xlarge&title=SJC+Frontdoor+and+CID+Proxy+Traffic&vl=&x=&n=&hreg[]=lvs&mreg[]=HTTP_RPS%2520%7CHTTPS_RPS&gtype=stack&glegend=hide&aggregate=1"],
//2
["ALL Money Makers",
"ALL Conversions",
"ALL Registrations",
"ALL Messages Delivered",
"ALL ORD Latency",
"http://iad.trend.int/graph.php?r=week&z=xlarge&title=ALL+CONVERSIONS&vl=&x=&n=&hreg[]=.*&mreg[]=user_operations.REGISTRATIONaction.CONVERSION&gtype=stack&glegend=hide&aggregate=1&embed=1&_=1380127596005",
"http://iad.trend.int/graph.php?r=week&z=xlarge&title=ALL+REGISTRATIONS&vl=&x=&n=&hreg[]=.*&mreg[]=requests.REGISTRATION.SUCCESS&gtype=stack&glegend=hide&aggregate=1&embed=1&_=1380127340457",
"http://iad.trend.int/graph.php?r=week&z=xlarge&title=ALL+DELIVERIES&vl=&x=&n=&hreg[]=.*&mreg[]=MsgRecvByBrowser_3&gtype=stack&glegend=hide&aggregate=1&embed=1&_=1380127504278",
"http://iad.trend.int/graph.php?r=hour&z=xlarge&title=Frontend+Latency+as+seen+from+ORD&vl=&x=.6&n=&hreg[]=dt&mreg[]=https_[i%2Cs]&gtype=line&glegend=hide&aggregate=1&embed=1&_=1381958087706"],
]
