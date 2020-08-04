<?php include("inc/_header.php"); ?>

<body class="d-flex flex-column h-100">

<?php 
include("inc/_topnav.php");
include("inc/_db.php");

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

if ($_GET['action']=="remove") { 
    $get_id = $_GET['id'];
    echo "DELETE - ".$get_id;
    $sql_remove = <<<SQL
DELETE FROM asset WHERE id=$get_id
SQL;
mysqli_query($db,"$sql_remove");
header("Location: asset_details.php");

}

?>

 
<!-- Begin page content -->
<main role="main" class="flex-shrink-0">
  <div class="container">

  <h2 class="mt-5">Assets List</h2>        
    <p class="lead">A list of all assets being monitored.</p>
    <p class="lead"><a class="btn btn-primary btn-md "  href="add_asset.php">Add New</a> <a class="btn btn-primary btn-md "  href="get_meter.php">Run Meters</a> <a class="btn btn-primary btn-md "  href="get_consumables.php">Run Consumables</a></p>
   
    <div class="row">
 <div class="col-md-12 order-md-1">
      <h4 class="d-flex justify-content-between align-items-center mb-3">
        <span class="text-muted">Your Assets</span>
        <span class="badge badge-secondary badge-pill">
        <?PHP echo $result->num_rows;?>
        </span>
      </h4>
      
      <ul class="list-group mb-3">
<?php
while($row = $result->fetch_assoc()){
    $rs_customer_id = $row['rs_customer_id'];
    $rs_asset_id = $row['rs_asset_id'];
    $snmp_ip =  $row['ipaddress'];
    $snmpcommunity = $row['snmp_community'];
    $man = $row['machine_type'];
    $setup = $row['setup'];
    $sysDescr = $row['sysDescr'];
    $id = $row['id'];
    $status = $row['status'];
    $serialNumber = $row['serialNumber'];
    $sysLocation = $row['sysLocation'];

    if($setup==1){        
      $setup = "Complete";
  }
  else {
      $setup = "New";  
  }

    if ($setup=="Complete") {


      if ($status=="Active"){


    $sql_meter = "SELECT * FROM `meter_count` WHERE asset_id=$rs_asset_id ORDER BY id DESC LIMIT 1";
    
    if(!$result_meter = $db->query($sql_meter)){
      die('There was an error running the query [' . $db->error . ']');
    }

    while($row = $result_meter->fetch_assoc())
    {
    $count_black = $row['count_bw'];
    $count_color = $row['count_color'];
    $count_scan = $row['count_scan'];
    $count_feet = $row['sq_foot'];
    $meter_read_date = $row['read_date'];
    }
        

        echo '
        <div style="background-color:#11CB05; padding-left:15px;">
        <small class="text-light">Active</small>
        </div>
        <li class="list-group-item d-flex justify-content-between lh-condensed">
        ';
      }
      else{
        echo '
        <div style="background-color:#CB2605; padding-left:15px;">
        <small class="text-light">Disabled</small>
        </div>
        <li class="list-group-item d-flex justify-content-between bg-light">
        ';
      }

        echo   '
        <div>
          <h6 class="my-0">'.$sysDescr.'</h6>
          <small class="text-muted">Serial#: '.$serialNumber.'</small><br>
          <small class="text-muted">Assset ID: '.$rs_asset_id.'</small><br>
          <small class="text-muted">Machine Type: '.$man.'</small><br>
          <small class="text-muted">System Location: '.$sysLocation.'</small><br>
          <small class="text-muted">Status: '.$setup.'</small><br>
          <span>
          <small style="padding-right:15px;" class="text-muted">Black: '.$count_black.'</small>
          <small style="padding-right:15px;" class="text-muted">Color: '.$count_color.'</small>
          <small style="padding-right:15px;" class="text-muted">Scans: '.$count_scan.'</small>
          <small style="padding-right:15px;" class="text-muted">Sq Feet: '.$count_feet.'</small>
          <small style="padding-right:15px;" class="text-muted">Read Date: '.$meter_read_date.'</small>
          </span>

          </div>
        <span class="">'.$snmp_ip.'<br>
        <a class="btn btn-info btn-sm "  href="asset_single.php?id='.$id.'&aid='.$rs_asset_id.'">Details</a>
        <a class="btn btn-danger btn-sm "  data-confirm="Are you sure you want to remove this asset?" href="?action=remove&id='.$id.'">Remove</a>
        </span>
        </li>
      ';

    
    
} else {

    echo   ' 
    <div style="background-color:#FFC300; padding-left:15px;">
    <small class="text-light">Incomplete</small>
    </div>
    <li class="list-group-item d-flex justify-content-between bg-light">
    <div class="text-danger">
      <h6 class="my-0">'.$sysDescr.'</h6>
      <small>CID: '.$rs_customer_id.'</small><br>
      <small>AID: '.$rs_asset_id.'</small>
    </div>
    <span class="text-danger">'.$snmp_ip.'<br>
    <a data-confirm="Are you sure you want to remove this asset?" href="?action=remove&id='.$id.'">Remove</a><br><a data-confirm="Running the SNMP query will try to connect to the printer and request more information used for the asset record." href="setup.php">Finish Setup</a>
  </span>
    </li>
  ';  
}
}

if (isset($_POST['submitted'])) { 
    $post_rs_customer_id = $_POST['rs_customer_id'];
    $post_rs_asset_id = $_POST['rs_asset_id'];
    $post_snmp_ip =  $_POST['ipaddress'];
    $post_snmpcommunity = $_POST['snmp_community'];
    $post_man = $_POST['machine_type'];
    
  $sql_insert = "INSERT INTO `asset` (rs_customer_id, rs_asset_id, ipaddress, snmp_community, machine_type)
  VALUES ('$post_rs_customer_id', '$post_rs_asset_id', '$post_snmp_ip', '$post_snmpcommunity','$post_man')";
  
    mysqli_query($db,"$sql_insert");
    header("Location: asset_details.php");


    
    } 
?>
      </ul>
    </div>
   
   
  </div>


  </div>
</main>
<?php 
include("inc/_footer.php");
$db->close();
?>