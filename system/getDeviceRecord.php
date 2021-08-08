<?php header('Content-Type: text/html; charset=ISO-8859-15'); ?>
<?php
  header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
  header("Cache-Control: no-store, no-cache, must-revalidate");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");
  include('../../config.php');
  $conexion   = @new mysqli($database['hostname'], $database['username'], $database['password'], $database['database'], $database['port']);
  
  if ($conexion->connect_error){
	  die('Error de conexión: ' . $conexion->connect_error); 
  }

  $accID    = $_GET["accID"];
  $devID    = $_GET["devID"];
  
  $sql2="SELECT d.deviceID, d.uniqueID, d.description, d.licensePlate, d.simPhoneNumber,
  e.`timestamp`, e.`statusCode`, e.latitude, e.longitude, e.speedKPH, e.heading
  from EventData e, Device d
  where e.deviceID=d.deviceID
  AND e.accountID=d.accountID
  AND e.accountID='$accID' and e.deviceID='$devID'
  order by e.`timestamp` desc
  limit 1;";

  $result2 = $conexion->query($sql2);
  if ($result2->num_rows > 0){
	  
	  while($row = $result2->fetch_array(MYSQLI_ASSOC)){
      $track[] = array(
        'deviceID'      => $row['deviceID']
      );
    }
  echo json_encode($track);


  }else{
    $track[] = array(
        'deviceID'   => "-"
      );
	  echo json_encode($track);

  }
  $conexion->close(); //cerramos la conexión
?>