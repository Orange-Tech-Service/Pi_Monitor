<?php


$asset_id = 50000000;
$count_bw = 1500;
$count_color = 1500;
$count_scan = 1500;
$sq_foot = 1500;
$read_date = '2019-11-02 12:12:22';
$who = 'API';

$data = array("asset_id" => "$asset_id", "count_bw" => "$count_bw", "count_color" => "$count_color", "count_scan" => "$count_scan", "sq_foot" => "$sq_foot", "read_date" => "$read_date", "who" => "$who");
$data_string = json_encode($data);

$ch = curl_init('http://mps.copiers4sale.com/api/meter/create.php');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data_string))
);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

//execute post
$result = curl_exec($ch);

//close connection
curl_close($ch);

echo $result;

?>