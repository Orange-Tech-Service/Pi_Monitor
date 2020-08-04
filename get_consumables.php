<?php
include("inc/_db.php");

//date
$today= date("Y-m-d");

$db = new mysqli($server, $db_user, $db_pass, $db_name);

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
    $model = $row['model'];
    $setup = $row['setup'];
    $status = $row['status'];

    if ($status=="Active"){


    


    $today= date("Y-m-d H:i:s");
   // echo "Today ".$today."<br>";
   // echo 'RSID: '.$rs_customer_id . '<br>';
    
    $sysDescr[0] = snmpget($snmp_ip, $snmpcommunity, "1.3.6.1.2.1.1.1.0");
    $sysDescr[1] = str_replace("STRING:", "", $sysDescr[0]);
    $sysDescr[2] = str_replace('"', "", $sysDescr[1]);
    $sysDescr[3] = ltrim($sysDescr[2]);
    //echo 'SYSTEM DESCRIPTION '.$sysDescr[3]. '<br>';

    echo '<br>----------------------------------'.$today.'------------------------------';
    echo '<br>----------------------------------'.$sysDescr[3].'------------------------------<br>';
    echo 'Asset ID: '.$rs_asset_id . '<br>';

    //sysName
    $sysName[0] = snmpget($snmp_ip, $snmpcommunity, "1.3.6.1.2.1.1.5.0");
    $sysName[1] = str_replace("STRING:", "", $sysName[0]);
    $sysName[2] = str_replace('"', "", $sysName[1]);
    $sysName[3] = ltrim($sysName[2]);
   // echo 'SYSTEM NAME '.$sysName[3]. '<br>';

    //sysContact
    $sysContact[0] = snmpget($snmp_ip, $snmpcommunity, "1.3.6.1.2.1.1.3.0");
    $sysContact[1] = str_replace("STRING:", "", $sysContact[0]);
    $sysContact[2] = str_replace('"', "", $sysContact[1]);
    $sysContact[3] = ltrim($sysContact[2]);
  //  echo $sysContact[3].'<br>';

    //sysLocation
    $sysLocation[0] = snmpget($snmp_ip, $snmpcommunity, "1.3.6.1.2.1.1.6.0");
    $sysLocation[1] = str_replace("STRING:", "", $sysLocation[0]);
    $sysLocation[2] = str_replace('"', "", $sysLocation[1]);
    $sysLocation[3] = ltrim($sysLocation[2]);
   // echo $sysLocation[3]. '<br>';

    //serialNumber
    $serialNumber[0] = snmpget($snmp_ip, $snmpcommunity, "1.3.6.1.2.1.43.5.1.1.17.1");
    $serialNumber[1] = str_replace("STRING:", "", $serialNumber[0]);
    $serialNumber[2] = str_replace('"', "", $serialNumber[1]);
    $serialNumber[3] = ltrim($serialNumber[2]);
    echo $serialNumber[3]. '<br>';

    echo $man. "<br>";
   
    }
    else{
        echo "NO Active Assets";
    }


   if ($man=="BH" && $status=="Active" && $model=="363"){
        include("get_363.php");
    }

    if ($man=="BH" && $status=="Active" && $model=="754"){
        include("get_754.php");

    }



    if ($man=="MFX" && $status=="Active"){

//MACHINE

//TONER
//tonerBlack
$tonerBlack[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.1");
$tonerBlack[1] = str_replace("INTEGER:", " ", $tonerBlack[0]);
$tonerBlack[2] = str_replace('"', " ", $tonerBlack[1]);
$tonerBlack[3] = ltrim($tonerBlack[2]);

echo 'TONER BLACK: '.$tonerBlack[3]. '<br>';

//drumBlack
$drumBlack[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.2");
$drumBlack[1] = str_replace("INTEGER:", " ", $drumBlack[0]);
$drumBlack[2] = str_replace('"', " ", $drumBlack[1]);
$drumBlack[3] = ltrim($drumBlack[2]);

echo 'DRUM BLACK '.$drumBlack[3]. '<br>';


$sql_insert = <<<SQL
    INSERT INTO consumables (asset_id, t_black, drum_black, read_date)
    VALUES ('$rs_asset_id', '$tonerBlack[3]', '$drumBlack[3]', '$today')
    SQL;
mysqli_query($db,"$sql_insert");

echo '<br>---------------------------------------------------------------------------------------------------------------------------------------';
echo '<br>'.$sql_insert;
echo '<br>---------------------------------------------------------------------------------------------------------------------------------------<br><br>';

}

if ($man=="BH" && $man!="KIP" && $man!="AC" && $man!="MFX" && $model!="363" && $model!="754" && $model!="1050" && $status=="Active"){


//tonerCyan
$tonerCyan[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.1");
$tonerCyan[1] = str_replace("INTEGER:", " ", $tonerCyan[0]);
$tonerCyan[2] = str_replace('"', " ", $tonerCyan[1]);
$tonerCyan[3] = ltrim($tonerCyan[2]);

echo 'TONER CYAN: '. $tonerCyan[3]. '<br>';

//tonerMagenta
$tonerMagenta[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.2");
$tonerMagenta[1] = str_replace("INTEGER:", " ", $tonerMagenta[0]);
$tonerMagenta[2] = str_replace('"', " ", $tonerMagenta[1]);
$tonerMagenta[3] = ltrim($tonerMagenta[2]);

echo 'TONER MAGENTA: '.$tonerMagenta[3]. '<br>';

//tonerYellow
$tonerYellow[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.3");
$tonerYellow[1] = str_replace("INTEGER:", " ", $tonerYellow[0]);
$tonerYellow[2] = str_replace('"', " ", $tonerYellow[1]);
$tonerYellow[3] = ltrim($tonerYellow[2]);

echo 'TONER YELLOW: '.$tonerYellow[3]. '<br>';

//tonerBlack
$tonerBlack[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.4");
$tonerBlack[1] = str_replace("INTEGER:", " ", $tonerBlack[0]);
$tonerBlack[2] = str_replace('"', " ", $tonerBlack[1]);
$tonerBlack[3] = ltrim($tonerBlack[2]);

echo  'TONER BLACK: '.$tonerBlack[3]. '<br>';

//IMAGING UNITS

//imagerCyan
$imagerCyan[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.5");
$imagerCyan[1] = str_replace("INTEGER:", " ", $imagerCyan[0]);
$imagerCyan[2] = str_replace('"', " ", $imagerCyan[1]);
$imagerCyan[3] = ltrim($imagerCyan[2]);

echo 'IMAGE CYAN: '.$imagerCyan[3]. '<br>';

//imagerMagenta
$imagerMagenta[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.6");
$imagerMagenta[1] = str_replace("INTEGER:", " ", $imagerMagenta[0]);
$imagerMagenta[2] = str_replace('"', " ", $imagerMagenta[1]);
$imagerMagenta[3] = ltrim($imagerMagenta[2]);


echo 'IMAGE MAGENTA: '.$imagerMagenta[3]. '<br>';

//imagerYellow
$imagerYellow[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.7");
$imagerYellow[1] = str_replace("INTEGER:", " ", $imagerYellow[0]);
$imagerYellow[2] = str_replace('"', " ", $imagerYellow[1]);
$imagerYellow[3] = ltrim($imagerYellow[2]);

echo 'IMAGE YELLOW: '.$imagerYellow[3]. '<br>';

//MISC


//drumCartridge
$drumCartridge[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.8");
$drumCartridge[1] = str_replace("INTEGER:", " ", $drumCartridge[0]);
$drumCartridge[2] = str_replace('"', " ", $drumCartridge[1]);
$drumCartridge[3] = ltrim($drumCartridge[2]);

echo 'DRUM CARTRIDGE: '.$drumCartridge[3]. '<br>';

//developerCartridge
$developerCartridge[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.9");
$developerCartridge[1] = str_replace("INTEGER:", " ", $developerCartridge[0]);
$developerCartridge[2] = str_replace('"', " ", $developerCartridge[1]);
$developerCartridge[3] = ltrim($developerCartridge[2]);

echo 'DEVELOPER CARTRIDGE: '.$developerCartridge[3]. '<br>';

//wasteBox
$wasteBox[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.10");
$wasteBox[1] = str_replace("INTEGER:", " ", $wasteBox[0]);
$wasteBox[2] = str_replace('"', " ", $wasteBox[1]);
$wasteBox[3] = ltrim($wasteBox[2]);

echo 'WASTE BOX: '.$wasteBox[3]. '<br>';


//fusingUnit
$fusingUnit[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.11");
$fusingUnit[1] = str_replace("INTEGER:", " ", $fusingUnit[0]);
$fusingUnit[2] = str_replace('"', " ", $fusingUnit[1]);
$fusingUnit[3] = ltrim($fusingUnit[2]);

echo 'FUSING UNIT: '.$fusingUnit[3]. '<br>';

//transferBelt
$transferBelt[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.12");
$transferBelt[1] = str_replace("INTEGER:", " ", $transferBelt[0]);
$transferBelt[2] = str_replace('"', " ", $transferBelt[1]);
$transferBelt[3] = ltrim($transferBelt[2]);

echo 'TRANSFER BELT: '.$transferBelt[3]. '<br>';

//transferRoller
$transferRoller[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.13");
$transferRoller[1] = str_replace("INTEGER:", " ", $transferRoller[0]);
$transferRoller[2] = str_replace('"', " ", $transferRoller[1]);
$transferRoller[3] = ltrim($transferRoller[2]);

echo 'TRANSFER ROLLER: '.$transferRoller[3]. '<br>';

//ozoneFilter
$ozoneFilter[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.14");
$ozoneFilter[1] = str_replace("INTEGER:", " ", $ozoneFilter[0]);
$ozoneFilter[2] = str_replace('"', " ", $ozoneFilter[1]);
$ozoneFilter[3] = ltrim($ozoneFilter[2]);

echo 'OZONE FILTER: '.$ozoneFilter[3]. '<br>';

//tonerFilter
$tonerFilter[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.15");
$tonerFilter[1] = str_replace("INTEGER:", " ", $tonerFilter[0]);
$tonerFilter[2] = str_replace('"', " ", $tonerFilter[1]);
$tonerFilter[3] = ltrim($tonerFilter[2]);

echo 'TONER FILTER: '.$tonerFilter[3]. '<br>';

//stapleCartridge
$stapleCartridge[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.16");
$stapleCartridge[1] = str_replace("INTEGER:", " ", $stapleCartridge[0]);
$stapleCartridge[2] = str_replace('"', " ", $stapleCartridge[1]);
$stapleCartridge[3] = ltrim($stapleCartridge[2]);

echo 'STAPLE CARTRIDGE: '.$stapleCartridge[3]. '<br>';

//finisher
$finisher[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.1.5.5.1.1.2.26");
$finisher[1] = str_replace("INTEGER:", " ", $finisher[0]);
$finisher[2] = str_replace('"', "", $finisher[1]);
$finisher[3] = ltrim($finisher[2]);

echo 'FINISHER: '.$finisher[3]. '<br>';


$sql_insert = <<<SQL
    INSERT INTO consumables (asset_id, t_black, t_cyan, t_magenta, t_yellow, i_cyan, i_yellow, i_magenta, drum_cartridge, developer_cartridge, waste_box, fusing_unit, transfer_belt, transfer_roller, ozone_filter, toner_filter, staple_cartridge, finisher, read_date)
    VALUES ('$rs_asset_id', '$tonerBlack[3]', '$tonerCyan[3]','$tonerMagenta[3]', '$tonerYellow[3]', '$imagerCyan[3]', '$imagerYellow[3]', '$imagerMagenta[3]', '$drumCartridge[3]', '$developerCartridge[3]', '$wasteBox[3]', '$fusingUnit[3]', '$transferBelt[3]', '$transferRoller[3]', '$ozoneFilter[3]', '$tonerFilter[3]', '$stapleCartridge[3]', '$finisher[3]', '$today')
    SQL;
mysqli_query($db,"$sql_insert");

echo '<br>---------------------------------------------------------------------------------------------------------------------------------------';
echo '<br>'.$sql_insert;
echo '<br>---------------------------------------------------------------------------------------------------------------------------------------<br><br>';

}





}

?>