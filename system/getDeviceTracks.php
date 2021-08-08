<?php
  header('Content-Type: text/html; charset=ISO-8859-15');
  header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
  header("Cache-Control: no-store, no-cache, must-revalidate");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");

  // session_start();
  // if(isset($_SESSION["cuenta"])){

  // }else{
  //   echo "<head><link rel='stylesheet' href='../css/general.css' /></head>";

  //   die("<div id='alertContainer'><div id='alertMessage'>Debe ingresar al sistema antes de realizar cualquier consulta
  //     </div></div>"); 
  // }

  include('../../config.php');
  $conexion   = @new mysqli($database['hostname'], $database['username'], $database['password'], $database['database'], $database['port']);
  
  if ($conexion->connect_error){
	  die('Error de conexión: ' . $conexion->connect_error); 
  }

  $accID    = $_GET["accID"];
  $devID    = $_GET["devID"];

  $sql2="SELECT e.deviceID, e.`timestamp`, e.`statusCode`, e.latitude, e.longitude, e.speedKPH, e.heading,e.streetAddress, e.city 
  FROM EventData e 
  WHERE e.accountID='$accID' and e.deviceID='$devID' 
  order by e.`timestamp` desc limit 10;";
  

  $result2 = $conexion->query($sql2);
  $filas = $result2 -> num_rows;

  if ($filas > 0){
    while($row1 = $result2->fetch_array(MYSQLI_ASSOC)){
      $track[] = array(
        'deviceID'      => utf8_encode($row1['deviceID']),
        'timestamp'     => utf8_encode($row1['timestamp']),
        'statusCode'    => utf8_encode($row1['statusCode']),
        'latitude'      => utf8_encode($row1['latitude']),
        'longitude'     => utf8_encode($row1['longitude']),
        'speedKPH'      => utf8_encode($row1['speedKPH']),
        'heading'       => utf8_encode($row1['heading']),
        'streetAddress' => utf8_encode($row1['streetAddress']),
        'city'          => utf8_encode($row1['city'])
      );
    } 
    
    echo json_encode($track);
  
  }else{
    $track[] = array(
        'deviceID'   => "-",
        'timestamp'   => "-",
        'statusCode'   => "-",
        'latitude'   => "-",
        'longitude'   => "-",
        'speedKPH'   => "-",
        'heading'   => "-",
        'streetAddress'   => "-",
        'city'   => "-"
      );
	  echo json_encode($track);

  }
  $conexion->close(); //cerramos la conexión
?>