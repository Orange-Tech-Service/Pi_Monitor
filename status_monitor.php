<?php
sleep(5);

include("inc/_db.php");
//date
$today= date("Y-m-d h:m:s");
$url = "http://mps.copiers4sale.com/api/monitor/status.php";
$db = new mysqli($server, $db_user, $db_pass, $db_name);

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}

$sql = "SELECT * FROM `customer`";
    
if(!$result = $db->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
}
while($row = $result->fetch_assoc()){
    $cust_rs_customer_id = $row['rs_customer_id'];
    $device_id = $row['ssh_port'];
    $status_date = $today;


$dataAC = array("rs_customer_id" => "$cust_rs_customer_id", "device_id" => "$device_id", "who" => "API", "status_date" => "$status_date");
$data_stringAC = json_encode($dataAC);

$chAC = curl_init($url);
curl_setopt($chAC, CURLOPT_VERBOSE, true);  
curl_setopt($chAC, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($chAC, CURLOPT_POSTFIELDS, $data_stringAC);
curl_setopt($chAC, CURLOPT_RETURNTRANSFER, true);
curl_setopt($chAC, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data_stringAC))
);
curl_setopt($chAC, CURLOPT_TIMEOUT, 5);
curl_setopt($chAC, CURLOPT_CONNECTTIMEOUT, 5);

//execute post
echo "Updating Asset via API -- ";
echo $data_stringAC;
echo "Redirecting to asset list.";
header ("Location:asset_details.php");

curl_exec($chAC);
}

curl_close($ch);
?>