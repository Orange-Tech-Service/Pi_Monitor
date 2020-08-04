<?php 

include("inc/_header.php");

if ($rs_customer_id<1){
echo "NO CUSTOMER";
header("Location: customer_details.php");
}
else {
echo $rs_business_name;
header("Location: asset_details.php");
}
?>

