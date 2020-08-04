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
  <h2 class="mt-5">Customer Details</h2>
    <p class="lead">Please add the customer details below.</p>
   

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
   // $http_port = $row['http_port'];
   // $ssh_port = $row['ssh_port'];
   
   


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
        <a href="asset_single.php?id='.$id.'&aid='.$rs_asset_id.'">Details</a>
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
    <a data-confirm="Running the SNMP query will try to connect to the printer and request more information used for the asset record." href="setup.php">Finish Setup</a>
  </span>
    </li>
  ';  
}
  
}

if (isset($_POST['submitted'])) { 
    $post_rs_customer_id = $_POST['rs_customer_id'];
    $post_rs_business_name =  $_POST['rs_business_name'];
    $post_email = $_POST['email'];
    $post_tech_email = $_POST['tech_email'];
    $post_contact = $_POST['contact'];
    $post_phone = $_POST['phone'];
    $post_http_port = $_POST['http_port'];
    $post_ssh_port = $_POST['ssh_port'];
    $post_ip_address = $ip_address;

   $sql_insert = "INSERT INTO customer (rs_customer_id, rs_business_name, email, tech_email, contact, phone, http_port, ssh_port, ip_address)
    VALUES ('$post_rs_customer_id', '$post_rs_business_name', '$post_email', '$post_tech_email','$post_contact', '$post_phone' , '$post_http_port' , '$post_ssh_port', '$post_ip_address')";
    mysqli_query($db,"$sql_insert");
    echo "<meta http-equiv='refresh' content='0'>";
  //  header("location:add_asset.php"); // your current page
      header("location:create_monitor.php"); // your current page

    } 
    echo ' <br><a  class="btn btn-primary btn-lg btn-block" href="asset_details.php">View All</a>';
    ?>
      </ul>
    </div>

    <div class="col-md-8 order-md-1">
      <h4 class="mb-3">RepairShopr Information</h4>
      

     <?php if ($cust_rs_customer_id<1){
echo '

<form class="needs-validation" novalidate action="customer_details.php" method="POST" data-confirm="Please ensure the IPADDRESS is correct!">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="rs_customer_id">Customer ID</label>
            <input type="text" class="form-control" id="rs_customer_id" name="rs_customer_id" placeholder="" value="" required>
            <div class="invalid-feedback">
              Valid RS Customer ID is required.
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <label for="rs_business_name">Business Name</label>
            <input type="text" class="form-control" id="rs_business_name" name="rs_business_name" placeholder="" value="" required>
            <div class="invalid-feedback">
              Valid RS Business Name is required.
            </div>
          </div>
        </div>


        <div class="row">
        <div class="col-md-6 mb-3">
          <label for="http_port">HTTP Port</label>
          <input type="text" class="form-control" id="http_port" name="http_port" placeholder="" value="' .$cust_http_port.'" required>
          <div class="invalid-feedback">
            Valid RS Customer ID is required.
          </div>
        </div>
        <div class="col-md-6 mb-3">
          <label for="ssh_port">SSH Port</label>
          <input type="text" class="form-control" id="ssh_port" name="ssh_port" placeholder="" value="' .$cust_ssh_port.'" required>
          <div class="invalid-feedback">
            Valid RS Business Name is required.
          </div>
        </div>
      </div>


        <div class="mb-3">
          <label for="email">Customer Email Address <span class="text-muted">(required)</span></label>
          <input type="text" class="form-control" id="email" name="email" placeholder="">
          <div class="invalid-feedback">
            Please enter a valid Email Address.
          </div>
        </div>

        <div class="mb-3">
          <label for="tech_email">Tech Email Address</label>
          <input type="text" class="form-control" id="tech_email" name="tech_email" value="tech@copiers4sale.com" required>
          <div class="invalid-feedback">
            Please enter the Technician Email.
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="contact">Key Operator Name</label>
            <input type="text" class="form-control" id="contact" name="contact" placeholder="" value="" required>
            <div class="invalid-feedback">
              Valid Key Operator required.
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <label for="phone">Customer Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" placeholder="" value="" required>
            <div class="invalid-feedback">
              Valid RS phone  is required.
            </div>
          </div>
        </div>
  </div>
</div>
        <hr class="mb-4">
        <input type="hidden" name="form_submitted" value="1" />
        <input class="btn btn-primary btn-lg btn-block"  type="submit" value="Save Customer" name="submitted">
      </form>


';
//header("Location: customer_details.php");
}
else {
echo '

<div class="card-deck mb-4 text-center">
    <div class="card mb-4 shadow-sm">
      <div class="card-header">
        <h4 class="my-0 font-weight-normal">Customer</h4>
      </div>
      <div class="card-body">
        <h3 class="card-title pricing-card-title"><small class="text-muted">' .$cust_rs_business_name.'</small></h3>
        <ul class="list-unstyled mt-1 mb-4">
          <li><small class="text-muted">Customer ID: ' .$cust_rs_customer_id.' </small></li>
          <li><small class="text-muted">Key Operator: ' .$cust_contact.' </small></li>
          <li><small class="text-muted">Phone: ' .$cust_phone.' </small></li>
          <li><small class="text-muted">' .$cust_email.' </small></li>
        </ul>
      </div>
    </div>
   
    <div class="card mb-4 shadow-sm">
      <div class="card-header">
        <h4 class="my-0 font-weight-normal">Ports</h4>
      </div>
      <div class="card-body">
      <h3 class="card-title pricing-card-title"><small class="text-muted">SSH: ' .$cust_ssh_port.'</small></h3>
      <h3 class="card-title pricing-card-title"><small class="text-muted">HTTP: ' .$cust_http_port.'</small></h3>

       
      </div>
    </div>
  </div> 
';
}
?>
   
    </div>
  </div>

  </div>
</main>
<?php include("inc/_footer.php");
$db->close();
?>