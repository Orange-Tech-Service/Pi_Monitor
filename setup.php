<?php include("inc/_header.php"); ?>
<body class="d-flex flex-column h-100">
<?php include("inc/_topnav.php"); ?>
<?php


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

    if ($setup==0 && $status=="Active"){


    $today= date("Y-m-d");
    echo "Today ".$today."";
    echo $rs_customer_id . '<br>';
    echo $rs_asset_id . '<br>';



    $sysDescr[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.1.1.0");
    //$sysDescr[0] = snmpget($snmp_ip, $snmpcommunity, "1.3.6.1.2.1.1.1.0");
    $sysDescr[1] = str_replace("STRING:", "", $sysDescr[0]);
    $sysDescr[2] = str_replace('"', "", $sysDescr[1]);
    $sysDescr[3] = ltrim($sysDescr[2]);
    echo 'SYSTEM DESCRIPTION '.$sysDescr[3]. '<br>';

    //sysName
    $sysName[0] = snmpget($snmp_ip, $snmpcommunity, "1.3.6.1.2.1.1.5.0");
    $sysName[1] = str_replace("STRING:", "", $sysName[0]);
    $sysName[2] = str_replace('"', "", $sysName[1]);
    $sysName[3] = ltrim($sysName[2]);
    echo 'SYSTEM NAME '.$sysName[3]. '<br>';

    //sysContact
    $sysContact[0] = snmpget($snmp_ip, $snmpcommunity, "1.3.6.1.2.1.1.3.0");
    $sysContact[1] = str_replace("STRING:", "", $sysContact[0]);
    $sysContact[2] = str_replace('"', "", $sysContact[1]);
    $sysContact[3] = ltrim($sysContact[2]);
    echo 'SYSTEM CONTACT '.$sysContact[3].'<br>';

    //sysLocation
    $sysLocation[0] = snmpget($snmp_ip, $snmpcommunity, "1.3.6.1.2.1.1.6.0");
    $sysLocation[1] = str_replace("STRING:", "", $sysLocation[0]);
    $sysLocation[2] = str_replace('"', "", $sysLocation[1]);
    $sysLocation[3] = ltrim($sysLocation[2]);
    echo 'SYSTEM LOCATION ' .$sysLocation[3]. '<br>';

    //serialNumber
    $serialNumber[0] = snmpget($snmp_ip, $snmpcommunity, "1.3.6.1.2.1.43.5.1.1.17.1");
    $serialNumber[1] = str_replace("STRING:", "", $serialNumber[0]);
    $serialNumber[2] = str_replace('"', "", $serialNumber[1]);
    $serialNumber[3] = ltrim($serialNumber[2]);
    echo 'SYSTEM SERIAL NUMBER '.$serialNumber[3]. '<br>';
 
    }

 //   echo $sql;

    if ($man=="AC" && $status=="Active"){
    
        //netMask
        $netMask[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.2.1.5.7.1.1.1.4.1");
        $netMask[1] = str_replace("IpAddress:", "", $netMask[0]);
        $netMask[2] = str_replace('"', " ", $netMask[1]);
        $netMask[3] = ltrim($netMask[2]);
      
        echo $netMask[3]. '<br>';

        //workGroup
    $workGroup[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.2.1.5.10.1.1.3.1");
    $workGroup[1] = str_replace("STRING:", "", $workGroup[0]);
    $workGroup[2] = str_replace('"', " ", $workGroup[1]);
    $workGroup[3] = ltrim($workGroup[2]);

    echo $workGroup[3]. '<br>';    
    
          }





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

    
    }


if ($man=="MFX" && $status=="Active"){

    //MACHINE
    //netIP
//    $netIP[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.4322.101.1.2.1.5.7.1.1.1.3.1");
//    $netIP[1] = str_replace("IpAddress:", "", $netIP[0]);
//    $netIP[2] = str_replace('"', " ", $netIP[1]);
//    $netIP[3] = ltrim($netIP[2]);
//    echo $netIP[3]. '<br>';
    
    //netMask
    $netMask[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.4322.101.1.2.1.5.7.1.1.1.4.1");
    $netMask[1] = str_replace("IpAddress:", "", $netMask[0]);
    $netMask[2] = str_replace('"', " ", $netMask[1]);
    $netMask[3] = ltrim($netMask[2]);

    echo $netMask[3]. '<br>';
    
    //workGroup
    $workGroup[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.4322.101.1.2.1.5.7.1.1.1.13.1");
    $workGroup[1] = str_replace("STRING:", "", $workGroup[0]);
    $workGroup[2] = str_replace('"', " ", $workGroup[1]);
    $workGroup[3] = ltrim($workGroup[2]);

    echo $workGroup[3]. '<br>';
    
    }
    

    if ($man=="BH" && $status=="Active") {
    
    //netIP
//    $netIP[0] = snmpget($snmp_ip, $snmpcommunity, "1.3.6.1.2.1.4.20.1.1.192.168.0.53");
//    $netIP[1] = str_replace("IpAddress:", "", $netIP[0]);
//    $netIP[2] = str_replace('"', " ", $netIP[1]);
//    echo $netIP[2]. '<br>';
    
//netMask
$netMask[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.2.1.5.7.1.1.1.4.1");
$netMask[1] = str_replace("IpAddress:", "", $netMask[0]);
$netMask[2] = str_replace('"', " ", $netMask[1]);
$netMask[3] = ltrim($netMask[2]);

echo $netMask[3]. '<br>';


    //netMask
   // $netMask[0] = snmpget($snmp_ip, $snmpcommunity, "1.3.6.1.2.1.4.20.1.3.192.168.0.53");
   // $netMask[1] = str_replace("IpAddress:", "", $netMask[0]);
   // $netMask[2] = str_replace('"', " ", $netMask[1]);
   // $netMask[3] = ltrim($netMask[2]);

    //echo $netMask[3]. '<br>';
    
    //workGroup
    $workGroup[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.2.1.5.10.1.1.3.1");
    $workGroup[1] = str_replace("STRING:", "", $workGroup[0]);
    $workGroup[2] = str_replace('"', " ", $workGroup[1]);
    $workGroup[3] = ltrim($workGroup[2]);

    echo $workGroup[3]. '<br>';
    
    }

    if ($setup==0 && $status=="Active") {
    mysqli_query($db,"UPDATE asset SET 
    serialNumber='$serialNumber[3]', 
    sysDescr ='$sysDescr[3]',
    sysName ='$sysName[3]',
    sysLocation ='$sysLocation[3]',
    netmask ='$netMask[3]',
    workgroup ='$workGroup[3]',
    setup = 1
    WHERE rs_asset_id=$rs_asset_id");
    }

}
header("Location: add_asset.php");
$db->close();
?>
</body>
<?php include("inc/_footer.php"); ?>
