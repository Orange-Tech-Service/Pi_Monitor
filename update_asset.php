<?php
sleep(10);

include("inc/_db.php");
//date
$today= date("Y-m-d");
$url = "http://mps.copiers4sale.com/api/asset/update.php";
$db = new mysqli($server, $db_user, $db_pass, $db_name);

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}

$sql = "SELECT * FROM `asset`";
    
if(!$result = $db->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
}
while($row = $result->fetch_assoc()){
    $rs_id = $row['rs_asset_id'];
    $ip_address = $row['ipaddress'];

$dataAC = array("rs_id" => "$rs_id", "ip_address" => "$ip_address");
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