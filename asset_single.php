<?php include("inc/_header.php"); 
///TEST COMMIT
$get_id = $_GET['id'];
$get_aid = $_GET['aid'];

?>

<body class="d-flex flex-column h-100">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>

<?php include("inc/_topnav.php"); ?>

<?php 
$db = new mysqli($server, $db_user, $db_pass, $db_name);


if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}

$sql = <<<SQL
    SELECT *
    FROM `asset`
    WHERE id=$get_id
SQL;

$sql_consume = <<<SQL
    SELECT  *
FROM
`consumables`
    WHERE consumables.asset_id=$get_aid
    ORDER BY consumables.id 
DESC
LIMIT 1
SQL;

$sql_meter = <<<SQL
    SELECT *
FROM
`meter_count`
    WHERE asset_id=$get_aid
    ORDER BY id 
DESC
LIMIT 1
SQL;

if(!$result = $db->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
}
if(!$result_consume = $db->query($sql_consume)){
    die('There was an error running the query [' . $db->error . ']');
}

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


    //echo 'Black: '.$count_black;
    //echo 'Color: '.$count_color;
    //echo 'Scans: '.$count_scan;
    //echo 'Sq Feet: '.$count_feet;
    //echo 'Read Date: '.$read_date;



}

while($row = $result_consume->fetch_assoc())
    {
    $t_cyan = $row['t_cyan'];
    $t_magenta = $row['t_magenta'];
    $t_black = $row['t_black'];
    $t_yellow = $row['t_yellow'];+    
    $drumBlack = $row['drum_black'];
    $image_cyan = $row['i_cyan'];
    $image_yellow = $row['i_yellow'];
    $image_magenta = $row['i_magenta'];
    $drum_cartridge = $row['drum_cartridge'];
    $developer_cartridge = $row['developer_cartridge'];
    $waste_box = $row['waste_box'];
    $fusing_unit = $row['fusing_unit'];
    $transfer_belt = $row['transfer_belt'];
    $transfer_roller = $row['transfer_roller'];
    $ozone_filter = $row['ozone_filter'];
    $toner_filter = $row['toner_filter'];
    $staple_cartridge = $row['staple_cartridge'];
    $fnisher = $row['finisher'];
    $developer = $row['developer'];
    $paper_dust = $row['paper_dust'];
    $consumable_read_date = $row['read_date'];



    echo 'CYAN: '.$t_cyan;
    echo 'Magenta: '.$t_magenta;
    echo 'Yellow: '.$t_yellow;
    echo 'Black: '.$t_black;
    echo 'Read Date: '.$read_date;



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
//echo $sql_consume;

?>

<!-- Begin page content -->






<main role="main" class="flex-shrink-0">
  <div class="container">

  
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="logModal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      </div>
    </div>
  </div>

 




  <h2 class="mt-5">Asset Details</h2>        
    <p class="lead">Shows the details for the selected asset.</p>
    <p class="lead"><a class="btn btn-primary btn-md "  href="add_asset.php">Add New</a> <a class="btn btn-primary btn-md "  href="asset_details.php">Asset List</a> <a class="btn btn-primary btn-md "  href="get_meter.php">Run Meters</a> <a class="btn btn-primary btn-md "  href="get_consumables.php">Run Consumables</a></p>
   
    <div class="row">
 <div class="col-md-8 order-md-1">
      <h4 class="d-flex justify-content-between align-items-center mb-3">
        <span class="text-muted">The Asset</span>
        <span class="badge badge-secondary badge-pill">
        <?PHP echo $result->num_rows;?>
        </span>
      </h4>
      
      <ul class="list-group mb-3">
<?php
//echo $sql2;

while($row = $result->fetch_assoc()){
    $rs_customer_id = $row['rs_customer_id'];
    $rs_asset_id = $row['rs_asset_id'];
    $snmp_ip =  $row['ipaddress'];
    $snmpcommunity = $row['snmp_community'];
    $man = $row['machine_type'];
    $setup = $row['setup'];
    $sysDescr = $row['sysDescr'];
    $status = $row['status'];
    $id = $row['id'];
    $serialNumber = $row['serialNumber'];
    $sysLocation = $row['sysLocation'];
    $model = $row['model'];

        if($setup==1){        
        $setup = "Complete";
    }
    else {
        $setup = "New";  
    }


    if ($setup=='Complete') {

      if ($status=="Active"){

        echo '
        <span>
        <a class="btn btn-secondary btn-sm "  href="log_meter.php?id='.$id.'&aid='.$rs_asset_id.'" data-toggle="modal" data-target="#logModal">Meter Log</a>
        <a class="btn btn-secondary btn-sm "  href="log_consumables.php?id='.$id.'&aid='.$rs_asset_id.'" data-toggle="modal" data-target="#logModal">Consumables Log</a>
        <a class="btn btn-danger btn-sm "  data-confirm="Are you sure you want to remove this asset?" href="?action=remove&id='.$id.'">Remove</a>
        </span>
<br>

        <div style="background-color:#11CB05; padding-left:15px;">
        <small class="text-light">Active</small>
        </div>
        <li class="list-group-item justify-content-between lh-condensed">
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
          <small class="text-muted">Serial#: '.$snmp_ip.'</small><br>
          <small class="text-muted">Assset ID: '.$rs_asset_id.'</small><br>
          <small class="text-muted">Machine Type: '.$man.'</small><br>
          <small class="text-muted">Model: '.$model.'</small><br>
          <small class="text-muted">System Location: '.$sysLocation.'</small><br>
          <small class="text-muted">Setup: '.$setup.'</small>

          <hr >
          <h6 class="my-0">Consumables as of ('.$consumable_read_date.')</h6>
          <hr >
          <div class="row">
          <div class="col-12 col-md">';

          if ($man=="BH" && $model!="363" && $model!="754" && $status=="Active"){

          echo   '
          <div class="progress">
          <div class="progress-bar bg-cyan" style="width:'.$t_cyan.'%">Cyan: '.$t_cyan.'%</div>
          </div>
          <br>
          <div class="progress">
          <div class="progress-bar bg-magenta" style="width:'.$t_magenta.'%">Magenta: '.$t_magenta.'%</div>
          </div>
          <br>
          <div class="progress">
          <div class="progress-bar bg-yellow text-primary " style="width:'.$t_yellow.'%">Yellow: '.$t_yellow.'%</div>
          </div>
          <br>
          <div class="progress">
          <div class="progress-bar bg-black" style="width:'.$t_black.'%">Black: '.$t_black.'%</div>
          </div>';
          }

         


          if ($man=="BH" && $model=="363" && $status=="Active"){

            echo   '
            <div class="progress">
            <div class="progress-bar bg-black" style="width:'.$t_black.'%">Black: '.$t_black.'%</div>
            </div>
            <br>
            <div class="progress">
            <div class="progress-bar bg-black" style="width:'.$developer_cartridge.'%">Developing Unit: '.$developer_cartridge.'%</div>
            </div>
            <br>
            <div class="progress">
            <div class="progress-bar bg-black" style="width:'.$developer.'%">Developer: '.$developer.'%</div>
            </div>
            <br> 
            <div class="progress">
            <div class="progress-bar bg-black" style="width:'.$ozone_filter.'%">Ozone Filter: '.$ozone_filter.'%</div>
            </div>
            <br>           
            <div class="progress">
            <div class="progress-bar bg-black" style="width:'.$fusing_unit.'%">Fusing Unit: '.$fusing_unit.'%</div>
            </div>
            <br> 
            <div class="progress">
            <div class="progress-bar bg-black" style="width:'.$transfer_roller.'%">Transfer Unit Roller: '.$transfer_roller.'%</div>
            </div>
            <br>
            <div class="progress">
            <div class="progress-bar bg-black" style="width:'.$paper_dust.'%">Paper Dust Remover: '.$paper_dust.'%</div>
            </div>';
            }


            if ($man=="BH" && $model=="754" && $status=="Active"){

              echo   '
              <div>Black
              <div class="progress"> 
              <div class="progress-bar bg-black" style="width:'.$t_black.'%">'.$t_black.'%</div>
              </div>
              </div>
              <br>
              <div>Drum Unit
              <div class="progress">
              <div class="progress-bar bg-black" style="width:'.$drum_cartridge.'%">'.$drum_cartridge.'%</div>
              </div>
              </div>
              <br>
              <div>Developer Unit
              <div class="progress">
              <div class="progress-bar bg-black" style="width:'.$developer_cartridge.'%">'.$developer_cartridge.'%</div>
              </div>
              </div>
              <br> 
              <div>Ozone Filter
              <div class="progress">
              <div class="progress-bar bg-black" style="width:'.$ozone_filter.'%">'.$ozone_filter.'%</div>
              </div>
              </div>
              <br>           
              <div>Fusing Unit
              <div class="progress">
              <div class="progress-bar bg-black" style="width:'.$fusing_unit.'%">'.$fusing_unit.'%</div>
              </div>
              </div>
              <br> 
              <div>Transfter Belt
              <div class="progress">
              <div class="progress-bar bg-black" style="width:'.$transfer_belt.'%">'.$transfer_belt.'%</div>
              </div>
              </div>
              <br>
              <div>Transfer Roller
              <div class="progress">
              <div class="progress-bar bg-black" style="width:'.$transfer_roller.'%">'.$transfer_roller.'%</div>
              </div>
              </div>
              <br>
              <div>Toner Filter
              <div class="progress">
              <div class="progress-bar bg-black" style="width:'.$toner_filter.'%">'.$toner_filter.'%</div>
              </div>
              </div>
              <br>
              <div>Waste Box
              <div class="progress">
              <div class="progress-bar bg-black" style="width:'.$waste_box.'">'.$waste_box.'%</div>
              </div>
              </div>
              <br>
              <div>Staple Cartridge
              <div class="progress">
              <div class="progress-bar bg-black" style="width:'.$staple_cartridge.'">'.$staple_cartridge.'%</div>
              </div>
              </div>';
              }


          if ($man=="MFX" && $status=="Active"){

            echo   '
            <div class="progress">
            <div class="progress-bar bg-black" style="width:'.$t_black.'%">Black: '.$t_black.'%</div>
            </div>
            <br>
            <div class="progress">
            <div class="progress-bar bg-black" style="width:'.$drumBlack.'%">Drum: '.$drumBlack.'%</div>
            </div>';
            }

          echo   '
          <hr >
          <h6 class="my-0">Meter as of ('.$meter_read_date.')</h6>
          <hr >
          </div>
          </div>

        <!-----Sart----->
        <div class="row">

        <div class="col-3 themed-grid-col">
        <h6 class="my-0">Black</h6>
          <ul class="list-unstyled text-small">
            <li><span class="text-muted" >'.$count_black.'</span></li>
           </ul>
        </div>
        <div class="col-3 themed-grid-col">
        <h6 class="my-0">Color</h6>
          <ul class="list-unstyled text-small">
            <li><span class="text-muted" >'.$count_color.'</span></li>
          </ul>
        </div>
        <div class="col-3 themed-grid-col">
        <h6 class="my-0">Scan</h6>
          <ul class="list-unstyled text-small">
            <li><span class="text-muted" >'.$count_scan.'</span></li>
          </ul>
        </div>
        <div class="col-3 themed-grid-col">
        <h6 class="my-0">Sq Feet</h6>
        <ul class="list-unstyled text-small">
          <li><span class="text-muted" >'.$count_feet.'</span></li>
        </ul>
      </div>
      
      </div>
        <!------ END ----->
         </div>
         
      
        </li>
      ';
    
    
} else {

    echo   '
    <li class="list-group-item d-flex justify-content-between bg-light">
    <div class="text-warning">
      <h6 class="my-0">'.$sysDescr.'</h6>
      <small>CID: '.$rs_customer_id.'</small><br>
      <small>AID: '.$rs_asset_id.'</small>
    </div>
    <span class="text-warning">'.$snmp_ip.'<br>
    <a data-confirm="Running the SNMP query will try to connect to the printer and request more information used for the asset record." href="setup.php">Query</a>
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
    $post_reset = $_POST['reset'];


    
    $sql_update = <<<SQL
    UPDATE asset 
    SET machine_type='$post_man', status='$post_status', setup='$post_reset'
    WHERE id=$id
    SQL;
   mysqli_query($db,"$sql_update");
   echo "<meta http-equiv='refresh' content='0'>";
   //echo $sql_update;
    
    } 
?>
      </ul>
    </div>
    <div class="col-md-4 order-md-2 mb-4">
    <h4 class="mb-3">Edit Asset</h4>
      <form class="needs-validation" novalidate action="asset_single.php?id=<?php echo $id;?>&aid=<?php echo $rs_asset_id;?>" method="POST" data-confirm="CAUTION: Please ensure you have the correct MACHINE TYPE or the information returned will not be sufficient for meter counts and consumables!">
       
       
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="rs_customer_id">Customer ID</label>
            <input type="text" class="form-control" id="rs_customer_id" name="rs_customer_id" placeholder="" value="<?php echo $rs_customer_id;?>" required readonly>
            <div class="invalid-feedback">
              Valid RS Customer ID is required.
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <label for="rs_asset_id">Asset ID</label>
            <input type="text" class="form-control" id="rs_asset_id" name="rs_asset_id" placeholder="" value="<?php echo $rs_asset_id;?>" required readonly> 
            <div class="invalid-feedback">
              Valid RS Asset ID is required.
            </div>
          </div>
        </div>

        <div class="mb-3">
        <label for="machine_type">Machine Type</label>

        <select class="custom-select d-block w-100" id="machine_type" name="machine_type" required>
        <option value="">Choose...</option>
        <option value="BH"<?php if ($man=='BH') {echo 'selected="selected"';} ?>>BizHub</option>
        <option value="MFX"<?php if ($man=='MFX') {echo 'selected="selected"';} ?>>Muratec</option>
        <option value="KIP"<?php if ($man=='KIP') {echo 'selected="selected"';} ?>>KIP</option>
        <option value="HP"<?php if ($man=='HP') {echo 'selected="selected"';} ?>>HP</option>
        <option value="AC"<?php if ($man=='AC') {echo 'selected="selected"';} ?>>KM - Accurrio</option>
        </select>
        </div>

        <div class="mb-3">
          <label for="ipaddress">Printer IP Address <span class="text-muted">(required)</span></label>
          <input type="text" class="form-control" id="ipaddress" name="ipaddress" value="<?php echo $snmp_ip;?>" readonly>
          <div class="invalid-feedback">
            Please enter a valid IP Address.
          </div>
        </div>

        <div class="mb-3">
          <label for="snmp_community">SNMP Community</label>
          <input type="text" class="form-control" id="snmp_community" name="snmp_community" value="<?php echo $snmpcommunity;?>" readonly>
          <div class="invalid-feedback">
            Please enter the SNMP Community.
          </div>
        </div>
        <div class="row">
        <div class="col-md-6 mb-3">
            <label for="query">Asset Status</label>
            <select class="custom-select d-block w-100" id="status" name="status" required>
            <option value="Active"<?php if ($status=='Active') {echo 'selected="selected"';} ?>>Active</option>
            <option value="Disabled"<?php if ($status=='Disabled') {echo 'selected="selected"';} ?>>Disabled</option>
            </select>
            <div class="invalid-feedback">
              Please select a valid asset status.
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <label for="query">Reset Asset</label>
            <select class="custom-select d-block w-100" id="reset" name="reset" required>
              <option value="1">No</option>
              <option value="0">Yes</option>
            </select>
            <div class="invalid-feedback">
              Please select a valid machine type.
            </div>
          </div>
           </div>
        <hr class="mb-4">
        <input type="hidden" name="id" value="<?php echo $id;?>" />
        <input class="btn btn-primary btn-lg btn-block"  type="submit" value="Save" name="submitted">
      </form>

</div>
   
  </div>


  </div>
</main>
<script>
  $.ajaxSetup({ cache: false })
  $('#logModal').on('hidden.bs.modal', function () {
 location.reload();
})
  </script>
<?php include("inc/_footer.php");
$db->close();
?>