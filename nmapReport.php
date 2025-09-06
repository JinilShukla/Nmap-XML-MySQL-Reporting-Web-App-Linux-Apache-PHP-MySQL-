<?php

$servername = "localhost";
$username = "nmap_user";
$password = "123456";
$db = "nmap";

$conn = new mysqli($servername, $username, $password, $db);

if ($conn->connect_error){
	die("Connection failed: ". $conn->connect_error);
}

$sql = "select * from log";
$result = $conn->query($sql);

$splitTrigger;

echo "<h1>NMAP Scan Report</h1>";

if ($result->num_rows > 0){
    echo "<table>";
while($row = $result->fetch_assoc() ){
    if ($row["timestamp"] != $splitTrigger) {
        echo "<tr><td>Scan At: ".date("h:i:sa d-m-Y",$row['timestamp'])."</td></tr>";
        $splitTrigger = $row["timestamp"];
    }
	echo "<tr><td>".$row["ip"] ."</td><td>" .$row["mac"]."</td><td>" .$row["vendor"]."</td><td>" .$row["ports"]."</td><td>" .$row["timestamp"]."</td></tr>";
}
echo "</table>";
} else {
	echo "0 records";
}
$conn->close();

?>

grant all privileges on nmap.log to namp_user;
