<?php include("inc/_header.php");
//ANOTHER VC GIT TEST
 ?>
<body class="d-flex flex-column h-100">

<?php include("inc/_topnav.php"); ?>

<?php 
$db = new mysqli($server, $db_user, $db_pass, $db_name);

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}

$sql = "SELECT * FROM `asset`";

if(!$result = $db->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
}

if ($_GET['action']=="remove") { 
$get_id = $_GET['id'];
$get_aid = $_GET['aid'];

// echo "DELETE - ".$get_id;
$sql_remove = "DELETE FROM asset WHERE id=$get_id";
mysqli_query($db,"$sql_remove");

$sql_remove_meter = "DELETE FROM meter_count WHERE asset_id=$get_aid";
mysqli_query($db,"$sql_remove_meter");

$sql_remove_consumables = "DELETE FROM consumables WHERE asset_id=$get_aid";
mysqli_query($db,"$sql_remove_consumables");

header("Location: add_asset.php");
}
?>

<!-- Begin page content -->
<main role="main" class="flex-shrink-0">
  <div class="container">
  <h2 class="mt-5">Add an Asset</h2>
    <p class="lead">Start adding your new assets below.</p>
   

    <div class="row">
    <div class="col-md-4 order-md-2 mb-4">
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


    if ($setup==1) {
      if ($status=="Active"){

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
        echo'
        <div>
        <h6 class="my-0">'.$sysDescr.'</h6>          
          <small class="text-muted">CID: '.$rs_customer_id.'</small><br>
          <small class="text-muted">AID: '.$rs_asset_id.'</small>
        </div>';


echo '
        <span class="text-muted">'.$snmp_ip.'<br>';
        echo '
        <a data-confirm="Are you sure you want to remove this asset?" href="?action=remove&id='.$id.'&aid='.$rs_asset_id.'">Remove</a><br>
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
    <a data-confirm="Are you sure you want to remove this asset?" href="?action=remove&id='.$id.'">Remove</a><br> <a data-confirm="Running the SNMP query will try to connect to the printer and request more information used for the asset record." href="setup.php">Finish Setup</a>
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
    $post_status = $_POST['status'];

    
    $sql_insert = "INSERT INTO asset (rs_customer_id, rs_asset_id, ipaddress, snmp_community, machine_type, status)
    VALUES ('$post_rs_customer_id', '$post_rs_asset_id', '$post_snmp_ip', '$post_snmpcommunity','$post_man', '$post_status')";
    mysqli_query($db,"$sql_insert");
    
    //    echo "<meta http-equiv='refresh' content='0'>";
    header("location:update_asset.php"); // your current page

    } 
    echo ' <br><a  class="btn btn-primary btn-lg btn-block" href="asset_details.php">View All</a>';
    ?>
      </ul>
    </div>

    <div class="col-md-8 order-md-1">
      <h4 class="mb-3">RepairShopr Information</h4>
      <form class="needs-validation" novalidate action="add_asset.php" method="POST" data-confirm="Please ensure the IPADDRESS is correct!">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="rs_customer_id">Customer ID</label>
            <input type="text" class="form-control" id="rs_customer_id" name="rs_customer_id" placeholder="" value="<?php echo $rs_customer_id;?> " readonly>
            <div class="invalid-feedback">
              Valid RS Customer ID is required.
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <label for="rs_asset_id">Asset ID</label>
            <input type="text" class="form-control" id="rs_asset_id" name="rs_asset_id" placeholder="" value="" required>
            <div class="invalid-feedback">
              Valid RS Asset ID is required.
            </div>
          </div>
        </div>

       

        <div class="mb-3">
          <label for="ipaddress">Printer IP Address <span class="text-muted">(required)</span></label>
          <input type="text" class="form-control" id="ipaddress" name="ipaddress" placeholder=" ">
          <div class="invalid-feedback">
            Please enter a valid IP Address.
          </div>
        </div>

        <div class="mb-3">
          <label for="snmp_community">SNMP Community</label>
          <input type="text" class="form-control" id="snmp_community" name="snmp_community" placeholder="" value="public" required>
          <div class="invalid-feedback">
            Please enter the SNMP Community.
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="machine_type">Asset Type</label>
            <select class="custom-select d-block w-100" id="machine_type" name="machine_type" required>
              <option value="">Choose...</option>
              <option value="BH">BizHub</option>
              <option value="MFX">Muratec</option>
              <option value="KIP">KIP</option>
              <option value="HP">HP</option>
              <option value="AC">KM - Accurrio</option>
            </select>
            <div class="invalid-feedback">
              Please select a valid machine type.
            </div>
          </div>

          <div class="col-md-6 mb-3">
            <label for="status">Asset Status</label>
            <select class="custom-select d-block w-100" id="status" name="status" required>
              <option value="Active">Active</option>
              <option value="Disabled">Disabled</option>
            </select>
            <div class="invalid-feedback">
              Please select a valid asset satus.
            </div>
          </div>
  </div>

        <hr class="mb-4">
        <input type="hidden" name="form_submitted" value="1" />
        <input class="btn btn-primary btn-lg btn-block"  type="submit" value="Add Asset" name="submitted">
      </form>
    </div>
  </div>


  </div>
</main>
<?php 
include("inc/_footer.php");
$db->close();
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  sleep(5);
  header ("Locatiion:update_asset.php");
} else  {
  echo "Dead";
}

?>