<?php
$snmpcommunity = 'public';
//$snmp_ip = '192.168.0.53';
$snmp_ip = $_GET["ip"];
$man = $_GET["man"];
$rs_customer_id = $_GET["cid"];
$rs_asset_id = $_GET["aid"];


//date
$today= date("Y-m-d");
echo "Today ".$today." ";
echo $rs_customer_id ;
echo $rs_asset_id ;

//sysDescr
$sysDescr[0] = snmpget($snmp_ip, $snmpcommunity, "1.3.6.1.2.1.1.1.0");
$sysDescr[1] = str_replace("STRING:", " ", $sysDescr[0]);
$sysDescr[2] = str_replace('"', " ", $sysDescr[1]);
echo 'SYSTEM DESCRIPTION '.$sysDescr[2]. '<br>';

//sysName
$sysName[0] = snmpget($snmp_ip, $snmpcommunity, "1.3.6.1.2.1.1.5.0");
$sysName[1] = str_replace("STRING:", " ", $sysName[0]);
$sysName[2] = str_replace('"', " ", $sysName[1]);
echo 'SYSTEM NAME '.$sysName[2]. '<br>';

//sysUpTime
$sysUpTime[0] = snmpget($snmp_ip, $snmpcommunity, "1.3.6.1.2.1.1.3.0");
$sysUpTime[1] = str_replace("Timeticks:", " ", $sysUpTime[0]);
$sysUpTime[2] = str_replace('"', " ", $sysUpTime[1]);
echo $sysUpTime[2]. '<br>';

//sysContact
$sysContact[0] = snmpget($snmp_ip, $snmpcommunity, "1.3.6.1.2.1.1.3.0");
$sysContact[1] = str_replace("STRING:", " ", $sysContact[0]);
$sysContact[2] = str_replace('"', " ", $sysContact[1]);
echo $sysContact[2]. '<br>';

//sysLocation
$sysLocation[0] = snmpget($snmp_ip, $snmpcommunity, "1.3.6.1.2.1.1.6.0");
$sysLocation[1] = str_replace("STRING:", " ", $sysLocation[0]);
$sysLocation[2] = str_replace('"', " ", $sysLocation[1]);
echo $sysLocation[2]. '<br>';



//serialNumber
$serialNumber[0] = snmpget($snmp_ip, $snmpcommunity, "1.3.6.1.2.1.43.5.1.1.17.1");
$serialNumber[1] = str_replace("STRING:", " ", $serialNumber[0]);
$serialNumber[2] = str_replace('"', " ", $serialNumber[1]);
echo $serialNumber[2]. '<br>';

if ($man=="MFX"){

//MACHINE


//netIP
$netIP[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.4322.101.1.2.1.5.7.1.1.1.3.1");
$netIP[1] = str_replace("IpAddress:", " ", $netIP[0]);
$netIP[2] = str_replace('"', " ", $netIP[1]);
echo $netIP[2]. '<br>';

//netMask
$netMask[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.4322.101.1.2.1.5.7.1.1.1.4.1");
$netMask[1] = str_replace("IpAddress:", " ", $netMask[0]);
$netMask[2] = str_replace('"', " ", $netMask[1]);
echo $netMask[2]. '<br>';

//workGroup
$workGroup[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.4322.101.1.2.1.5.7.1.1.1.13.1");
$workGroup[1] = str_replace("STRING:", "", $workGroup[0]);
$workGroup[2] = str_replace('"', " ", $workGroup[1]);
echo $workGroup[2]. '<br>';



//TONER
//tonerBlack
$tonerBlack[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.1");
$tonerBlack[1] = str_replace("INTEGER:", " ", $tonerBlack[0]);
$tonerBlack[2] = str_replace('"', " ", $tonerBlack[1]);
echo 'TONER BLACK '.$tonerBlack[2]. '<br>';

//drumBlack
$drumBlack[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.2");
$drumBlack[1] = str_replace("INTEGER:", " ", $drumBlack[0]);
$drumBlack[2] = str_replace('"', " ", $drumBlack[1]);
echo 'DRUM BLACK '.$drumBlack[2]. '<br>';


//METER COUNTS

//countCopy
$countCopy[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.4322.101.1.1.5.7.2.2.1.5.1.1");
$countCopy[1] = str_replace("INTEGER:", " ", $countCopy[0]);
$countCopy[2] = str_replace('"', " ", $countCopy[1]);
echo 'COUNT COPY '.$countCopy[2]. '<br>';
//Name/OID: .1.3.6.1.4.1.4322.101.1.1.5.7.2.2.1.5.1.1; Value (Integer): 2043

//countFax
$countFax[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.4322.101.1.1.5.7.2.3.1.7.1");
$countFax[1] = str_replace("INTEGER:", " ", $countFax[0]);
$countFax[2] = str_replace('"', " ", $countFax[1]);
echo 'COUNT FAX '.$countFax[2]. '<br>';
//Name/OID: .1.3.6.1.4.1.4322.101.1.1.5.7.2.3.1.7.1; Value (Integer): 7817

//countPrinter


//countList


//countTotal
$countTotal[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.4322.101.1.1.5.7.2.1.1.0");
$countTotal[1] = str_replace("INTEGER:", " ", $countTotal[0]);
$countTotal[2] = str_replace('"', " ", $countTotal[1]);
echo 'COUNT TOTAL '.$countTotal[2]. '<br>';
//Name/OID: .1.3.6.1.4.1.4322.101.1.1.5.7.2.1.1.0; Value (Integer): 129943







}



if ($man=="KM"){

//netIP
$netIP[0] = snmpget($snmp_ip, $snmpcommunity, "1.3.6.1.2.1.4.20.1.1.192.168.0.53");
$netIP[1] = str_replace("IpAddress:", " ", $netIP[0]);
$netIP[2] = str_replace('"', " ", $netIP[1]);
echo $netIP[2]. '<br>';

//netMask
$netMask[0] = snmpget($snmp_ip, $snmpcommunity, "1.3.6.1.2.1.4.20.1.3.192.168.0.53");
$netMask[1] = str_replace("IpAddress:", " ", $netMask[0]);
$netMask[2] = str_replace('"', " ", $netMask[1]);
echo $netMask[2]. '<br>';

//workGroup
$workGroup[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.2.1.5.10.1.1.3.1");
$workGroup[1] = str_replace("STRING:", "", $workGroup[0]);
$workGroup[2] = str_replace('"', " ", $workGroup[1]);
echo $workGroup[2]. '<br>';





//tonerCyan
$tonerCyan[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.1");
$tonerCyan[1] = str_replace("INTEGER:", " ", $tonerCyan[0]);
$tonerCyan[2] = str_replace('"', " ", $tonerCyan[1]);
echo $tonerCyan[2]. '<br>';

//tonerMagenta
$tonerMagenta[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.2");
$tonerMagenta[1] = str_replace("INTEGER:", " ", $tonerMagenta[0]);
$tonerMagenta[2] = str_replace('"', " ", $tonerMagenta[1]);
echo $tonerMagenta[2]. '<br>';

//tonerYellow
$tonerYellow[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.3");
$tonerYellow[1] = str_replace("INTEGER:", " ", $tonerYellow[0]);
$tonerYellow[2] = str_replace('"', " ", $tonerYellow[1]);
echo $tonerYellow[2]. '<br>';

//tonerBlack
$tonerBlack[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.4");
$tonerBlack[1] = str_replace("INTEGER:", " ", $tonerBlack[0]);
$tonerBlack[2] = str_replace('"', " ", $tonerBlack[1]);
echo $tonerBlack[2]. '<br>';

//IMAGING UNITS

//imagerCyan
$imagerCyan[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.5");
$imagerCyan[1] = str_replace("INTEGER:", " ", $imagerCyan[0]);
$imagerCyan[2] = str_replace('"', " ", $imagerCyan[1]);
echo $imagerCyan[2]. '<br>';

//imagerMagenta
$imagerMagenta[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.6");
$imagerMagenta[1] = str_replace("INTEGER:", " ", $imagerMagenta[0]);
$imagerMagenta[2] = str_replace('"', " ", $imagerMagenta[1]);
echo $imagerMagenta[2]. '<br>';

//imagerYellow
$imagerYellow[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.7");
$imagerYellow[1] = str_replace("INTEGER:", " ", $imagerYellow[0]);
$imagerYellow[2] = str_replace('"', " ", $imagerYellow[1]);
echo $imagerYellow[2]. '<br>';

//MISC


//drumCartridge
$drumCartridge[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.8");
$drumCartridge[1] = str_replace("INTEGER:", " ", $drumCartridge[0]);
$drumCartridge[2] = str_replace('"', " ", $drumCartridge[1]);
echo $drumCartridge[2]. '<br>';

//developerCartridge
$developerCartridge[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.9");
$developerCartridge[1] = str_replace("INTEGER:", " ", $developerCartridge[0]);
$developerCartridge[2] = str_replace('"', " ", $developerCartridge[1]);
echo $developerCartridge[2]. '<br>';

//wasteBox
$wasteBox[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.10");
$wasteBox[1] = str_replace("INTEGER:", " ", $wasteBox[0]);
$wasteBox[2] = str_replace('"', " ", $wasteBox[1]);
echo $wasteBox[2]. '<br>';


//fusingUnit
$fusingUnit[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.11");
$fusingUnit[1] = str_replace("INTEGER:", " ", $fusingUnit[0]);
$fusingUnit[2] = str_replace('"', " ", $fusingUnit[1]);
echo $fusingUnit[2]. '<br>';

//transferBelt
$transferBelt[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.12");
$transferBelt[1] = str_replace("INTEGER:", " ", $transferBelt[0]);
$transferBelt[2] = str_replace('"', " ", $transferBelt[1]);
echo $transferBelt[2]. '<br>';

//transferRoller
$transferRoller[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.13");
$transferRoller[1] = str_replace("INTEGER:", " ", $transferRoller[0]);
$transferRoller[2] = str_replace('"', " ", $transferRoller[1]);
echo $transferRoller[2]. '<br>';

//ozoneFilter
$ozoneFilter[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.14");
$ozoneFilter[1] = str_replace("INTEGER:", " ", $ozoneFilter[0]);
$ozoneFilter[2] = str_replace('"', " ", $ozoneFilter[1]);
echo $ozoneFilter[2]. '<br>';

//tonerFilter
$tonerFilter[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.15");
$tonerFilter[1] = str_replace("INTEGER:", " ", $tonerFilter[0]);
$tonerFilter[2] = str_replace('"', " ", $tonerFilter[1]);
echo $tonerFilter[2]. '<br>';

//stapleCartridge
$stapleCartridge[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.2.1.43.11.1.1.9.1.16");
$stapleCartridge[1] = str_replace("INTEGER:", " ", $stapleCartridge[0]);
$stapleCartridge[2] = str_replace('"', " ", $stapleCartridge[1]);
echo $stapleCartridge[2]. '<br>';

//finisher
$finisher[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.1.5.5.1.1.2.26");
$finisher[1] = str_replace("INTEGER:", " ", $finisher[0]);
$finisher[2] = str_replace('"', "", $finisher[1]);
echo $finisher[2]. '<br>';


//METER COUNTS

////GET COLOR TOTALS

//fullColor PRINT COUNTER
$fullColor[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.1.5.7.2.2.1.5.2.2");
$fullColor[1] = str_replace("INTEGER:", " ", $fullColor[0]);
$fullColor[2] = str_replace('"', " ", $fullColor[1]);
//echo $fullColor[2]. '<br>';


//fullColorCopy COPY COUNTER
$fullColorCopy[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.1.5.7.2.2.1.5.2.1");
$fullColorCopy[1] = str_replace("INTEGER:", " ", $fullColorCopy[0]);
$fullColorCopy[2] = str_replace('"', " ", $fullColorCopy[1]);
//echo $fullColorCopy[2]. '<br>';


//singleColorCopy COPY COUNTER
$singleColorCopy[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.1.5.7.2.2.1.5.3.1");
$singleColorCopy[1] = str_replace("INTEGER:", " ", $singleColorCopy[0]);
$singleColorCopy[2] = str_replace('"', " ", $singleColorCopy[1]);
//echo $singleColorCopy[2]. '<br>';


//scanFullColor COPY COUNTER
$scanFullColor[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.1.5.7.2.3.1.11.1");
$scanFullColor[1] = str_replace("INTEGER:", " ", $scanFullColor[0]);
$scanFullColor[2] = str_replace('"', " ", $scanFullColor[1]);
//echo $scanFullColor[2]. '<br>';





//copy2Color COPY COUNTER
$copy2Color[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.1.5.7.2.2.1.5.4.1");
$copy2Color[1] = str_replace("INTEGER:", " ", $copy2Color[0]);
$copy2Color[2] = str_replace('"', " ", $copy2Color[1]);
//echo $copy2Color[2]. '<br>';



echo 'TOTAL COLOR'.($fullColor[2]+$fullColorCopy[2]+$singleColorCopy[2]+$scanFullColor[2]+$copy2Color[2]);





//totalBWColor TOTAL BY COLOR
$totalBWColor[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.1.5.7.2.1.1.0");
$totalBWColor[1] = str_replace("INTEGER:", " ", $totalBWColor[0]);
$totalBWColor[2] = str_replace('"', "", $totalBWColor[1]);
//echo $totalBWColor[2]. '<br>';



////GET BLACK TOTALS


//black PRINT COUNTER
$black[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.1.5.7.2.2.1.5.1.2");
$black[1] = str_replace("INTEGER:", " ", $black[0]);
$black[2] = str_replace('"', "", $black[1]);
//echo $black[2]. '<br>';

//totalBlack COPY COUNTER
$totalBlack[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.1.5.7.2.2.1.5.1.1");
$totalBlack[1] = str_replace("INTEGER:", " ", $totalBlack[0]);
$totalBlack[2] = str_replace('"', "", $totalBlack[1]);
//echo $totalBlack[2]. '<br>';

//totalColor SCAN CONTROL
$totalColor[0] = snmpget($snmp_ip, $snmpcommunity, " .1.3.6.1.4.1.18334.1.1.1.5.7.2.2.1.5.2.1");
$totalColor[1] = str_replace("INTEGER:", " ", $totalColor[0]);
$totalColor[2] = str_replace('"', "", $totalColor[1]);
//echo $totalColor[2]. '<br>';

//echo 'TOTAL BLACK'.($black[2]+$totalBlack[2]+$totalColor[2]);

//total2Color
$total2Color[0] = snmpget($snmp_ip, $snmpcommunity, ".1.3.6.1.4.1.18334.1.1.1.5.7.2.2.1.5.4.1");
$total2Color[1] = str_replace("INTEGER:", " ", $total2Color[0]);
$total2Color[2] = str_replace('"', "", $total2Color[1]);
//echo $total2Color[2]. '<br>';


echo 'TOTAL BLACK'.($black[2]+$totalBlack[2]+$totalColor[2]+$total2Color[2]);


}

?>