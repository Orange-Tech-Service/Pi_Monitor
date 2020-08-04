<?PHP

$post_rs_customer_id = $_POST['rs_customer_id'];
$post_rs_asset_id = $_POST['rs_asset_id'];
$post_snmp_ip =  $_POST['ipaddress'];
$post_snmpcommunity = $_POST['snmp_community'];
$post_man = $_POST['machine_type'];

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
    $sysDescr = $row['sysDescr'];

echo '--------------'.$sysDescr.'------------------- <br>';
echo 'SYSTEM DESCRIPTION '.$rs_customer_id. '<br>';
echo 'SYSTEM DESCRIPTION '.$rs_asset_id. '<br>';
echo 'SYSTEM DESCRIPTION '.$snmp_ip. '<br>';
echo 'SYSTEM DESCRIPTION '.$snmpcommunity. '<br>';
echo 'SYSTEM DESCRIPTION '.$man. '<br>';
echo 'SYSTEM DESCRIPTION '.$setup. '<br>';
echo '<br>';
}
    
$sql_insert = <<<SQL
INSERT INTO asset (rs_customer_id, rs_asset_id, ipaddress, snmp_community, machine_type)
VALUES ('$post_rs_customer_id', '$post_rs_asset_id', '$post_snmp_ip', '$post_snmpcommunity','$post_man')
SQL;
mysqli_query($db,"$sql_insert");

echo $sql_insert;

if ($setup==0) {
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




echo '--------------NEW ASSET------------------- <br>';
echo 'SYSTEM DESCRIPTION '.$post_rs_customer_id. '<br>';
echo 'SYSTEM DESCRIPTION '.$post_rs_asset_id. '<br>';
echo 'SYSTEM DESCRIPTION '.$post_snmp_ip. '<br>';
echo 'SYSTEM DESCRIPTION '.$post_snmpcommunity. '<br>';
echo 'SYSTEM DESCRIPTION '.$post_man. '<br>';
echo '<br>';
?>