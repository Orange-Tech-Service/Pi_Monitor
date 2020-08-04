<?php
//date
$today= date("Y-m-d");

$db = new mysqli('localhost', 'ot_admin', '0rang3T3ch4758!', 'webcron');

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}

$sql = <<<SQL
    SELECT *
    FROM `asset` 
SQL;

if(!$result = $db->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
}
while($row = $result->fetch_assoc()){
    $rs_customer_id = $row['rs_customer_id'];
    $rs_asset_id = $row['rs_asset_id'];
    $snmp_ip =  $row['ipaddress'];
    $snmpcommunity = $row['snmp_community'];
    $man = $row['machine_type'];
    $setup = $row['setup'];
    $status = $row['status'];

    if ($status=="Active"){

    $today= date("Y-m-d H:i:s");
    echo "Today: ".$today."<br>";
  //  echo 'RSID: '.$rs_customer_id . '<br>';
    echo 'Asset ID: '.$rs_asset_id . '<br>';

    $sysDescr[0] = snmpget($snmp_ip, $snmpcommunity, "1.3.6.1.2.1.1.1.0");
    $sysDescr[1] = str_replace("STRING:", "", $sysDescr[0]);
    $sysDescr[2] = str_replace('"', "", $sysDescr[1]);
    $sysDescr[3] = ltrim($sysDescr[2]);
    echo 'System Description: '.$sysDescr[3]. '<br>';

    //sysName
    $sysName[0] = snmpget($snmp_ip, $snmpcommunity, "1.3.6.1.2.1.1.5.0");
    $sysName[1] = str_replace("STRING:", "", $sysName[0]);
    $sysName[2] = str_replace('"', "", $sysName[1]);
    $sysName[3] = ltrim($sysName[2]);
    //echo 'SYSTEM NAME: '.$sysName[3]. '<br>';

    //sysContact
    $sysContact[0] = snmpget($snmp_ip, $snmpcommunity, "1.3.6.1.2.1.1.3.0");
    $sysContact[1] = str_replace("STRING:", "", $sysContact[0]);
    $sysContact[2] = str_replace('"', "", $sysContact[1]);
    $sysContact[3] = ltrim($sysContact[2]);
    //echo $sysContact[3].'<br>';

    //sysLocation
    $sysLocation[0] = snmpget($snmp_ip, $snmpcommunity, "1.3.6.1.2.1.1.6.0");
    $sysLocation[1] = str_replace("STRING:", "", $sysLocation[0]);
    $sysLocation[2] = str_replace('"', "", $sysLocation[1]);
    $sysLocation[3] = ltrim($sysLocation[2]);
    //echo $sysLocation[3]. '<br>';

    //serialNumber
    $serialNumber[0] = snmpget($snmp_ip, $snmpcommunity, "1.3.6.1.2.1.43.5.1.1.17.1");
    $serialNumber[1] = str_replace("STRING:", "", $serialNumber[0]);
    $serialNumber[2] = str_replace('"', "", $serialNumber[1]);
    $serialNumber[3] = ltrim($serialNumber[2]);
    echo 'Serial Number: '.$serialNumber[3]. '<br>';

    echo 'Machine Type: '.$man. "<br>";
   
}
else{
    echo "NO Active Assets";
}

//-------------------------------------- HP -------------------------

if ($man=="HP" && $status=="Active"){
    
    //netMask
    $netMask[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.11.2.4.3.5.12.0");
    $netMask[1] = str_replace("IpAddress:", "", $netMask[0]);
    $netMask[2] = str_replace('"', " ", $netMask[1]);
    $netMask[3] = ltrim($netMask[2]);
  
    echo $netMask[3]. '<br>';
       
   //serialNumber
   $serialNumber[0] = snmpget($snmp_ip, $snmpcommunity, "1.3.6.1.2.1.1.5.0");
   $serialNumber[1] = str_replace("STRING:", "", $serialNumber[0]);
   $serialNumber[2] = str_replace('"', "", $serialNumber[1]);
   $serialNumber[3] = ltrim($serialNumber[2]);
   echo $serialNumber[3]. '<br>';
  
   $sql_insert = <<<SQL
   INSERT INTO meter_count (asset_id, count_bw, count_scan, count_fax, read_date)
   VALUES ('$rs_asset_id', '$countTotal[3]', '$countCopy[3]', '$countFax[3]', '$today')
   SQL;
    mysqli_query($db,"$sql_insert");
    echo $sql_insert;
      }

//-------------------------------------- AC -------------------------

      if ($man=="AC" && $status=="Active"){
    
//-------------------------------------- SCAN -------------------------

        //scanBlack SCAN COUNTER
        $scanBlack[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.1.5.7.2.1.5.0");
        $scanBlack[1] = str_replace("INTEGER:", " ", $scanBlack[0]);
        $scanBlack[2] = str_replace('"', " ", $scanBlack[1]);
        $scanBlack[3] = ltrim($scanBlack[2]);            
        $allScans[4] = $scanBlack[3];    
        echo 'Scans: '. $allScans[4]. '<br>';
        
//-------------------------------------- COLOR -------------------------

//fullColor PRINT COUNTER
$fullColor[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.1.5.7.2.2.1.5.2.2");
$fullColor[1] = str_replace("INTEGER:", " ", $fullColor[0]);
$fullColor[2] = str_replace('"', " ", $fullColor[1]);
$fullColor[3] = ltrim($fullColor[2]);
//echo 'Print Counter Color '.$fullColor[3]. '<br>';

//fullColorCopy COPY COUNTER
$fullColorCopy[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.1.5.7.2.2.1.5.2.1");
$fullColorCopy[1] = str_replace("INTEGER:", " ", $fullColorCopy[0]);
$fullColorCopy[2] = str_replace('"', " ", $fullColorCopy[1]);
$fullColorCopy[3] = ltrim($fullColorCopy[2]);
//echo 'Copy Counter Color '.$fullColorCopy[3]. '<br>';

//-------------------------------------- BLACK -------------------------

//black PRINT COUNTER
$black[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.1.5.7.2.2.1.5.1.2");
$black[1] = str_replace("INTEGER:", " ", $black[0]);
$black[2] = str_replace('"', "", $black[1]);
$black[3] = ltrim($black[2]);
//echo 'Printer Counter Black '.$black[3]. '<br>';

//totalBlack COPY COUNTER
$totalBlack[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.1.5.7.2.2.1.5.1.1");
$totalBlack[1] = str_replace("INTEGER:", " ", $totalBlack[0]);
$totalBlack[2] = str_replace('"', "", $totalBlack[1]);
$totalBlack[3] = ltrim($totalBlack[2]);
//echo 'Copy Counter Black '.$totalBlack[3]. '<br>';

//-------------------------------------- CALCULATION TOTALS -------------------------

$allBlack[4] = ($black[3]+$totalBlack[3]);
echo 'Black: '.$allBlack[4]. '<br>';

$allColor[4] = ($fullColor[3]+$fullColorCopy[3]);
echo 'Color: '.$allColor[4]. '<br>';

//-------------------------------------- SQL -------------------------


$count_bw = $allBlack[4];
$count_color = $allColor[4];
$count_scan = $allScans[4];
$count_fax = 0;
$sq_foot = 0;
$read_date = $today;
$who = 'API';

$sql_insert = <<<SQL
    INSERT INTO meter_count (asset_id, count_bw, count_color, count_scan, sq_foot, read_date)
    VALUES ('$rs_asset_id', '$count_bw', '$count_color','$count_scan', '$sq_foot', '$read_date')
    SQL;
mysqli_query($db,"$sql_insert");

echo '<br>'.$sql_insert;
echo '<br>------------------------------------------------';



$dataAC = array("asset_id" => "$rs_asset_id", "count_bw" => "$count_bw", "count_color" => "$count_color", "count_scan" => "$count_scan", "sq_foot" => "$sq_foot", "count_fax" => "$count_fax", "read_date" => "$read_date", "who" => "$who");
$data_stringAC = json_encode($dataAC);

$chAC = curl_init('http://mps.copiers4sale.com/api/meter/create.php');
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




    }

   
    
    

//-------------------------------------- MFX -------------------------

if ($man=="MFX" && $status=="Active"){

//-------------------------------------- BLACK -------------------------

//countTotal
$countTotal[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.4322.101.1.1.5.7.2.1.1.0");
$countTotal[1] = str_replace("INTEGER:", " ", $countTotal[0]);
$countTotal[2] = str_replace('"', " ", $countTotal[1]);
$countTotal[3] = ltrim($countTotal[2]);
echo 'Black: '.$countTotal[3]. '<br>';

//-------------------------------------- COPY -------------------------

//countCopy
$countCopy[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.4322.101.1.1.5.7.2.2.1.5.1.1");
$countCopy[1] = str_replace("INTEGER:", " ", $countCopy[0]);
$countCopy[2] = str_replace('"', " ", $countCopy[1]);
$countCopy[3] = ltrim($countCopy[2]);
echo 'Copy: '.$countCopy[3]. '<br>';

//-------------------------------------- FAX -------------------------

//countFax
$countFax[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.4322.101.1.1.5.7.2.3.1.7.1");
$countFax[1] = str_replace("INTEGER:", " ", $countFax[0]);
$countFax[2] = str_replace('"', " ", $countFax[1]);
$countFax[3] = ltrim($countFax[2]);
echo 'Fax: '.$countFax[3]. '<br><br>';

//-------------------------------------- SQL -------------------------

$count_bw = $countTotal[3];
$count_color = 0;
$count_scan = $countCopy[3];
$count_fax = $countFax[3];
$sq_foot = 0;
$read_date = $today;
$who = 'API';

$sql_insert = <<<SQL
    INSERT INTO meter_count (asset_id, count_bw, count_scan, count_fax, read_date)
    VALUES ('$rs_asset_id', '$countTotal[3]', '$countCopy[3]', '$countFax[3]', '$today')
    SQL;
mysqli_query($db,"$sql_insert");
echo $sql_insert;
echo '<br>------------------------------------------------';


$dataMFX = array("asset_id" => "$rs_asset_id", "count_bw" => "$count_bw", "count_color" => "$count_color", "count_scan" => "$count_scan", "sq_foot" => "$sq_foot", "count_fax" => "$count_fax", "read_date" => "$read_date", "who" => "$who");
$data_stringMFX = json_encode($dataMFX);

$chMFX = curl_init('http://mps.copiers4sale.com/api/meter/create.php');
curl_setopt($chMFX, CURLOPT_VERBOSE, true);  
curl_setopt($chMFX, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($chMFX, CURLOPT_POSTFIELDS, $data_stringMFX);
curl_setopt($chMFX, CURLOPT_RETURNTRANSFER, true);
curl_setopt($chMFX, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data_stringMFX))
);
curl_setopt($chMFX, CURLOPT_TIMEOUT, 5);
curl_setopt($chMFX, CURLOPT_CONNECTTIMEOUT, 5);

//execute post
echo $data_stringMFX;

curl_exec($chMFX);

//sleep(2);

}

//-------------------------------------- BH -------------------------

if ($man=="BH" && $status=="Active"){

//-------------------------------------- COLOR -------------------------

//fullColor PRINT COUNTER
$fullColor[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.1.5.7.2.2.1.5.2.2");
$fullColor[1] = str_replace("INTEGER:", " ", $fullColor[0]);
$fullColor[2] = str_replace('"', " ", $fullColor[1]);
$fullColor[3] = ltrim($fullColor[2]);

//echo $fullColor[3]. '<br>';

//fullColorCopy COPY COUNTER
$fullColorCopy[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.1.5.7.2.2.1.5.2.1");
$fullColorCopy[1] = str_replace("INTEGER:", " ", $fullColorCopy[0]);
$fullColorCopy[2] = str_replace('"', " ", $fullColorCopy[1]);
$fullColorCopy[3] = ltrim($fullColorCopy[2]);

//echo $fullColorCopy[3]. '<br>';

//singleColorCopy COPY COUNTER
$singleColorCopy[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.1.5.7.2.2.1.5.3.1");
$singleColorCopy[1] = str_replace("INTEGER:", " ", $singleColorCopy[0]);
$singleColorCopy[2] = str_replace('"', " ", $singleColorCopy[1]);
$singleColorCopy[3] = ltrim($singleColorCopy[2]);

//echo $singleColorCopy[3]. '<br>';

//--------------------------------------SCANS-------------------------

//scanFullColor SCAN COUNTER
$scanFullColor[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.1.5.7.2.3.1.11.1");
$scanFullColor[1] = str_replace("INTEGER:", " ", $scanFullColor[0]);
$scanFullColor[2] = str_replace('"', " ", $scanFullColor[1]);
$scanFullColor[3] = ltrim($scanFullColor[2]);

//echo 'SCAN COLOR'. $scanFullColor[3]. '<br>';

//scanFullColorLG SCAN COUNTER
$scanFullColorLG[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.1.5.7.2.3.1.13.1");
$scanFullColorLG[1] = str_replace("INTEGER:", " ", $scanFullColorLG[0]);
$scanFullColorLG[2] = str_replace('"', " ", $scanFullColorLG[1]);
$scanFullColorLG[3] = ltrim($scanFullColorLG[2]);

//echo 'SCAN COLOR LG'. $scanFullColorLG[3]. '<br>';

//baseScans SCAN COUNTER
$baseScans[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.1.5.7.2.3.1.5.1");
$baseScans[1] = str_replace("INTEGER:", " ", $baseScans[0]);
$baseScans[2] = str_replace('"', " ", $baseScans[1]);
$baseScans[3] = ltrim($baseScans[2]);

//echo 'SCANS'. $baseScans[3]. '<br>';

//baseScanLG SCAN COUNTER
$baseScanLG[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.1.5.7.2.3.1.6.1");
$baseScanLG[1] = str_replace("INTEGER:", " ", $baseScanLG[0]);
$baseScanLG[2] = str_replace('"', " ", $baseScanLG[1]);
$baseScanLG[3] = ltrim($baseScanLG[2]);

//echo 'BASE SCANS LG'. $baseScanLG[3]. '<br>';

//scanBlack SCAN COUNTER
$scanBlack[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.1.5.7.2.3.1.12.1");
$scanBlack[1] = str_replace("INTEGER:", " ", $scanBlack[0]);
$scanBlack[2] = str_replace('"', " ", $scanBlack[1]);
$scanBlack[3] = ltrim($scanBlack[2]);

echo 'BLACK SCANS'. $scanBlack[3]. '<br>';

//copy2Color COPY COUNTER
$copy2Color[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.1.5.7.2.2.1.5.4.1");
$copy2Color[1] = str_replace("INTEGER:", " ", $copy2Color[0]);
$copy2Color[2] = str_replace('"', " ", $copy2Color[1]);
$copy2Color[3] = ltrim($copy2Color[2]);

//echo $copy2Color[2]. '<br>';


//-------------------------------------- BLACK -------------------------

//black PRINT COUNTER
$black[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.1.5.7.2.2.1.5.1.2");
$black[1] = str_replace("INTEGER:", " ", $black[0]);
$black[2] = str_replace('"', "", $black[1]);
$black[3] = ltrim($black[2]);
//echo $black[2]. '<br>';

//totalBWColor TOTAL BY COLOR
$totalBWColor[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.1.5.7.2.1.1.0");
$totalBWColor[1] = str_replace("INTEGER:", " ", $totalBWColor[0]);
$totalBWColor[2] = str_replace('"', "", $totalBWColor[1]);
$totalBWColor[3] = ltrim($totalBWColor[2]);
//echo $totalBWColor[2]. '<br>';

//totalBlack COPY COUNTER
$totalBlack[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.1.5.7.2.2.1.5.1.1");
$totalBlack[1] = str_replace("INTEGER:", " ", $totalBlack[0]);
$totalBlack[2] = str_replace('"', "", $totalBlack[1]);
$totalBlack[3] = ltrim($totalBlack[2]);
//echo $totalBlack[2]. '<br>';

//totalColor SCAN CONTROL
$totalColor[0] = snmpget($snmp_ip, $snmpcommunity, " .1.3.6.1.4.1.18334.1.1.1.5.7.2.2.1.5.2.1");
$totalColor[1] = str_replace("INTEGER:", " ", $totalColor[0]);
$totalColor[2] = str_replace('"', "", $totalColor[1]);
$totalColor[3] = ltrim($totalColor[2]);
//echo 'Scans: '.$totalColor[3]. '<br>';

//echo 'TOTAL BLACK'.($black[2]+$totalBlack[2]+$totalColor[2]);

//total2Color
$total2Color[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.1.5.7.2.2.1.5.4.1");
$total2Color[1] = str_replace("INTEGER:", " ", $total2Color[0]);
$total2Color[2] = str_replace('"', "", $total2Color[1]);
$total2Color[3] = ltrim($total2Color[2]);
//echo $total2Color[3]. '<br>';

//-------------------------------------- CALCULATION TOTALS -------------------------

$allBlack[4] = ($black[3]+$totalBlack[3]+$scanBlack[3]);
echo 'Black: '.$allBlack[4]. '<br>';

$allColor[4] = ($fullColor[3]+$fullColorCopy[3]+$singleColorCopy[3]+$scanFullColor[3]+$copy2Color[3]);
echo 'Color: '.$allColor[4]. '<br>';

$allScans[4] = ($scanFullColor[3]+$scanFullColorLG[3]+$baseScans[3]+$baseScanLG[3]+$scanBlack[3]);
echo 'Scans: '. $allScans[4]. '<br>';

//-------------------------------------- SQL -------------------------

$count_bw = $allBlack[4];
$count_color = $allColor[4];
$count_scan = $allScans[4];
$read_date = $today;
$who = 'API';
$sq_foot = 0;
$send_failure = true;

$sql_insert = <<<SQL
    INSERT INTO meter_count (asset_id, count_bw, count_color, count_scan, sq_foot, read_date)
    VALUES ('$rs_asset_id', '$count_bw', '$count_color','$count_scan', '', '$read_date')
    SQL;
mysqli_query($db,"$sql_insert");

echo '<br>'.$sql_insert;
echo '<br>------------------------------------------------';

$data = array("asset_id" => "$rs_asset_id", "count_bw" => "$count_bw", "count_color" => "$count_color", "count_scan" => "$count_scan", "sq_foot" => "$sq_foot", "read_date" => "$read_date", "who" => "$who");
$data_string = json_encode($data);

$ch = curl_init('http://mps.copiers4sale.com/api/meter/create.php');
curl_setopt($curl, CURLOPT_VERBOSE, true);  
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
echo $data_string;

curl_exec($ch);

//sleep(2);
}
}

curl_close($ch); 
?>