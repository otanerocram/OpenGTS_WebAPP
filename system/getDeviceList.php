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
  $fleetID  = $_GET["fleetID"];

  $cars     = array();

  // Buscar todos los dispositivos de la flota

  if ($fleetID =='isAdmin'){
    $sql2="SELECT `deviceID`, `licensePlate`, `description` FROM Device WHERE `accountID`='$accID'";
  }else{
    $sql2="SELECT l.`deviceID`,d.`licensePlate`, d.`description` FROM DeviceList l, Device d 
WHERE l.`accountID`='$accID' AND l.`groupID`='$fleetID'
AND l.`accountID`=d.`accountID`
AND l.`deviceID`=d.`deviceID`";
  }

  $result2 = $conexion->query($sql2);
  $filas = $result2 -> num_rows;

  if ($filas > 0){
    while($row1 = $result2->fetch_array(MYSQLI_ASSOC)){
      $track[] = array(
        'deviceID'        => $row1['deviceID'],
        'licensePlate'    => utf8_encode($row1['licensePlate']),
        'description'     => utf8_encode($row1['description'])
      );
    } 
    
    echo json_encode($track);
  
  }else{
    $track[] = array(
        'deviceID'        => '',
        'licensePlate'    => '',
        'description'     => ''
      );
	  echo json_encode($track);

  }
  $conexion->close(); //cerramos la conexión
?>