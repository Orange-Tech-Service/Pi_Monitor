<?PHP
include("inc/_db.php");

$ip = shell_exec("/sbin/ifconfig  | grep 'inet'| grep -v '127.0.0.1' | cut -d: -f2 | awk '{ print $2}'");

 

$db = new mysqli($server, $db_user, $db_pass, $db_name);

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}

$ip_address = gethostbyname(trim(exec("hostname")));
$sql = "SELECT *  FROM `customer` LIMIT 1";
    
if(!$result = $db->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
}
while($row = $result->fetch_assoc()){
    $cust_rs_customer_id = $row['rs_customer_id'];
    $cust_rs_business_name = $row['rs_business_name'];
    $cust_email =  $row['email'];
    $cust_tech_email = $row['tech_email'];
    $cust_contact = $row['contact'];
    $cust_phone = $row['phone'];
    $cust_http_port = $row['http_port'];
    $cust_ssh_port = $row['ssh_port'];
    $cust_ip_address = $row['ip_address'];

}
?>
<html>
<head>
	<title><?php echo $cust_rs_business_name.'-'.$cust_rs_customer_id?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/app.css">
    <script src="assets/js/app.js"></script>
</head>