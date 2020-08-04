<?php 
include("inc/_header.php"); 
$get_id = $_GET['id'];
$get_aid = $_GET['aid'];
?>

<body class="d-flex flex-column h-100">


<?php $db = new mysqli('localhost', 'ot_admin', '0rang3T3ch4758!', 'webcron');

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}

$sql = <<<SQL
    SELECT *
    FROM `asset`
    WHERE id=$get_id
SQL;

$sql_consume = <<<SQL
    SELECT
consumables.id,
consumables.asset_id,
consumables.t_cyan,
consumables.t_yellow,
consumables.t_magenta,
consumables.t_black,
consumables.drum_black,
consumables.read_date
FROM
consumables
    WHERE consumables.asset_id=$get_aid
    ORDER BY consumables.read_date

DESC
SQL;

$sql_meter = <<<SQL
    SELECT *
FROM
meter_count
    WHERE asset_id=$get_aid
    ORDER BY id 
DESC
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
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
    </div> 
    <div class="modal-body">

<main role="main" class="flex-shrink-0">
  <div class="">

  <h2 class="mt-5">Log - Consumables</h2>        
   
    <div class="row">
 <div class="col-md-12 order-md-1">
      <h4 class="d-flex justify-content-between align-items-center mb-3">
       
      </h4>
      
      <ul class="list-group mb-3">
<?php
echo $sql2;

while($row = $result->fetch_assoc()){
    $rs_customer_id = $row['rs_customer_id'];
    $rs_asset_id = $row['rs_asset_id'];
    $snmp_ip =  $row['ipaddress'];
    $snmpcommunity = $row['snmp_community'];
    $man = $row['machine_type'];
    $setup = $row['setup'];    
    $status = $row['status'];
    $sysDescr = $row['sysDescr'];
    $id = $row['id'];
    $serialNumber = $row['serialNumber'];
    $sysLocation = $row['sysLocation'];

    if($setup==1){        
        $setup = "Complete";
    }
    else {
        $setup = "New";  
    }


    if ($setup=='Complete') {

        echo   '

        <li class="list-group-item d-flex justify-content-between lh-condensed">
        <div>
          <h6 class="my-0">'.$sysDescr.'</h6>
          <small class="text-muted">Serial#: '.$serialNumber.'</small><br>
          </div>
         
        <span class="text-muted">'.$snmp_ip.'
       
        </span>
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

if ($man=="BH" && $status=="Active"){

echo '
<br>
<li class="list-group-item">
<div class="row">
          <div class="col-12 col-md">
          <h6 class="my-0">Consumables as of...</h6>
          <hr >
          </div>
          </div>
    <!-----Sart----->
        <div class="row">
        <div class="col-3 themed-grid-col">
        <h6 class="my-0">Date</h6>
         
        </div>
        <div class="col-2 themed-grid-col">
        <h6 class="my-0">Cyan</h6>
         
        </div>
        <div class="col-2 themed-grid-col">
        <h6 class="my-0">Magenta</h6>
          
        </div>
        <div class="col-2 themed-grid-col">
        <h6 class="my-0">Yellow</h6>
         
        </div>
        <div class="col-2 themed-grid-col">
        <h6 class="my-0">Black</h6>
       
      </div>
      
      </div>
      <br>
';
}

if ($man=="MFX" && $status=="Active"){

  echo '
  <br>
  <li class="list-group-item">
  <div class="row">
            <div class="col-12 col-md">
            <h6 class="my-0">Consumables as of...</h6>
            <hr >
            </div>
            </div>
      <!-----Sart----->
          <div class="row">
          <div class="col-3 themed-grid-col">
          <h6 class="my-0">Date</h6>
           
          </div>
          <div class="col-4 themed-grid-col">
          <h6 class="my-0">Black</h6>
           
          </div>
          <div class="col-4 themed-grid-col">
          <h6 class="my-0">Drum</h6>
           
          </div>
          
        </div>
        <br>
  ';
  }

while($row = $result_consume->fetch_assoc())
    {
        $t_cyan = $row['t_cyan'];
        $t_magenta = $row['t_magenta'];
        $t_black = $row['t_black'];
        $t_yellow = $row['t_yellow'];
        $drumBlack = $row['drum_black'];
        $consumable_read_date = $row['read_date'];

        if ($man=="BH" && $status=="Active"){

echo '

    <!-----Sart----->
        <div class="row">
        <div class="col-3 themed-grid-col">
        <ul class="list-unstyled text-small">
          <li><small class="text-muted" >'.$consumable_read_date.'</small>
          </li>
         </ul>
      </div>
        <div class="col-2 themed-grid-col">
          <ul class="list-unstyled text-small">
            <li>
            <div class="progress">
          <div class="progress-bar bg-cyan" style="width:'.$t_cyan.'%">'.$t_cyan.'%</div>
          </div></li>
           </ul>
        </div>
        <div class="col-2 themed-grid-col">
          <ul class="list-unstyled text-small">
            <li>
            <div class="progress">
            <div class="progress-bar bg-magenta" style="width:'.$t_magenta.'%">'.$t_magenta.'%</div>
            </div></li>
          </ul>
        </div>
        <div class="col-2 themed-grid-col">
          <ul class="list-unstyled text-small">
            <li><div class="progress">
            <div class="progress-bar bg-yellow text-primary" style="width:'.$t_yellow.'%">'.$t_yellow.'%</div>
            </div></li>
          </ul>
        </div>
        <div class="col-2 themed-grid-col">
        <ul class="list-unstyled text-small">
          <li><div class="progress">
          <div class="progress-bar bg-black" style="width:'.$t_black.'%">'.$t_black.'%</div>
          </div></li>
        </ul>
      </div>
      
      </div>
        <!------ END ----->

';
        }

        if ($man=="MFX" && $status=="Active"){

          echo '
          
              <!-----Sart----->
                  <div class="row">
                  <div class="col-3 themed-grid-col">
                  <ul class="list-unstyled text-small">
                    <li><small class="text-muted" >'.$consumable_read_date.'</small>
                    </li>
                   </ul>
                </div>
                  <div class="col-4 themed-grid-col">
                    <ul class="list-unstyled text-small">
                      <li><div class="progress">
                      <div class="progress-bar bg-black " style="width:'.$t_black.'%">'.$t_black.'%</div>
                      </div></li>
                    </ul>
                  </div>
                  <div class="col-4 themed-grid-col">
                  <ul class="list-unstyled text-small">
                    <li><div class="progress">
                    <div class="progress-bar bg-black " style="width:'.$drumBlack.'%">'.$drumBlack.'%</div>
                    </div></li>
                  </ul>
                </div>
                
                </div>
                  <!------ END ----->
          
          ';
                  }


}
  
echo '</li>
';
//echo '--------------'.$sysDescr.'------------------- <br>';
//echo 'RepairShopr Customer ID '.$rs_customer_id. '<br>';
//echo 'RepairShopr Asset ID '.$rs_asset_id. '<br>';
//echo 'IP Address '.$snmp_ip. '<br>';
//echo 'SNMP Community '.$snmpcommunity. '<br>';
//echo 'Machine Type '.$man. '<br>';
//echo 'Setup Completed '.$setup. '<br>';
//echo '<a data-confirm="Are you sure you want to remove this asset?" href="?action=remove&id='.$id.'">Remove Asset</a><br>';
//echo '<form action="add_asset.php" method="POST">
 //       <input type="text" id="id" name="id" value='.$id.' />
 //       <input type="submit" value="Remove" name="remove">
//        <br>';


if (isset($_POST['submitted'])) { 
    $post_rs_customer_id = $_POST['rs_customer_id'];
    $post_rs_asset_id = $_POST['rs_asset_id'];
    $post_snmp_ip =  $_POST['ipaddress'];
    $post_snmpcommunity = $_POST['snmp_community'];
    $post_man = $_POST['machine_type'];
    
    $sql_insert = <<<SQL
    INSERT INTO asset (rs_customer_id, rs_asset_id, ipaddress, snmp_community, machine_type)
    VALUES ('$post_rs_customer_id', '$post_rs_asset_id', '$post_snmp_ip', '$post_snmpcommunity','$post_man')
    SQL;
    mysqli_query($db,"$sql_insert");
    header("Location: asset_single.php");


  //  echo   '<br><h4 class="d-flex justify-content-between align-items-center mb-3">
  //  <span class="text-muted">Recently Added</span>
  //  <span class="badge badge-secondary badge-pill">3</span>
  //</h4><br><li class="list-group-item d-flex justify-content-between lh-condensed">
  //  <div>
  //    <h6 class="my-0">'.$sysDescr.'</h6>
  //    <small class="text-muted">CID: '.$rs_customer_id.'</small><br>
   //   <small class="text-muted">AID: '.$rs_asset_id.'</small>
  //  </div>
  //  <span class="text-muted">'.$snmp_ip.'<br>
  //  <a data-confirm="Are you sure you want to remove this asset?" href="?action=remove&id='.$id.'">Remove</a></span>
  //</li>';
    
  //  echo '--------------NEW ASSET------------------- <br>';
  //  echo 'SYSTEM DESCRIPTION '.$post_rs_customer_id. '<br>';
  //  echo 'SYSTEM DESCRIPTION '.$post_rs_asset_id. '<br>';
  //  echo 'SYSTEM DESCRIPTION '.$post_snmp_ip. '<br>';
  //  echo 'SYSTEM DESCRIPTION '.$post_snmpcommunity. '<br>';
  //  echo 'SYSTEM DESCRIPTION '.$post_man. '<br>';
  //  echo '<br>';
    
    } 
?>
      </ul>
    </div>
   
   
  </div>


  </div>

  </div>
</main>
<?php 

$db->close();
?>