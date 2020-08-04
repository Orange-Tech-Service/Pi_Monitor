<header>
  <!-- Fixed navbar -->
  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <a class="navbar-brand" href="#">
    <?PHP if ($cust_rs_business_name == NULL) {
      echo '';
    }else {        
    echo $cust_rs_business_name; }
    ?> 
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="customer_details.php">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="asset_details.php">Assets <span class="sr-only">(current)</span></a>
        </li>
       <!--  <li class="nav-item">
          <a class="nav-link" href="asset_details.php">Assets</a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="#" tabindex="-1" aria-disabled="false">Consumables</a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="#" tabindex="-1" aria-disabled="false">Meter</a>
        </li>
      
        <li class="nav-item">
          <a class="nav-link " href="#" tabindex="-1" aria-disabled="false">Logs</a>
        </li>
        -->
        <li class="nav-item">
          <a class="nav-link" href="schedule" target="_blank">Schedule</a>
        </li>
      </ul>
      <div style="float:right; color:white;"><?PHP
      
      $f = fopen("/sys/class/thermal/thermal_zone0/temp","r");
 $temp = fgets($f);
 $c_temp = round($temp/1000);
 $f_temp = $c_temp * 1.8 + 32;
 echo 'TEMP: '.$f_temp;
 fclose($f);
      
      ?>  - - - IP ADDDRESS: <?PHP echo $ip; ?>  - - - PORTS: <?PHP echo $cust_ssh_port; ?> / <?PHP echo $cust_http_port; ?> </div>
      </div>
  </nav>
</header>