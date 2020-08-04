<?php
include("inc/_db.php");

//date
$today= date("Y-m-d");    $today= date("Y-m-d H:i:s");


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

  


if ($model=="363"){

$percent = "100";

//tonerCyan
$tonerBlack[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.1");
$tonerBlack[1] = str_replace("INTEGER:", " ", $tonerBlack[0]);
$tonerBlack[2] = str_replace('"', " ", $tonerBlack[1]);
$tonerBlack[3] = ltrim($tonerBlack[2]);

echo "Black Toner: ".$tonerBlack[3]. '<br>';

//drumCartridge
$drumCartridge[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.8");
$drumCartridge[1] = str_replace("INTEGER:", " ", $drumCartridge[0]);
$drumCartridge[2] = str_replace('"', " ", $drumCartridge[1]);
$drumCartridge[3] = ltrim($drumCartridge[2]);
$drumCartridge[4] = ($percent - $drumCartridge[3]);

echo "Drum Cartridge: ".$drumCartridge[4]. '<br>';

//developerCartridge
$developerCartridge[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.3");
$developerCartridge[1] = str_replace("INTEGER:", " ", $developerCartridge[0]);
$developerCartridge[2] = str_replace('"', " ", $developerCartridge[1]);
$developerCartridge[3] = ltrim($developerCartridge[2]);
$developerCartridge[4] = ($percent - $developerCartridge[3]);


echo "Developer Cartridge ".$developerCartridge[4]. '<br>';

//developerBlack
$developerBlack[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.4");
$developerBlack[1] = str_replace("INTEGER:", " ", $developerBlack[0]);
$developerBlack[2] = str_replace('"', " ", $developerBlack[1]);
$developerBlack[3] = ltrim($developerBlack[2]);
$developerBlack[4] = ($percent - $developerBlack[3]);


echo "Developer Black ".$developerBlack[4]. '<br>';

//fusingUnit
$fusingUnit[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.5");
$fusingUnit[1] = str_replace("INTEGER:", " ", $fusingUnit[0]);
$fusingUnit[2] = str_replace('"', " ", $fusingUnit[1]);
$fusingUnit[3] = ltrim($fusingUnit[2]);
$fusingUnit[4] = ($percent - $fusingUnit[3]);

echo "Fusing Unit ".$fusingUnit[4]. '<br>';


//transferRoller
$transferRoller[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.6");
$transferRoller[1] = str_replace("INTEGER:", " ", $transferRoller[0]);
$transferRoller[2] = str_replace('"', " ", $transferRoller[1]);
$transferRoller[3] = ltrim($transferRoller[2]);
$transferRoller[4] = ($percent - $transferRoller[3]);

echo "Transfer Unit Roller ".$transferRoller[4]. '<br>';

//ozoneFilter
$ozoneFilter[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.7");
$ozoneFilter[1] = str_replace("INTEGER:", " ", $ozoneFilter[0]);
$ozoneFilter[2] = str_replace('"', " ", $ozoneFilter[1]);
$ozoneFilter[3] = ltrim($ozoneFilter[2]);
$ozoneFilter[4] = ($percent - $ozoneFilter[3]);

echo "Ozone Filter ".$ozoneFilter[4]. '<br>';

//paperDust
$paperDust[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.8");
$paperDust[1] = str_replace("INTEGER:", " ", $paperDust[0]);
$paperDust[2] = str_replace('"', " ", $paperDust[1]);
$paperDust[3] = ltrim($paperDust[2]);
$paperDust[4] = ($percent - $paperDust[3]);

echo "Paper Dust Remover ".$paperDust[4]. '<br>------------------------------------<br>';


$sql_insert = <<<SQL
    INSERT INTO consumables (asset_id, t_black,  drum_cartridge, developer_cartridge, fusing_unit, transfer_roller, ozone_filter, developer, paper_dust, read_date)
    VALUES ('$rs_asset_id', '$tonerBlack[3]',   '$drumCartridge[4]', '$developerCartridge[4]', '$fusingUnit[4]', '$transferRoller[4]', '$ozoneFilter[4]', '$developerBlack[4]', '$paperDust[4]', '$today')
    SQL;
mysqli_query($db,"$sql_insert");

echo '<br>---------------------------------------------------------------------------------------------------------------------------------------';
echo '<br>'.$sql_insert;
echo '<br>---------------------------------------------------------------------------------------------------------------------------------------<br><br>';

}

}

?>