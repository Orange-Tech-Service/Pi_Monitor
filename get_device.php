<?php
//date
$today= date("Y-m-d");
$url = "http://mps.local/api/customer/update.php";
include("inc/_db.php");
$db = new mysqli($server, $db_user, $db_pass, $db_name);

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}

$ip_address = gethostbyname(trim(exec("hostname")));
$sql = <<<SQL
    SELECT *
    FROM `customer` LIMIT 1
    
SQL;
if(!$result = $db->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
}
while($row = $result->fetch_assoc()){
    $cust_rs_customer_id = $row['rs_customer_id'];
    $cust_rs_business_name = $row['rs_business_name'];
    $cust_email =  $row['email'];
    $cust_tech_email = $row['tech_email'];
    $cust_contact = $row['contact'];
    $cust_phone = $row['phone'];
    $cust_http_port = $row['http_port'];
    $cust_ssh_port = $row['ssh_port'];
    $cust_ip_address = $row['ip_address'];

}

$dataAC = array("rs_customer_id" => "$cust_rs_customer_id", "http_port" => "$cust_http_port", "ssh_port" => "$cust_ssh_port", "ip_address" => "$cust_ip_address");
$data_stringAC = json_encode($dataAC);

$chAC = curl_init('$url');
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
echo $data_stringAC;

curl_exec($chAC);

curl_close($ch);
?>