<?php

$file = file('nmapTest.xml');

$servername = "localhost";
$username = "nmap_user";
$password = "123456";
$db = "nmap";

$conn = new mysqli($servername, $username, $password, $db);

if ($conn->connect_error){
	die("Connection failed: ". $conn->connect_error);
}

$ip;
$mac;
$vendor;
$hostname;
$port;
$portArray = array();
$portList;
$timestamp;

foreach($file as $line){
	
	//Get IP Address
	if (strpos($line, 'addrtype="ipv4"') == TRUE){
	preg_match('/addr=".* addrtype/',$line,$results);
	$ip = implode(" ",$results);
	$ip = ltrim($ip, 'addr="');
	$ip = rtrim($ip, '" addrtype');
	print "<br><strong><u>Device</u></strong><br>";
	print "IP Address:  $ip<br>";
	}

	//Get Vendor
	if (strpos($line, 'addrtype="mac"') == TRUE){
	preg_match('/vendor=".*"/',$line,$results);
	$vendor = implode(" ",$results);
	$vendor = ltrim($vendor,'vendor="');
	$vendor = rtrim($vendor, '"');
	print "Vendor: $vendor<br>";
	}
	
	//Get MAC Address
	if (strpos($line, 'addrtype="mac"') == TRUE){
	preg_match('/addr=".*" addrtype/',$line,$results);
	$mac = implode(" ",$results);
	$mac = ltrim($mac,'addr="');
	$mac = rtrim($mac, '" addrtype');
	print "MAC Address: $mac<br>";
	}
	
	//Get Hostname
	if (strpos($line, 'type="PTR"') == TRUE){
	preg_match('/name=".*" type/',$line,$results);
	$hostname = implode(" ",$results);
	$hostname = ltrim($hostname,'name="');
	$hostname = rtrim($hostname, ' type');
	$hostname = rtrim($hostname, '"');
	print "Hostname:  $hostname<br>";
	}
	
	//Get Ports
	if (strpos($line, 'portid="') == TRUE){
	preg_match('/portid=".*><state/',$line,$results);
	$port = implode(" ",$results);
	$port = ltrim($port,'portid="');
	$port = rtrim($port, '"><state');
	print "Port: $port<br>";
	array_push($portArray, $port);
	}
	
	//Add Values to Database
	if (strpos($line, '/host>') == TRUE){
	$timestamp = time();	
	$portList = implode(", ",$portArray);
	$sql = "insert into log(ip,mac,vendor,hostname,ports,timestamp) values ('$ip','$mac','$vendor','$hostname','$portList','$timestamp')";

	if ($conn->query($sql) === TRUE) {
		echo "Data Added: $ip  - $mac - $vendor - $hostname - $portList - $timestamp <br>";
	} else {
		echo "Error: ".$sql."<br>".$conn->error;
	}
	$ip = " ";
	$mac = " ";
	$vendor = " ";
	$hostname = " ";
	unset($portArray);
	$portArray = array();
	$portList = " ";
	}

}

$conn->close();

?>
